<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerMonitoringController extends Controller
{
    // Owner hanya MEMANTAU (baca saja). Mengubah status tetap tugas Admin.
    public function index(Request $request)
    {
        $status = $request->query('status');
        $valid  = ['PENDING', 'DIKONFIRMASI', 'DIPROSES', 'SELESAI', 'DIBATALKAN'];

        $hariIni = Pesanan::whereDate('tanggal_pesan', now()->toDateString());
        $stat = [
            'total'   => Pesanan::count(),
            'hari_ini' => (clone $hariIni)->count(),
            'proses'  => Pesanan::whereIn('status_pesanan', ['PENDING', 'DIKONFIRMASI', 'DIPROSES'])->count(),
            'selesai_hari_ini' => (clone $hariIni)->where('status_pesanan', 'SELESAI')->count(),
        ];
        $stat['persen_selesai'] = $stat['hari_ini'] > 0 ? round($stat['selesai_hari_ini'] / $stat['hari_ini'] * 100, 1) : 0;

        $pesanan = Pesanan::with(['pembeli', 'detailPesanan.produk'])
            ->when(in_array($status, $valid, true), fn ($q) => $q->where('status_pesanan', $status))
            ->orderBy('tanggal_pesan', 'desc')->orderBy('id_pesanan', 'desc')
            ->paginate(10)
            ->withQueryString();

        $alamat = DB::table('alamat')
            ->whereIn('id_alamat', $pesanan->pluck('id_alamat')->filter()->unique())
            ->get()->keyBy('id_alamat');

        $hitung = Pesanan::select('status_pesanan', DB::raw('count(*) as n'))->groupBy('status_pesanan')->pluck('n', 'status_pesanan');

        return view('owner.monitoring', compact('stat', 'pesanan', 'alamat', 'status', 'hitung'));
    }
}
