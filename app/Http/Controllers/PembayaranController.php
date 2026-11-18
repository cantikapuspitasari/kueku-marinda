<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // Mencatat pembayaran TUNAI untuk sebuah pesanan
    // (QRIS dan Transfer Bank tidak dicatat sebagai tunai: lihat QrisController dan TransferController)
    // - PICKUP   : dicatat Admin di toko
    // - DELIVERY : dicatat Kurir yang mengantar pesanan tersebut
    // Uang diterima divalidasi di sini, kembalian SELALU dihitung ulang di server
    // (nilai kembalian dari browser tidak pernah dipercaya).
    public function store(Request $request, $idPesanan)
    {
        $validated = $request->validate([
            'tempat_pembayaran' => 'required|in:TOKO,ALAMAT_PEMBELI',
            'uang_diterima'     => 'required|integer|min:0|max:99999999',
        ], [
            'uang_diterima.required' => 'Nominal uang diterima wajib diisi.',
            'uang_diterima.integer'  => 'Nominal uang diterima harus berupa angka bulat (rupiah).',
            'uang_diterima.min'      => 'Nominal uang diterima tidak boleh negatif.',
            'uang_diterima.max'      => 'Nominal uang diterima terlalu besar.',
        ]);

        $staf = Auth::guard('web')->user();
        $kembalian = 0;

        try {
            DB::transaction(function () use ($idPesanan, $validated, $staf, &$kembalian) {

                // Kunci pesanan supaya dua permintaan bersamaan tidak sama-sama lolos
                $pesanan = Pesanan::lockForUpdate()->findOrFail($idPesanan);

                if (in_array($pesanan->status_pesanan, ['PENDING', 'DIBATALKAN'])) {
                    throw new \DomainException(
                        "Pesanan berstatus {$pesanan->status_pesanan} belum bisa dibayar"
                    );
                }

                // QRIS dan Transfer Bank dibayar pembeli sendiri, bukan dicatat staf sebagai tunai
                if ($pesanan->isQris()) {
                    throw new \DomainException(
                        'Pesanan ini dibayar lewat QRIS. Pembayaran dikonfirmasi pembeli melalui scan QR Code.'
                    );
                }

                if ($pesanan->isTransfer()) {
                    throw new \DomainException(
                        'Pesanan ini dibayar lewat Transfer Bank. Pembayaran menjadi LUNAS setelah bukti transfer diverifikasi di menu Pembayaran Transfer.'
                    );
                }

                // Admin hanya mencatat pembayaran pickup (di toko)
                if ($staf->role === 'ADMIN' && $pesanan->jenis_pengambilan !== 'PICKUP') {
                    throw new \DomainException(
                        'Pembayaran pesanan delivery hanya dapat dicatat oleh kurir yang mengantar'
                    );
                }

                // Kurir hanya mencatat pembayaran delivery yang ditugaskan kepadanya
                if ($staf->role === 'KURIR') {
                    if ($pesanan->jenis_pengambilan !== 'DELIVERY') {
                        throw new \DomainException(
                            'Kurir hanya bisa mencatat pembayaran pesanan delivery'
                        );
                    }

                    $tugasSaya = Pengiriman::where('id_pesanan', $pesanan->id_pesanan)
                        ->where('id_user', $staf->id_user)
                        ->exists();

                    if (! $tugasSaya) {
                        throw new \DomainException(
                            'Pesanan ini bukan tugas pengantaran Anda'
                        );
                    }
                }

                // PICKUP dibayar di toko
                // DELIVERY dibayar di alamat pembeli
                $tempatSeharusnya = $pesanan->jenis_pengambilan === 'PICKUP'
                    ? 'TOKO'
                    : 'ALAMAT_PEMBELI';

                if ($validated['tempat_pembayaran'] !== $tempatSeharusnya) {
                    throw new \DomainException(
                        "Pesanan {$pesanan->jenis_pengambilan} harus dibayar di {$tempatSeharusnya}"
                    );
                }

                // Cegah pembayaran dua kali
                if ($pesanan->sudahDibayar()) {
                    throw new \DomainException(
                        'Pesanan ini sudah tercatat pembayarannya'
                    );
                }

                // Hitung ulang di server: kembalian = uang diterima - total pembayaran
                $total  = (int) round($pesanan->total_harga);
                $diterima = (int) $validated['uang_diterima'];

                if ($diterima < $total) {
                    throw new \DomainException(
                        'Uang diterima masih kurang Rp' . number_format($total - $diterima, 0, ',', '.')
                        . ' dari total pembayaran Rp' . number_format($total, 0, ',', '.')
                    );
                }

                $kembalian = $diterima - $total;

                Pembayaran::create([
                    'id_pesanan'        => $pesanan->id_pesanan,
                    'id_user'           => $staf->id_user,
                    'jumlah_bayar'      => $total,
                    'uang_diterima'     => $diterima,
                    'kembalian'         => $kembalian,
                    'tempat_pembayaran' => $validated['tempat_pembayaran'],
                ]);

                // Tandai LUNAS pada pesanan (dipakai admin untuk melihat status pembayaran)
                $pesanan->update([
                    'status_pembayaran' => 'LUNAS',
                    'waktu_bayar'       => now(),
                ]);

                // Pickup dibayar di toko, langsung selesai
                // Delivery, status diurus PengirimanController
                if ($validated['tempat_pembayaran'] === 'TOKO') {
                    $pesanan->update([
                        'status_pesanan' => 'SELESAI'
                    ]);
                }
            });

        } catch (\DomainException $e) {

            return back()->withInput()->withErrors([
                'pesanan' => $e->getMessage()
            ]);

        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {

            // Jaring pengaman terakhir: kolom id_pesanan pada tabel pembayaran unik
            return back()->withErrors([
                'pesanan' => 'Pesanan ini sudah tercatat pembayarannya'
            ]);
        }

        return back()->with(
            'success',
            $kembalian > 0
                ? 'Pembayaran berhasil dicatat. Kembalian: Rp' . number_format($kembalian, 0, ',', '.')
                : 'Pembayaran berhasil dicatat. Uang pas, tidak ada kembalian.'
        );
    }

    // Detail pembayaran 1 pesanan
    // Hanya pembeli yang memiliki pesanan tersebut yang dapat melihatnya
    public function show($idPesanan)
    {
        $idPembeli = Auth::guard('pembeli')->id();

        $pembayaran = Pembayaran::with([
            'pesanan',
            'penerima'
        ])
            ->where('id_pesanan', $idPesanan)
            ->whereHas('pesanan', function ($q) use ($idPembeli) {
                $q->where('id_pembeli', $idPembeli);
            })
            ->firstOrFail();

        return view('pembayaran.show', compact('pembayaran'));
    }
}
