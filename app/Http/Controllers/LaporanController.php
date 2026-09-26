<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Laporan penjualan per periode (bulan & tahun)
    public function index(Request $request)
    {
        // Default: bulan & tahun saat ini kalau tidak dipilih
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $pesananSelesai = Pesanan::with(['detailPesanan.produk', 'pembeli'])
            ->where('status_pesanan', 'SELESAI')
            ->whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->get();

        $totalPesanan = $pesananSelesai->count();
        $totalPemasukan = $pesananSelesai->sum('total_harga');

        // Breakdown tambahan: produk apa yang paling laku di periode ini
        $produkTerlaris = $pesananSelesai
            ->flatMap(fn ($pesanan) => $pesanan->detailPesanan)
            ->groupBy('id_produk')
            ->map(function ($items) {
                return [
                    'nama_produk' => $items->first()->produk->nama_produk,
                    'total_terjual' => $items->sum('jumlah'),
                ];
            })
            ->sortByDesc('total_terjual')
            ->values();

        return view('laporan.index', compact(
            'pesananSelesai',
            'totalPesanan',
            'totalPemasukan',
            'produkTerlaris',
            'bulan',
            'tahun'
        ));
    }
}