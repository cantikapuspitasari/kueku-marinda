<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    // Tugas antar milik kurir yang sedang login (pengiriman orang lain ditolak)
    private function milikSaya($idPesanan)
    {
        $pg = DB::table('pengiriman')
            ->where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::user()->id_user)
            ->first();
        abort_if(!$pg, 404);
        return $pg;
    }

    // Kurir mengambil pesanan dari toko (opsional, dipakai kalau tugas masih "MENUNGGU")
    public function ambilDariToko(Request $request, $idPesanan)
    {
        $this->milikSaya($idPesanan);

        DB::table('pengiriman')->where('id_pesanan', $idPesanan)->update([
            'status_pengiriman' => 'DIAMBIL_KURIR',
            'waktu_diambil'     => now()->toDateString(),
        ]);
        DB::table('pesanan')->where('id_pesanan', $idPesanan)->update(['status_pesanan' => 'DIPROSES']);

        return back()->with('success', 'Pesanan ditandai sudah diambil dari toko.');
    }

    // Langkah 2: Antar Pesanan -> status "Di Perjalanan"
    public function mulaiAntar($idPesanan)
    {
        $pg = $this->milikSaya($idPesanan);
        if ($pg->status_pengiriman === 'TERKIRIM') {
            return back()->with('error', 'Pesanan ini sudah selesai.');
        }

        DB::table('pengiriman')->where('id_pesanan', $idPesanan)->update([
            'status_pengiriman' => 'DIANTAR',
            'waktu_diambil'     => $pg->waktu_diambil ?: now()->toDateString(),
        ]);

        return back()->with('success', 'Status diperbarui: Di Perjalanan.');
    }

    // Langkah 3: Serahkan pesanan & terima pembayaran cash -> "Selesai"
    public function tandaiTerkirim(Request $request, $idPesanan)
    {
        $pg = $this->milikSaya($idPesanan);
        if ($pg->status_pengiriman !== 'DIANTAR') {
            return back()->with('error', 'Mulai antar pesanan terlebih dahulu (Di Perjalanan).');
        }

        DB::transaction(function () use ($idPesanan) {
            DB::table('pengiriman')->where('id_pesanan', $idPesanan)->update([
                'status_pengiriman' => 'TERKIRIM',
                'waktu_terkirim'    => now()->toDateString(),
            ]);
            DB::table('pesanan')->where('id_pesanan', $idPesanan)->update(['status_pesanan' => 'SELESAI']);
        });

        return back()->with('success', 'Pesanan selesai. Terima kasih!');
    }
}
