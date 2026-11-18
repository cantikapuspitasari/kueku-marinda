<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

        // Staf (guard web) atau pembeli (guard pembeli). Model Pembeli tidak punya kolom role,
        // jadi pembeli dikenali dari guard-nya, bukan dari $user->role.
        $akunStaf = Auth::guard('web')->user();

        if ($akunStaf) {
            abort_unless(
                in_array($akunStaf->role, array_keys(self::PERAN), true) && $akunStaf->status_akun !== 'NONAKTIF',
                403,
                'Anda tidak memiliki akses untuk halaman ini.'
            );
        } else {
            // Pembeli hanya boleh melihat struk pesanannya sendiri
            $pembeli = Auth::guard('pembeli')->user();
            abort_unless(
                $pembeli && (int) $pembeli->id_pembeli === (int) $pesanan->id_pembeli,
                403,
                'Struk ini bukan milik Anda.'
            );
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

        // ----- PEMBAYARAN TUNAI -----
        // Data lama (sebelum ada fitur kembalian) dianggap uang pas.
        $uangDiterima = $pembayaran ? (int) round($pembayaran->uang_diterima ?? $pembayaran->jumlah_bayar) : null;
        $kembalian    = $pembayaran ? (int) round($pembayaran->kembalian ?? 0) : null;

        // Input uang diterima di halaman struk hanya untuk ADMIN pada pesanan Ambil di Toko yang belum dibayar.
        // (Pembayaran delivery diinput kurir dari panel kurir.)
        // Metode pembayaran yang dipilih pembeli (TUNAI / QRIS)
        $metode = $pesanan->metode_pembayaran ?: 'TUNAI';
        $labelMetode = \App\Models\Pesanan::METODE_PEMBAYARAN[$metode] ?? $metode;

        // QRIS dan Transfer Bank dibayar pembeli sendiri. Status LUNAS tersimpan di pesanan (bukan tabel pembayaran).
        // Transfer yang masih BELUM_BAYAR / MENUNGGU_VERIFIKASI / DITOLAK TIDAK dianggap lunas.
        $nonTunaiLunas = $pesanan->isNonTunai() && $pesanan->status_pembayaran === 'LUNAS';
        $labelBayar = $pesanan->isTransfer() && $pesanan->bank_tujuan
            ? 'Transfer Bank ' . $pesanan->bank_tujuan
            : $labelMetode;
        $statusBayarTeks = $pesanan->isNonTunai()
            ? strtoupper(\App\Models\Pesanan::STATUS_PEMBAYARAN[$pesanan->statusBayar()] ?? 'BELUM BAYAR')
            : 'BELUM DIBAYAR';

        // Admin hanya menginput uang untuk pesanan TUNAI. QRIS dan Transfer tidak punya input uang.
        $bisaInputBayar = $akunStaf
            && $akunStaf->role === 'ADMIN'
            && ! $delivery
            && ! $pesanan->isNonTunai()
            && ! $pembayaran
            && in_array($pesanan->status_pesanan, ['DIKONFIRMASI', 'DIPROSES', 'SELESAI'], true);

        // Tombol "Kembali": tautan tetap sesuai peran. history.back() tidak bisa dipakai karena struk
        // sering dibuka di tab baru dan setelah simpan pembayaran halaman dimuat ulang.
        $urlKembali = $akunStaf
            ? match ($akunStaf->role) {
                'ADMIN' => route('admin.pesanan'),
                'KURIR' => route('kurir.pesanan'),
                default => route('owner.monitoring'),
            }
            : route('pesanan.saya');

        return view('struk.show', [
            'pesanan'    => $pesanan,
            'delivery'   => $delivery,
            'subtotal'   => $subtotal,
            'ongkir'     => $ongkir,
            'total'      => $total,
            'pembayaran' => $pembayaran,
            'uangDiterima'   => $uangDiterima,
            'kembalian'      => $kembalian,
            'bisaInputBayar' => $bisaInputBayar,
            'metode'         => $metode,
            'labelMetode'    => $labelMetode,
            'nonTunaiLunas'  => $nonTunaiLunas,
            'labelBayar'     => $labelBayar,
            'statusBayarTeks' => $statusBayarTeks,
            'urlKembali'     => $urlKembali,
            'staffNama'  => $staffNama,
            'staffPeran' => $staffPeran,
            'pelanggan'  => $pesanan->pembeli->nama ?? '-',
            'waktu'      => $pembayaran->tanggal_bayar ?? $pesanan->waktu_bayar ?? $pesanan->tanggal_pesan,
        ]);
    }
}
