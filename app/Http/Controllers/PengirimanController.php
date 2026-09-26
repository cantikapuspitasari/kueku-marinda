<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    // Daftar pesanan yang perlu diantar hari ini (dipakai Kurir)
    public function index()
    {
        $pengiriman = Pengiriman::with('pesanan.pembeli', 'pesanan.alamat')
            ->whereIn('status_pengiriman', ['MENUNGGU', 'DIAMBIL_KURIR', 'DIANTAR'])
            ->get();

        return view('pengiriman.index', compact('pengiriman'));
    }

    // Kurir menandai sudah ambil pesanan dari toko
    public function ambilDariToko(Request $request, $idPesanan)
    {
        $validated = $request->validate([
            'id_kurir' => 'required|exists:users,id_user',
        ]);

        $pesanan = Pesanan::findOrFail($idPesanan);

        // Bikin record pengiriman kalau belum ada, atau ambil yang sudah ada
        $pengiriman = Pengiriman::firstOrCreate(
            ['id_pesanan' => $pesanan->id_pesanan],
            ['id_kurir' => $validated['id_kurir']]
        );

        $pengiriman->update([
            'status_pengiriman' => 'DIAMBIL_KURIR',
            'waktu_diambil'     => now()->toDateString(),
        ]);

        $pesanan->update(['status_pesanan' => 'DIPROSES']);

        return back()->with('success', 'Pesanan ditandai sudah diambil dari toko');
    }

    // Kurir menandai pesanan sudah sampai & pembeli sudah bayar
    // Ini memicu pencatatan pembayaran DAN status terkirim sekaligus (sesuai alur COD)
    public function tandaiTerkirim(Request $request, $idPesanan)
    {
        $pengiriman = Pengiriman::where('id_pesanan', $idPesanan)->firstOrFail();

        $pengiriman->update([
            'status_pengiriman' => 'TERKIRIM',
            'waktu_terkirim'    => now()->toDateString(),
        ]);

        $pengiriman->pesanan->update(['status_pesanan' => 'SELESAI']);

        return back()->with('success', 'Pesanan berhasil ditandai terkirim. Jangan lupa catat pembayarannya.');
    }
}