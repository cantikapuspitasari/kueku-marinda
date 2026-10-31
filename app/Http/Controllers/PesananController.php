<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // Ongkos kirim tetap untuk pesanan DELIVERY (dipakai juga oleh halaman keranjang, checkout, dan detail)
    const ONGKIR = 10000;

    // Menampilkan daftar pesanan (beda isi tergantung role: pembeli lihat punya sendiri, admin lihat semua)
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'PEMBELI') {
            $pesanan = Pesanan::with('detailPesanan.produk')
                ->where('id_user', $user->id_user)
                ->orderBy('tanggal_pesan', 'desc')
                ->get();
        } else {
            // Admin/Owner lihat semua pesanan
            $pesanan = Pesanan::with(['detailPesanan.produk', 'pembeli'])
                ->orderBy('tanggal_pesan', 'desc')
                ->get();
        }

        return view('pesanan.index', compact('pesanan'));
    }

    // Form buat pesanan baru
    public function create()
    {
        $produk = Produk::where('status_produk', 'TERSEDIA')->get();
        return view('pesanan.create', compact('produk'));
    }

    // Simpan pesanan baru + detail item-nya sekaligus
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_alamat'          => 'nullable|exists:alamat,id_alamat',
            'alamat_lengkap'     => 'nullable|string|max:500',
            'no_telepon'         => 'nullable|string|max:20',
            'tanggal_ambil'      => 'required|date|after_or_equal:today',
            'jenis_pengambilan'  => 'required|in:PICKUP,DELIVERY',
            'items'              => 'required|array|min:1',
            'items.*.id_produk'  => 'required|exists:produk,id_produk',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        $delivery = $validated['jenis_pengambilan'] === 'DELIVERY';

        // Kalau delivery, alamat wajib diisi (pilih alamat tersimpan atau ketik alamat baru)
        if ($delivery && empty($validated['id_alamat']) && empty($validated['alamat_lengkap'])) {
            return $this->gagal($request, 'Alamat wajib diisi untuk pesanan delivery');
        }

        // Gabungkan item yang produknya sama supaya pengecekan stok akurat
        $gabung = [];
        foreach ($validated['items'] as $item) {
            $id = (int) $item['id_produk'];
            $gabung[$id] = ($gabung[$id] ?? 0) + (int) $item['jumlah'];
        }

        try {
            // Simpan pesanan + detail dalam satu transaction, biar kalau ada yang gagal, semua dibatalkan (rollback)
            $pesanan = DB::transaction(function () use ($validated, $gabung, $delivery) {

                $user = Auth::user();
                $totalHarga = 0;
                $items = [];

                foreach ($gabung as $idProduk => $jumlah) {
                    // S2-06: lockForUpdate() mengunci baris produk ini sampai transaksi selesai.
                    // Pembeli lain yang coba pesan produk yang sama HARUS menunggu giliran,
                    // sehingga stok tidak pernah dibaca dua kali sebelum sempat dikurangi (anti-oversell).
                    $produk = Produk::where('id_produk', $idProduk)
                        ->lockForUpdate()
                        ->first();

                    if (!$produk) {
                        throw new \Exception("Produk tidak ditemukan");
                    }
                    if ($produk->status_produk !== 'TERSEDIA') {
                        throw new \Exception("Produk {$produk->nama_produk} sedang tidak tersedia");
                    }
                    if ($produk->stok < $jumlah) {
                        throw new \Exception("Stok {$produk->nama_produk} tidak cukup (tersisa {$produk->stok})");
                    }

                    // Harga selalu diambil dari database, bukan dari kiriman browser
                    $subtotal = $produk->harga * $jumlah;
                    $totalHarga += $subtotal;

                    $items[] = [
                        'id_produk' => $produk->id_produk,
                        'jumlah'    => $jumlah,
                        'subtotal'  => $subtotal,
                    ];
                }

                // Pesanan diantar: tambahkan ongkir
                if ($delivery) {
                    $totalHarga += self::ONGKIR;
                }

                // Alamat: kalau pelanggan mengetik alamat baru, simpan ke tabel alamat.
                // (alamat.id_pembeli mengacu ke tabel pembeli, dicocokkan lewat email akun)
                $idAlamat = $validated['id_alamat'] ?? null;
                if ($delivery && !$idAlamat) {
                    $idPembeli = DB::table('pembeli')->where('email', $user->email)->value('id_pembeli');
                    if (!$idPembeli) {
                        $idPembeli = DB::table('pembeli')->insertGetId([
                            'nama'       => $user->nama,
                            'email'      => $user->email,
                            'password'   => $user->password,
                            'no_telepon' => $validated['no_telepon'] ?? $user->no_telepon,
                        ]);
                    }

                    $idAlamat = DB::table('alamat')->insertGetId([
                        'id_pembeli'     => $idPembeli,
                        'label_alamat'   => 'Rumah',
                        'alamat_lengkap' => $validated['alamat_lengkap'],
                        'kota'           => '-',
                        'kode_pos'       => '-',
                    ]);
                }

                // Bikin kode pesanan otomatis: KM-YYYYMMDD-XXX (urutan per hari)
                $urutan = Pesanan::whereDate('tanggal_pesan', now()->toDateString())->count() + 1;
                $kodePesanan = 'KM-' . now()->format('Ymd') . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

                $pesananBaru = Pesanan::create([
                        'kode_pesanan'      => $kodePesanan,
                        'id_pembeli'        => DB::table('pembeli')
                                ->where('email', $user->email)
                                ->value('id_pembeli'),
                        'id_alamat'         => $idAlamat,
                        'tanggal_pesan'     => now(),
                        'tanggal_ambil'     => $validated['tanggal_ambil'],
                        'jenis_pengambilan' => $validated['jenis_pengambilan'],
                        'status_pesanan'    => 'PENDING',
                        'total_harga'       => $totalHarga,
                    ]);
                // Simpan tiap item ke detail_pesanan, sekalian kurangi stok
                foreach ($items as $item) {
                    DetailPesanan::create([
                        'id_pesanan' => $pesananBaru->id_pesanan,
                        'id_produk'  => $item['id_produk'],
                        'jumlah'     => $item['jumlah'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    // Masih di dalam baris yang sudah terkunci -> aman dari race condition
                    Produk::where('id_produk', $item['id_produk'])->decrement('stok', $item['jumlah']);
                }

                return $pesananBaru;
            });

            // Dipanggil dari halaman checkout (fetch) -> balas JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'ok'            => true,
                    'id_pesanan'    => $pesanan->id_pesanan,
                    'kode_pesanan'  => $pesanan->kode_pesanan,
                    'total_harga'   => (float) $pesanan->total_harga,
                ]);
            }

            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat dengan kode ' . $pesanan->kode_pesanan);

        } catch (\Exception $e) {
            // S2-06 DoD: pesanan kedua yang bentrok stok otomatis DITOLAK, bukan crash
            return $this->gagal($request, $e->getMessage());
        }
    }

    // Balasan error yang sesuai: JSON untuk checkout, redirect untuk form biasa
    private function gagal(Request $request, string $pesan)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $pesan], 422);
        }
        return back()->withInput()->with('error', $pesan);
    }

    // Detail 1 pesanan
    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'pembeli', 'alamat', 'pembayaran', 'pengiriman'])
            ->findOrFail($id);

        return view('pesanan.show', compact('pesanan'));
    }

    // Halaman "Pesanan Terkonfirmasi"
    public function selesai()
    {
        return view('pesanan.selesai');
    }

    // Halaman "Detail Pesanan" (tracking)
    public function detail()
    {
        return view('pesanan.detail', ['produkJs' => ProdukController::katalogJs()]);
    }

    // Halaman admin: Pesanan Masuk
    public function admin()
    {
        $pesanan = Pesanan::with(['pembeli', 'detailPesanan.produk'])
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('admin.pesanan', compact('pesanan'));
    }

    // Update status pesanan (dipakai Admin)
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:PENDING,DIKONFIRMASI,DIPROSES,SELESAI,DIBATALKAN',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update($validated);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

}
