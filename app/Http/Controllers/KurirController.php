<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Panel Kurir: Dashboard + Pesanan Diantar (data nyata dari tabel pengiriman)
class KurirController extends Controller
{
    private function dasar()
    {
        return DB::table('pengiriman as g')
        ->join('pesanan as p', 'p.id_pesanan', '=', 'g.id_pesanan')
        ->where('g.id_user', Auth::user()->id_user)
        ->where('p.status_pesanan', '!=', 'DIBATALKAN');
    }

    public function dashboard()
    {
        $hariIni = now()->toDateString();

        $aktif   = $this->dasar()->whereIn('g.status_pengiriman', ['MENUNGGU', 'DIAMBIL_KURIR', 'DIANTAR'])->count();
        $diantar = $this->dasar()->where('g.status_pengiriman', 'DIANTAR')->count();
        $selesai = $this->dasar()->where('g.status_pengiriman', 'TERKIRIM')->whereDate('g.waktu_terkirim', $hariIni)->count();

        $stat = [
            'hari_ini' => $aktif + $selesai,   // tugas aktif + yang sudah selesai hari ini
            'diantar'  => $diantar,
            'selesai'  => $selesai,
        ];

        return view('kurir.dashboard', compact('stat'));
    }

    public function pesanan()
    {
        $hariIni = now()->toDateString();

        $tugas = $this->dasar()
            ->leftJoin('pembeli as b', 'b.id_pembeli', '=', 'p.id_pembeli')
            ->leftJoin('users as u', 'u.email', '=', 'b.email')
            ->leftJoin('alamat as a', 'a.id_alamat', '=', 'p.id_alamat')
            ->where(function ($q) use ($hariIni) {
                $q->whereIn('g.status_pengiriman', ['MENUNGGU', 'DIAMBIL_KURIR', 'DIANTAR'])
                  ->orWhere(function ($q2) use ($hariIni) {
                      $q2->where('g.status_pengiriman', 'TERKIRIM')->whereDate('g.waktu_terkirim', $hariIni);
                  });
            })
            ->orderByRaw("FIELD(g.status_pengiriman, 'DIANTAR', 'DIAMBIL_KURIR', 'MENUNGGU', 'TERKIRIM')")
            ->orderBy('g.id_pengiriman')
            ->get([
                'g.status_pengiriman', 'p.id_pesanan', 'p.kode_pesanan', 'p.total_harga', 'p.tanggal_ambil',
                'b.nama as pelanggan', DB::raw("COALESCE(NULLIF(b.no_telepon, ''), u.no_telepon) as telepon"), 'a.alamat_lengkap', 'a.kota',
            ]);

        $item = DB::table('detail_pesanan as d')
            ->join('produk as pr', 'pr.id_produk', '=', 'd.id_produk')
            ->whereIn('d.id_pesanan', $tugas->pluck('id_pesanan'))
            ->get(['d.id_pesanan', 'd.jumlah', 'pr.nama_produk'])
            ->groupBy('id_pesanan');

        return view('kurir.pesanan', compact('tugas', 'item'));
    }
}
