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

                $kodePesanan = 'KM-' . now()->format('Ymd') . '-' . str_pad(Pesanan::count() + 1, 3, '0', STR_PAD_LEFT);

                $pesananBaru = Pesanan::create([
                    'kode_pesanan'      => $kodePesanan,
                    'id_pembeli'        => Auth::guard('pembeli')->id(),
                    'id_alamat'         => $validated['id_alamat'] ?? null,
                    'tanggal_ambil'     => $validated['tanggal_ambil'],
                    'jenis_pengambilan' => $validated['jenis_pengambilan'],
                    'status_pesanan'    => 'PENDING',
                    'total_harga'       => $totalHarga,
                ]);

                foreach ($items as $item) {
                    DetailPesanan::create([
                        'id_pesanan' => $pesananBaru->id_pesanan,
                        'id_produk'  => $item['id_produk'],
                        'jumlah'     => $item['jumlah'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    Produk::where('id_produk', $item['id_produk'])->decrement('stok', $item['jumlah']);
                }

                return $pesananBaru;
            });

            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat dengan kode ' . $pesanan->kode_pesanan);

        } catch (\Exception $e) {
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
// Daftar SEMUA pesanan - khusus Admin & Owner
    public function indexStaff()
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'pembeli'])
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('pesanan.index-staff', compact('pesanan'));
    }
}