<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrukController extends Controller
{
    private const PERAN = ['ADMIN' => 'Admin', 'KURIR' => 'Kurir', 'OWNER' => 'Owner'];

    /**
     * Struk pembelian (lebar 8 cm, cocok untuk printer thermal).
     * Bisa dibuka admin / owner / kurir, atau pembeli untuk pesanannya sendiri.
     */
    public function show($idPesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'pembeli', 'pembayaran', 'pengiriman'])
            ->findOrFail($idPesanan);

        $user = Auth::user();

        // Pembeli hanya boleh melihat struk pesanannya sendiri
        if ($user->role === 'PEMBELI') {
            $idPembeli = DB::table('pembeli')->where('email', $user->email)->value('id_pembeli');
            abort_unless($idPembeli && (int) $idPembeli === (int) $pesanan->id_pembeli, 403, 'Struk ini bukan milik Anda.');
        }

        abort_if($pesanan->status_pesanan === 'DIBATALKAN', 403, 'Pesanan yang dibatalkan tidak memiliki struk.');

        $delivery = $pesanan->jenis_pengambilan === 'DELIVERY';
        $subtotal = (int) round($pesanan->detailPesanan->sum('subtotal'));
        $total = (int) round($pesanan->total_harga);
        $ongkir = max(0, $total - $subtotal);

        // ----- STAFF -----
        // Ambil di toko  -> admin yang menerima pembayaran
        // Diantar kurir  -> kurir yang menerima pembayaran
        // Dicari dari data pembayaran; kalau belum ada, dari kurir pengiriman; kalau belum ada juga, hanya peran.
        $pembayaran = $pesanan->pembayaran;
        $pengiriman = $pesanan->pengiriman;

        $idStaff = $pembayaran->id_penerima ?? $pembayaran->id_user ?? null;
        if (! $idStaff && $delivery) {
            $idStaff = $pengiriman->id_kurir ?? $pengiriman->id_user ?? null;
        }
        $staff = $idStaff ? User::find($idStaff) : null;

        $staffNama = $staff->nama ?? null;
        $staffPeran = $staff ? (self::PERAN[$staff->role] ?? ucfirst(strtolower($staff->role))) : ($delivery ? 'Kurir' : 'Admin');

        return view('struk.show', [
            'pesanan'    => $pesanan,
            'delivery'   => $delivery,
            'subtotal'   => $subtotal,
            'ongkir'     => $ongkir,
            'total'      => $total,
            'pembayaran' => $pembayaran,
            'staffNama'  => $staffNama,
            'staffPeran' => $staffPeran,
            'pelanggan'  => $pesanan->pembeli->nama ?? '-',
            'waktu'      => $pembayaran->tanggal_bayar ?? $pesanan->tanggal_pesan,
        ]);
    }
}
