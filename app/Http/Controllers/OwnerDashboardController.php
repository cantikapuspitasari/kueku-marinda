<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();
        $kemarin = now()->subDay()->toDateString();

        // Omzet = semua pesanan yang tidak dibatalkan (sama dengan dashboard Admin)
        $omzet = fn ($tgl) => Pesanan::whereDate('tanggal_pesan', $tgl)->where('status_pesanan', '!=', 'DIBATALKAN')->sum('total_harga');
        $omzetHariIni = $omzet($hariIni);
        $omzetKemarin = $omzet($kemarin);
        $selisih = $omzetKemarin > 0 ? round((($omzetHariIni - $omzetKemarin) / $omzetKemarin) * 100) : null;

        $pesananHariIni = Pesanan::whereDate('tanggal_pesan', $hariIni);

        $stat = [
            'omzet'      => $omzetHariIni,
            'selisih'    => $selisih,
            'masuk'      => (clone $pesananHariIni)->count(),
            'selesai'    => (clone $pesananHariIni)->where('status_pesanan', 'SELESAI')->count(),
            'proses'     => (clone $pesananHariIni)->whereIn('status_pesanan', ['PENDING', 'DIKONFIRMASI', 'DIPROSES'])->count(),
            'admin'      => DB::table('users')->where('role', 'ADMIN')->where('status_akun', 'AKTIF')->count(),
            'kurir'      => DB::table('users')->where('role', 'KURIR')->where('status_akun', 'AKTIF')->count(),
        ];

        $pesananTerbaru = Pesanan::with(['pembeli', 'detailPesanan.produk'])
            ->orderBy('tanggal_pesan', 'desc')->orderBy('id_pesanan', 'desc')
            ->limit(5)->get();

        return view('owner.dashboard', compact('stat', 'pesananTerbaru'));
    }
}
