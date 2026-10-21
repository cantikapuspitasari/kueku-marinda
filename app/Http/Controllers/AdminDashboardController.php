<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();
        $kemarin = now()->subDay()->toDateString();

        // Pesanan batal tidak dihitung sebagai penjualan
        $pesananHariIni = Pesanan::whereDate('tanggal_pesan', $hariIni)->where('status_pesanan', '!=', 'DIBATALKAN');
        $jumlahHariIni  = (clone $pesananHariIni)->count();
        $jumlahKemarin  = Pesanan::whereDate('tanggal_pesan', $kemarin)->where('status_pesanan', '!=', 'DIBATALKAN')->count();

        if ($jumlahKemarin > 0) {
            $selisih = round((($jumlahHariIni - $jumlahKemarin) / $jumlahKemarin) * 100);
        } else {
            $selisih = null; // belum ada pembanding
        }

        $stat = [
            'hari_ini'     => $jumlahHariIni,
            'selisih'      => $selisih,
            'menunggu'     => Pesanan::where('status_pesanan', 'PENDING')->count(),
            'pendapatan'   => (clone $pesananHariIni)->sum('total_harga'),
            'produk_aktif' => Produk::where('status_produk', 'TERSEDIA')->count(),
            'stok_habis'   => Produk::where('status_produk', 'STOK_HABIS')->count(),
        ];

        $pesananTerbaru = Pesanan::with(['pembeli', 'detailPesanan.produk'])
            ->orderBy('tanggal_pesan', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->limit(5)
            ->get();

        $stokMenipis = Produk::where('status_produk', '!=', 'NONAKTIF')
            ->where('stok', '<=', 5)
            ->orderBy('stok')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stat', 'pesananTerbaru', 'stokMenipis'));
    }
}
