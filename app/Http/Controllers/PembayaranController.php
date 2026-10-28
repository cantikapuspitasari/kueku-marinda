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
    // Mencatat pembayaran tunai untuk sebuah pesanan
    // Dipanggil oleh Admin (saat pickup) atau Kurir (saat delivery)
    public function store(Request $request, $idPesanan)
    {
        // Penerima pembayaran (staff di struk) diisi OTOMATIS oleh sistem, bukan diketik admin.
        $validated = $request->validate([
            'tempat_pembayaran' => 'required|in:TOKO,ALAMAT_PEMBELI',
        ]);

        $pesanan = Pesanan::findOrFail($idPesanan);

        // Ambil di toko -> staff yang sedang login (admin) yang menerima bayar.
        // Diantar kurir  -> kurir yang menangani pengiriman; kalau belum ada kurir, staff yang sedang login.
        $idPenerima = null;
        if ($pesanan->jenis_pengambilan === 'DELIVERY') {
            $idPenerima = Pengiriman::where('id_pesanan', $pesanan->id_pesanan)->value('id_kurir');
        }
        $idPenerima = $idPenerima ?: Auth::id();

        // Cegah pesanan yang sama dibayar dua kali (relasi 1-ke-1)
        if ($pesanan->pembayaran()->exists()) {
            return back()->withErrors(['pesanan' => 'Pesanan ini sudah tercatat pembayarannya']);
        }

        DB::transaction(function () use ($pesanan, $validated, $idPenerima) {
            Pembayaran::create([
                'id_pesanan'        => $pesanan->id_pesanan,
                'id_penerima'       => $idPenerima,
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