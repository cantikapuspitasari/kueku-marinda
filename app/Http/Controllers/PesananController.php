<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePesananRequest;

class PesananController extends Controller
{
    // Menampilkan daftar pesanan milik pembeli yang sedang login
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

    // Simpan pesanan baru + detail item-nya sekaligus
    // S2-05: validasi input sudah dipindahkan ke StorePesananRequest
    public function store(StorePesananRequest $request)
    {
        $validated = $request->validated();

        try {
            $pesanan = DB::transaction(function () use ($validated) {

                // Gabungkan produk yang sama terlebih dahulu.
                $gabungan = collect($validated['items'])
                    ->groupBy('id_produk')
                    ->map(fn ($baris, $id) => [
                        'id_produk' => (int) $id,
                        'jumlah'    => $baris->sum('jumlah'),
                    ])
                    ->values();

                $totalHarga = 0;
                $items = [];

                foreach ($gabungan as $item) {

                    // Lock produk sampai transaksi selesai
                    // untuk mencegah overselling.
                    $produk = Produk::where(
                        'id_produk',
                        $item['id_produk']
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$produk) {
                        throw new \Exception(
                            "Produk tidak ditemukan"
                        );
                    }

                    if ($produk->status_produk !== 'TERSEDIA') {
                        throw new \Exception(
                            "Produk {$produk->nama_produk} sedang tidak tersedia"
                        );
                    }

                    if ($produk->stok < $item['jumlah']) {
                        throw new \Exception(
                            "Stok {$produk->nama_produk} tidak cukup (tersisa {$produk->stok})"
                        );
                    }

                    $subtotal = $produk->harga * $item['jumlah'];

                    $totalHarga += $subtotal;

                    $items[] = [
                        'id_produk' => $produk->id_produk,
                        'jumlah'    => $item['jumlah'],
                        'subtotal'  => $subtotal,
                    ];
                }

                // Membuat kode pesanan
                $prefix = 'KM-' . now()->format('Ymd') . '-';

                $nomorTerakhir = Pesanan::where(
                    'kode_pesanan',
                    'like',
                    $prefix . '%'
                )
                    ->lockForUpdate()
                    ->max(
                        DB::raw(
                            "CAST(SUBSTRING_INDEX(kode_pesanan, '-', -1) AS UNSIGNED)"
                        )
                    );

                $kodePesanan = $prefix . str_pad(
                    ($nomorTerakhir ?? 0) + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                // Buat pesanan utama
                $pesananBaru = Pesanan::create([
                    'kode_pesanan'      => $kodePesanan,
                    'id_pembeli'        => Auth::guard('pembeli')->id(),
                    'id_alamat'         => $validated['id_alamat'] ?? null,
                    'tanggal_ambil'     => $validated['tanggal_ambil'],
                    'jenis_pengambilan' => $validated['jenis_pengambilan'],
                    'status_pesanan'    => 'PENDING',
                    'total_harga'       => $totalHarga,
                ]);

                // Buat detail pesanan dan kurangi stok
                foreach ($items as $item) {

                    DetailPesanan::create([
                        'id_pesanan' => $pesananBaru->id_pesanan,
                        'id_produk'  => $item['id_produk'],
                        'jumlah'     => $item['jumlah'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    Produk::where(
                        'id_produk',
                        $item['id_produk']
                    )->decrement(
                        'stok',
                        $item['jumlah']
                    );

                    // Sesuaikan status produk setelah stok berkurang
                    $produk = Produk::find($item['id_produk']);

                    if ($produk) {
                        $produk->sesuaikanStatus();
                    }
                }

                return $pesananBaru;
            });

            return redirect()
                ->route('pesanan.show', $pesanan->id_pesanan)
                ->with(
                    'success',
                    'Pesanan berhasil dibuat dengan kode ' .
                    $pesanan->kode_pesanan
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    // Detail 1 pesanan
    // Hanya pembeli yang memiliki pesanan tersebut yang dapat melihatnya
    public function show($id)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'pembeli',
            'alamat',
            'pembayaran',
            'pengiriman'
        ])
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->findOrFail($id);

        return view('pesanan.show', compact('pesanan'));
    }

    // Update status pesanan (dipakai Admin)
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:PENDING,DIKONFIRMASI,DIPROSES,SELESAI,DIBATALKAN',
        ]);

        // Perubahan status yang diizinkan
        $alur = [
            'PENDING'      => ['DIKONFIRMASI', 'DIBATALKAN'],
            'DIKONFIRMASI' => ['DIPROSES', 'DIBATALKAN'],
            'DIPROSES'     => ['SELESAI', 'DIBATALKAN'],
            'SELESAI'      => [],
            'DIBATALKAN'   => [],
        ];

        try {
            DB::transaction(function () use ($id, $validated, $alur) {

                // Kunci pesanan supaya tidak diubah
                // oleh dua admin secara bersamaan.
                $pesanan = Pesanan::with('detailPesanan')
                    ->lockForUpdate()
                    ->findOrFail($id);

                $lama = $pesanan->status_pesanan;
                $baru = $validated['status_pesanan'];

                // Cek apakah perubahan status diperbolehkan
                if (!in_array($baru, $alur[$lama] ?? [])) {
                    throw new \DomainException(
                        "Status tidak bisa diubah dari {$lama} ke {$baru}"
                    );
                }

                // Pesanan harus sudah memiliki pembayaran
                // sebelum dapat diselesaikan.
                if (
                    $baru === 'SELESAI' &&
                    !$pesanan->pembayaran()->exists()
                ) {
                    throw new \DomainException(
                        'Catat pembayaran dulu sebelum menyelesaikan pesanan'
                    );
                }

                // Jika pesanan dibatalkan
                if ($baru === 'DIBATALKAN') {

                    // Pesanan yang sudah dibayar tidak boleh dibatalkan
                    if ($pesanan->pembayaran()->exists()) {
                        throw new \DomainException(
                            'Pesanan yang sudah dibayar tidak bisa dibatalkan'
                        );
                    }

                    // Kembalikan stok
                    foreach ($pesanan->detailPesanan as $detail) {

                        Produk::where(
                            'id_produk',
                            $detail->id_produk
                        )->increment(
                            'stok',
                            $detail->jumlah
                        );

                        // Sesuaikan kembali status produk
                        $produk = Produk::find($detail->id_produk);

                        if ($produk) {
                            $produk->sesuaikanStatus();
                        }
                    }
                }

                // Simpan status pesanan
                $pesanan->update([
                    'status_pesanan' => $baru
                ]);
            });

        } catch (\DomainException $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui'
        );
    }

    // Daftar SEMUA pesanan - khusus Admin & Owner
    public function indexStaff()
    {
        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'pembeli'
        ])
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('pesanan.index-staff', compact('pesanan'));
    }
}