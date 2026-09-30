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
            'tanggal_ambil'      => 'required|date|after_or_equal:today',
            'jenis_pengambilan'  => 'required|in:PICKUP,DELIVERY',
            'items'              => 'required|array|min:1',
            'items.*.id_produk'  => 'required|exists:produk,id_produk',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        // Kalau delivery, alamat wajib diisi
        if ($validated['jenis_pengambilan'] === 'DELIVERY' && empty($validated['id_alamat'])) {
            return back()->withErrors(['id_alamat' => 'Alamat wajib diisi untuk pesanan delivery']);
        }

        try {
            // Simpan pesanan + detail dalam satu transaction, biar kalau ada yang gagal, semua dibatalkan (rollback)
            $pesanan = DB::transaction(function () use ($validated) {

                $totalHarga = 0;
                $items = [];

                foreach ($validated['items'] as $item) {
                    // S2-06: lockForUpdate() mengunci baris produk ini sampai transaksi selesai.
                    // Pembeli lain yang coba pesan produk yang sama HARUS menunggu giliran,
                    // sehingga stok tidak pernah dibaca dua kali sebelum sempat dikurangi (anti-oversell).
                    $produk = Produk::where('id_produk', $item['id_produk'])
                        ->lockForUpdate()
                        ->first();

                    if (!$produk) {
                        throw new \Exception("Produk tidak ditemukan");
                    }
                    if ($produk->status_produk !== 'TERSEDIA') {
                        throw new \Exception("Produk {$produk->nama_produk} sedang tidak tersedia");
                    }
                    if ($produk->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$produk->nama_produk} tidak cukup (tersisa {$produk->stok})");
                    }

                    $subtotal = $produk->harga * $item['jumlah'];
                    $totalHarga += $subtotal;

                    $items[] = [
                        'id_produk' => $produk->id_produk,
                        'jumlah'    => $item['jumlah'],
                        'subtotal'  => $subtotal,
                    ];
                }

                // Bikin kode pesanan otomatis: KM-YYYYMMDD-XXX
                $kodePesanan = 'KM-' . now()->format('Ymd') . '-' . str_pad(Pesanan::count() + 1, 3, '0', STR_PAD_LEFT);

                $pesananBaru = Pesanan::create([
                    'kode_pesanan'      => $kodePesanan,
                    'id_user'           => Auth::id(),
                    'id_alamat'         => $validated['id_alamat'] ?? null,
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

            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat dengan kode ' . $pesanan->kode_pesanan);

        } catch (\Exception $e) {
            // S2-06 DoD: pesanan kedua yang bentrok stok otomatis DITOLAK, bukan crash
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // Detail 1 pesanan
    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'pembeli', 'alamat', 'pembayaran', 'pengiriman'])
            ->findOrFail($id);

        return view('pesanan.show', compact('pesanan'));
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