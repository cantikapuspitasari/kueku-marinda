<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    // Mencatat pembayaran tunai untuk sebuah pesanan
    // Dipanggil oleh Admin atau Kurir
    public function store(Request $request, $idPesanan)
    {
        $validated = $request->validate([
            'tempat_pembayaran' => 'required|in:TOKO,ALAMAT_PEMBELI',
        ]);

        $staf = Auth::guard('web')->user();

        try {
            DB::transaction(function () use ($idPesanan, $validated, $staf) {

                // Kunci pesanan supaya dua permintaan bersamaan tidak sama-sama lolos
                $pesanan = Pesanan::lockForUpdate()->findOrFail($idPesanan);

                if (in_array($pesanan->status_pesanan, ['PENDING', 'DIBATALKAN'])) {
                    throw new \DomainException(
                        "Pesanan berstatus {$pesanan->status_pesanan} belum bisa dibayar"
                    );
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

                // Kurir hanya boleh mencatat pembayaran di alamat pembeli
                if (
                    $staf->role === 'KURIR' &&
                    $validated['tempat_pembayaran'] !== 'ALAMAT_PEMBELI'
                ) {
                    throw new \DomainException(
                        'Kurir hanya bisa mencatat pembayaran di alamat pembeli'
                    );
                }

                // Cegah pembayaran dua kali
                if ($pesanan->pembayaran()->exists()) {
                    throw new \DomainException(
                        'Pesanan ini sudah tercatat pembayarannya'
                    );
                }

                Pembayaran::create([
                    'id_pesanan'        => $pesanan->id_pesanan,
                    'id_user'           => $staf->id_user,
                    'jumlah_bayar'      => $pesanan->total_harga,
                    'tempat_pembayaran' => $validated['tempat_pembayaran'],
                ]);

                // Pickup dibayar di toko → langsung selesai
                // Delivery → status diurus PengirimanController
                if ($validated['tempat_pembayaran'] === 'TOKO') {
                    $pesanan->update([
                        'status_pesanan' => 'SELESAI'
                    ]);
                }
            });

        } catch (\DomainException $e) {

            return back()->withErrors([
                'pesanan' => $e->getMessage()
            ]);
        }

        return back()->with(
            'success',
            'Pembayaran berhasil dicatat'
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