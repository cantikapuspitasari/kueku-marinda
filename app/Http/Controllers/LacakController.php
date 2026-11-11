<?php

namespace App\Http\Controllers;

use App\Support\TahapPesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Dipakai halaman "Detail Pesanan" pelanggan untuk membaca status asli dari database
class LacakController extends Controller
{
    public function status(string $kode)
    {
        $kode = ltrim($kode, '#');
        $user = Auth::user();

        $p = DB::table('pesanan')->where('kode_pesanan', $kode)->first();
        if (!$p) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Pelanggan hanya boleh melihat pesanannya sendiri (dicocokkan lewat email akun)
        if ($user->role === 'PEMBELI') {
            if (!in_array($p->id_pembeli, \App\Support\PembeliAkun::ids($user))) {
                return response()->json(['message' => 'Bukan pesanan Anda'], 403);
            }
        }

        $pg = DB::table('pengiriman')->where('id_pesanan', $p->id_pesanan)->first();
        $hasil = TahapPesanan::hitung($p->status_pesanan, $p->jenis_pengambilan, $pg->status_pengiriman ?? null);

        $kurir = null;
        if ($pg && $pg->id_user && in_array($pg->status_pengiriman, ['DIAMBIL_KURIR', 'DIANTAR', 'TERKIRIM'], true)) {
            $kurir = DB::table('users')->where('id_user', $pg->id_user)->first(['nama', 'no_telepon']);
        }

        $cart = DB::table('detail_pesanan')->where('id_pesanan', $p->id_pesanan)
            ->pluck('jumlah', 'id_produk');

        // Ongkir = total pesanan - jumlah harga item (dihitung saat pesanan dibuat)
        $subtotal = (float) DB::table('detail_pesanan')->where('id_pesanan', $p->id_pesanan)->sum('subtotal');
        $ongkir = max(0, (int) round($p->total_harga - $subtotal));

        return response()->json([
            'cart'   => $cart,
            'ongkir' => $ongkir,
            'kode'   => $p->kode_pesanan,
            'mode'   => $p->jenis_pengambilan === 'PICKUP' ? 'toko' : 'kurir',
            'state'  => $hasil['state'],   // ok | menunggu | batal
            'tahap'  => $hasil['tahap'],   // 0..4 (atau -1)
            'kurir'  => $kurir,
        ]);
    }
}
