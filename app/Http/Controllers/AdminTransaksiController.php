<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

// Halaman "Riwayat Transaksi" Admin: filter metode pembayaran + status pesanan,
// serta total nominal seluruh transaksi yang cocok dengan filter (bukan hanya halaman aktif).
class AdminTransaksiController extends Controller
{
    // Status pesanan yang dipakai aplikasi (kolom pesanan.status_pesanan)
    private const STATUS = [
        'PENDING'      => 'Menunggu',
        'DIKONFIRMASI' => 'Dikonfirmasi',
        'DIPROSES'     => 'Diproses',
        'SELESAI'      => 'Selesai',
        'DIBATALKAN'   => 'Dibatalkan',
    ];

    public function index(Request $request)
    {
        // Nilai di luar daftar diabaikan (dianggap "semua"), jadi aman dari input liar
        $metode = (string) $request->query('metode', '');
        $status = (string) $request->query('status', '');
        $metode = array_key_exists($metode, Pesanan::METODE_PEMBAYARAN) ? $metode : '';
        $status = array_key_exists($status, self::STATUS) ? $status : '';

        // Query dasar + filter dinamis (Query Builder / Eloquent: nilai di-bind, bukan disambung ke SQL)
        $query = Pesanan::query()
            ->when($metode !== '', fn ($q) => $q->where('metode_pembayaran', $metode))
            ->when($status !== '', fn ($q) => $q->where('status_pesanan', $status));

        // Total dihitung di database atas SEMUA data yang cocok; kosong = 0
        $jumlah       = (clone $query)->count();
        $totalNominal = (float) ((clone $query)->sum('total_harga') ?? 0);

        $pesanan = $query
            ->with('pembeli')
            ->orderBy('tanggal_pesan', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.transaksi.index', [
            'pesanan'      => $pesanan,
            'metode'       => $metode,
            'status'       => $status,
            'opsiMetode'   => Pesanan::METODE_PEMBAYARAN,
            'opsiStatus'   => self::STATUS,
            'jumlah'       => $jumlah,
            'totalNominal' => $totalNominal,
        ]);
    }
}
