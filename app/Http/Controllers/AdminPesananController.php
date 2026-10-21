<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;

class AdminPesananController extends Controller
{
    // Halaman "Pesanan Masuk": semua pesanan pelanggan dari database
    public function index()
    {
        $pesanan = Pesanan::with(['pembeli', 'detailPesanan.produk', 'pembayaran'])
            ->orderBy('tanggal_pesan', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        // Alamat diambil langsung dari tabel alamat (tidak bergantung pada relasi model)
        $alamat = DB::table('alamat')
            ->whereIn('id_alamat', $pesanan->pluck('id_alamat')->filter()->unique())
            ->get()
            ->keyBy('id_alamat');

        return view('admin.pesanan', compact('pesanan', 'alamat'));
    }
}
