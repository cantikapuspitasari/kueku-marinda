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

    // Langkah 2: "Berangkat mengantar" kini HANYA ditandai oleh admin (Admin > Pesanan > Kurir berangkat mengantar).
    // Kurir cukup mengabari admin; route ini dibiarkan supaya link lama tidak error.
    public function mulaiAntar($idPesanan)
    {
        $this->milikSaya($idPesanan);
        return back()->with('error', 'Status "Di Perjalanan" ditandai oleh admin. Kabari admin bahwa Anda siap berangkat.');
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
