<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // Mencatat pembayaran tunai untuk sebuah pesanan
    // Dipanggil oleh Admin (saat pickup) atau Kurir (saat delivery)
    public function store(Request $request, $idPesanan)
    {
        $validated = $request->validate([
            'id_penerima'       => 'required|exists:users,id_user',
            'tempat_pembayaran' => 'required|in:TOKO,ALAMAT_PEMBELI',
        ]);

        $pesanan = Pesanan::findOrFail($idPesanan);

        // Cegah pesanan yang sama dibayar dua kali (relasi 1-ke-1)
        if ($pesanan->pembayaran()->exists()) {
            return back()->withErrors(['pesanan' => 'Pesanan ini sudah tercatat pembayarannya']);
        }

        DB::transaction(function () use ($pesanan, $validated) {
            Pembayaran::create([
                'id_pesanan'        => $pesanan->id_pesanan,
                'id_penerima'       => $validated['id_penerima'],
                'jumlah_bayar'      => $pesanan->total_harga,
                'tempat_pembayaran' => $validated['tempat_pembayaran'],
            ]);

            // Kalau dibayar di toko (pickup), pesanan langsung selesai.
            // Kalau delivery, status pesanan diurus lewat PengirimanController (nunggu terkirim)
            if ($validated['tempat_pembayaran'] === 'TOKO') {
                $pesanan->update(['status_pesanan' => 'SELESAI']);
            }
        });

        return back()->with('success', 'Pembayaran berhasil dicatat');
    }

    // Detail pembayaran 1 pesanan
    public function show($idPesanan)
    {
        $pembayaran = Pembayaran::with(['pesanan', 'penerima'])
            ->where('id_pesanan', $idPesanan)
            ->firstOrFail();

        return view('pembayaran.show', compact('pembayaran'));
    }
}