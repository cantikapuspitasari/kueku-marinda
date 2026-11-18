<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesananRequest;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Support\Ongkir;
use App\Support\Telepon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    const ONGKIR = 5000;
    const MIN_JAM_PROSES = 3;

    const JAM_OPERASIONAL = [
        'PICKUP' => ['08:00', '15:00'],
        'DELIVERY' => ['09:00', '18:00'],
    ];

    // Daftar pesanan milik pembeli yang sedang login
    public function index()
    {
        $pembeli = Auth::guard('pembeli')->user();

        $pesanan = Pesanan::with('detailPesanan.produk')
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('pesanan.index', compact('pesanan'));
    }

    // Form buat pesanan baru
    public function create()
    {
        $produk = Produk::where('status_produk', 'TERSEDIA')->get();

        return view('pesanan.create', compact('produk'));
    }

    // Simpan pesanan baru
    public function store(StorePesananRequest $request)
    {
        // Hitung ongkir tanpa membuat pesanan
        if ($request->input('mode') === 'hitung_ongkir') {
            return $this->hitungOngkir($request);
        }

        $validated = $request->validated();

        $validated['no_telepon'] = Telepon::normalisasi(
            $validated['no_telepon']
        );

        $delivery = $validated['jenis_pengambilan'] === 'DELIVERY';

        // Validasi jam operasional
        [$buka, $tutup] = self::JAM_OPERASIONAL[
            $validated['jenis_pengambilan']
        ];

        if (
            $validated['jam_ambil'] < $buka ||
            $validated['jam_ambil'] >= $tutup
        ) {
            return $this->gagal(
                $request,
                "Jam di luar jam operasional ($buka - $tutup WIB)."
            );
        }

        // Validasi minimal 3 jam sebelum pengambilan
        $wib = 'Asia/Jakarta';

        $waktuPilihan = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['tanggal_ambil'] . ' ' . $validated['jam_ambil'],
            $wib
        );

        $minimal = Carbon::now($wib)->addHours(self::MIN_JAM_PROSES);

        if ($waktuPilihan->lt($minimal)) {
            return $this->gagal(
                $request,
                'Pesanan diproses minimal ' .
                self::MIN_JAM_PROSES .
                ' jam sebelum diambil/dikirim. Waktu paling cepat: ' .
                $minimal->format('d-m-Y H:i') .
                ' WIB.'
            );
        }

        // Validasi maksimal jadwal 7 hari ke depan
        if (
            $waktuPilihan->gt(
                Carbon::now($wib)->addDays(7)->endOfDay()
            )
        ) {
            return $this->gagal(
                $request,
                'Pesanan hanya bisa dijadwalkan sampai 7 hari ke depan.'
            );
        }

        // Delivery wajib memiliki alamat
        if (
            $delivery &&
            empty($validated['id_alamat']) &&
            empty($validated['alamat_lengkap'])
        ) {
            return $this->gagal(
                $request,
                'Alamat wajib diisi untuk pesanan delivery.'
            );
        }

        // Ongkir dihitung menggunakan token server
        $ongkir = 0;
        $titik = null;

        if ($delivery) {
            $tok = Ongkir::bacaToken(
                $validated['ongkir_token'] ?? null
            );

            if (!$tok) {
                return $this->gagal(
                    $request,
                    'Tandai lokasi pengantaran di peta dulu agar ongkir bisa dihitung. Jika sudah lama, pilih ulang lokasinya.'
                );
            }

            $ongkir = (int) $tok['ongkir'];

            $titik = round($tok['lat'], 5) .
                ', ' .
                round($tok['lng'], 5);
        }

        // Gabungkan produk yang sama
        $gabung = [];

        foreach ($validated['items'] as $item) {
            $id = (int) $item['id_produk'];

            $gabung[$id] = ($gabung[$id] ?? 0) +
                (int) $item['jumlah'];
        }

        try {
            $pesanan = DB::transaction(function () use (
                $validated,
                $gabung,
                $delivery,
                $ongkir,
                $titik
            ) {
                $pembeli = Auth::guard('pembeli')->user();

                if (!$pembeli) {
                    throw new \DomainException(
                        'Silakan login sebagai pembeli terlebih dahulu.'
                    );
                }

                $totalHarga = 0;
                $items = [];

                // Perbarui nomor telepon pembeli
                DB::table('pembeli')
                    ->where('id_pembeli', $pembeli->id_pembeli)
                    ->update([
                        'no_telepon' => $validated['no_telepon'],
                    ]);

                // Periksa dan kunci stok produk
                foreach ($gabung as $idProduk => $jumlah) {
                    $produk = Produk::where('id_produk', $idProduk)
                        ->lockForUpdate()
                        ->first();

                    if (!$produk) {
                        throw new \Exception(
                            'Produk tidak ditemukan.'
                        );
                    }

                    if ($produk->status_produk !== 'TERSEDIA') {
                        throw new \Exception(
                            "Produk {$produk->nama_produk} sedang tidak tersedia."
                        );
                    }

                    if ($produk->stok < $jumlah) {
                        throw new \Exception(
                            "Stok {$produk->nama_produk} tidak cukup (tersisa {$produk->stok})."
                        );
                    }

                    // Harga diambil langsung dari database
                    $subtotal = $produk->harga * $jumlah;

                    $totalHarga += $subtotal;

                    $items[] = [
                        'id_produk' => $produk->id_produk,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal,
                    ];
                }

                // Tambahkan ongkir jika delivery
                if ($delivery) {
                    $totalHarga += $ongkir;
                }

                // Simpan alamat baru jika diperlukan
                $idAlamat = $validated['id_alamat'] ?? null;

                if ($delivery && !$idAlamat) {
                    $idAlamat = DB::table('alamat')->insertGetId([
                        'id_pembeli' => $pembeli->id_pembeli,
                        'label_alamat' => 'Rumah',
                        'alamat_lengkap' => Str::limit(
                            $validated['alamat_lengkap'],
                            200,
                            ''
                        ) . ' (Titik peta: ' . $titik . ')',
                        'kota' => '-',
                        'kode_pos' => '-',
                    ]);
                }

                // Buat kode pesanan
                $urutan = Pesanan::whereDate(
                    'tanggal_pesan',
                    now()->toDateString()
                )->count() + 1;

                $kodePesanan = 'KM-' .
                    now()->format('Ymd') .
                    '-' .
                    str_pad($urutan, 3, '0', STR_PAD_LEFT);

                // Buat pesanan utama
                $pesananBaru = Pesanan::create([
                    'kode_pesanan' => $kodePesanan,
                    'id_pembeli' => $pembeli->id_pembeli,
                    'id_alamat' => $idAlamat,
                    'tanggal_pesan' => now(),
                    'tanggal_ambil' => $validated['tanggal_ambil'],
                    'jam_ambil' => $validated['jam_ambil'],
                    'jenis_pengambilan' => $validated['jenis_pengambilan'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    // Bank tujuan hanya disimpan untuk Transfer Bank
                    'bank_tujuan' => $validated['metode_pembayaran'] === 'TRANSFER'
                        ? ($validated['bank_tujuan'] ?? null)
                        : null,
                    'status_pesanan' => 'PENDING',
                    'total_harga' => $totalHarga,
                ]);

                // Simpan detail pesanan dan kurangi stok
                foreach ($items as $item) {
                    DetailPesanan::create([
                        'id_pesanan' => $pesananBaru->id_pesanan,
                        'id_produk' => $item['id_produk'],
                        'jumlah' => $item['jumlah'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    Produk::where(
                        'id_produk',
                        $item['id_produk']
                    )->decrement('stok', $item['jumlah']);

                    $produk = Produk::find($item['id_produk']);

                    if ($produk) {
                        $produk->sesuaikanStatus();
                    }
                }

                return $pesananBaru;
            });

            // Respons JSON untuk checkout menggunakan fetch
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'id_pesanan' => $pesanan->id_pesanan,
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'total_harga' => (float) $pesanan->total_harga,
                    'metode_pembayaran' => $pesanan->metode_pembayaran,
                    // QRIS: pembeli diarahkan ke halaman QR Code untuk membayar
                    'qris_url' => $pesanan->isQris()
                        ? route('qris.bayar', $pesanan->id_pesanan)
                        : null,
                    // Transfer Bank: pembeli diarahkan ke halaman rekening & unggah bukti
                    'transfer_url' => $pesanan->isTransfer()
                        ? route('transfer.bayar', $pesanan->id_pesanan)
                        : null,
                ]);
            }

            if ($pesanan->isQris()) {
                return redirect()->route('qris.bayar', $pesanan->id_pesanan);
            }

            if ($pesanan->isTransfer()) {
                return redirect()->route('transfer.bayar', $pesanan->id_pesanan);
            }

            return redirect()
                ->route('pesanan.show', $pesanan->id_pesanan)
                ->with(
                    'success',
                    'Pesanan berhasil dibuat dengan kode ' .
                    $pesanan->kode_pesanan
                );

        } catch (\Exception $e) {
            return $this->gagal($request, $e->getMessage());
        }
    }

    // Hitung ongkir berdasarkan koordinat peta
    private function hitungOngkir(Request $request)
    {
        $d = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ], [
            'lat.required' => 'Lokasi belum dipilih di peta.',
            'lng.required' => 'Lokasi belum dipilih di peta.',
        ]);

        $lat = (float) $d['lat'];
        $lng = (float) $d['lng'];

        if (!Ongkir::koordinatValid($lat, $lng)) {
            return $this->gagal(
                $request,
                'Lokasi tidak valid. Pilih lokasi di wilayah Indonesia.'
            );
        }

        [$km, $metode] = Ongkir::jarakKm($lat, $lng);

        $ongkir = Ongkir::tarif($km);

        if ($ongkir === null) {
            return $this->gagal(
                $request,
                'Lokasi terlalu jauh (' .
                $km .
                ' km). Pengantaran maksimal ' .
                Ongkir::MAKS_KM .
                ' km dari toko. Silakan pilih "Ambil di toko".'
            );
        }

        return response()->json([
            'ok' => true,
            'jarak_km' => $km,
            'ongkir' => $ongkir,
            'metode' => $metode,
            'token' => Ongkir::buatToken(
                $lat,
                $lng,
                $km,
                $ongkir
            ),
        ]);
    }

    // Respons error untuk checkout JSON atau form biasa
    private function gagal(Request $request, string $pesan)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $pesan,
            ], 422);
        }

        return back()
            ->withInput()
            ->with('error', $pesan);
    }

    // Detail pesanan milik pembeli
    public function show($id)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'pembeli',
            'alamat',
            'pembayaran',
            'pengiriman',
        ])
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->findOrFail($id);

        return view('pesanan.show', compact('pesanan'));
    }

    // Halaman pesanan terkonfirmasi
    public function selesai()
    {
        return view('pesanan.selesai');
    }

    // Halaman detail pesanan / tracking
    public function detail()
    {
        return view('pesanan.detail', [
            'produkJs' => ProdukController::katalogJs(),
        ]);
    }

    // Halaman admin: pesanan masuk
    public function admin()
    {
        $pesanan = Pesanan::with([
            'pembeli',
            'detailPesanan.produk',
        ])
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('admin.pesanan', compact('pesanan'));
    }

    // Update status pesanan oleh Admin
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:PENDING,DIKONFIRMASI,DIPROSES,SELESAI,DIBATALKAN',
        ]);

        $baru = $validated['status_pesanan'];

        $alur = [
            'PENDING' => ['DIKONFIRMASI', 'DIBATALKAN'],
            'DIKONFIRMASI' => ['DIPROSES', 'DIBATALKAN'],
            'DIPROSES' => ['SELESAI', 'DIBATALKAN'],
            'SELESAI' => [],
            'DIBATALKAN' => [],
        ];

        try {
            DB::transaction(function () use ($id, $baru, $alur) {
                $pesanan = Pesanan::with('detailPesanan')
                    ->lockForUpdate()
                    ->findOrFail($id);

                $lama = $pesanan->status_pesanan;

                if (!in_array($baru, $alur[$lama] ?? [], true)) {
                    throw new \DomainException(
                        "Status tidak bisa diubah dari {$lama} ke {$baru}."
                    );
                }

                // QRIS / Transfer Bank: pesanan baru boleh diverifikasi setelah pembayaran LUNAS
                // (Transfer: LUNAS hanya setelah Admin menerima bukti transfer)
                if (
                    $baru === 'DIKONFIRMASI' &&
                    $pesanan->isNonTunai() &&
                    $pesanan->status_pembayaran !== 'LUNAS'
                ) {
                    throw new \DomainException(
                        $pesanan->isTransfer()
                            ? 'Pembayaran Transfer Bank belum LUNAS. Verifikasi bukti transfer di menu Pembayaran Transfer terlebih dahulu.'
                            : 'Pembayaran QRIS belum LUNAS. Tunggu pembeli menyelesaikan pembayaran.'
                    );
                }

                // Pesanan harus memiliki pembayaran sebelum selesai
                if (
                    $baru === 'SELESAI' &&
                    !$pesanan->sudahDibayar()
                ) {
                    throw new \DomainException(
                        'Catat pembayaran terlebih dahulu sebelum menyelesaikan pesanan.'
                    );
                }

                if ($baru === 'DIBATALKAN') {
                    // Pesanan yang sudah dibayar tidak boleh dibatalkan
                    if ($pesanan->sudahDibayar()) {
                        throw new \DomainException(
                            'Pesanan yang sudah dibayar tidak bisa dibatalkan.'
                        );
                    }

                    // Kembalikan stok produk
                    foreach ($pesanan->detailPesanan as $detail) {
                        Produk::where(
                            'id_produk',
                            $detail->id_produk
                        )->increment('stok', $detail->jumlah);

                        $produk = Produk::find($detail->id_produk);

                        if ($produk) {
                            $produk->sesuaikanStatus();
                        }
                    }
                }

                $pesanan->update([
                    'status_pesanan' => $baru,
                ]);
            });

        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui.'
        );
    }

    // Daftar semua pesanan khusus Admin dan Owner
    public function indexStaff()
    {
        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'pembeli',
        ])
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('pesanan.index-staff', compact('pesanan'));
    }
}
