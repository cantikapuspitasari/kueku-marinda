<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Support\TahapPesanan;
use Illuminate\Http\Request;
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

        // Data pengiriman + daftar kurir aktif (untuk tahap "Serahkan ke kurir")
        $pengiriman = DB::table('pengiriman')
            ->whereIn('id_pesanan', $pesanan->pluck('id_pesanan'))
            ->get()
            ->keyBy('id_pesanan');

        $kurir = DB::table('users')
            ->where('role', 'KURIR')
            ->where('status_akun', 'AKTIF')
            ->orderBy('nama')
            ->get(['id_user', 'nama']);

        return view('admin.pesanan', compact('pesanan', 'alamat', 'pengiriman', 'kurir'));
    }

    // Memajukan pesanan satu tahap (dapur -> kurir -> diantar -> sampai)
    public function maju(Request $request, $id)
    {
        $p = DB::table('pesanan')->where('id_pesanan', $id)->first();
        abort_if(!$p, 404);

        $pg   = DB::table('pengiriman')->where('id_pesanan', $id)->first();
        $next = TahapPesanan::berikutnya($p->status_pesanan, $p->jenis_pengambilan, $pg->status_pengiriman ?? null);

        if (!$next) {
            return back()->with('error', 'Pesanan ini tidak punya tahap berikutnya.');
        }

        $today = now()->toDateString();

        DB::transaction(function () use ($request, $next, $id, $pg, $today) {
            switch ($next['aksi']) {
                case 'kemas':
                    DB::table('pesanan')->where('id_pesanan', $id)->update(['status_pesanan' => 'DIPROSES']);
                    break;

                case 'serahkan':
                    $data = $request->validate([
                        'id_kurir' => 'required|exists:users,id_user',
                    ], ['id_kurir.required' => 'Pilih kurir terlebih dahulu.']);

                    $isiKurir = [
                        'id_user'           => $data['id_kurir'],
                        'status_pengiriman' => 'DIAMBIL_KURIR',
                        'waktu_diambil'     => $today,
                    ];
                    if ($pg) {
                        DB::table('pengiriman')->where('id_pesanan', $id)->update($isiKurir);
                    } else {
                        DB::table('pengiriman')->insert($isiKurir + ['id_pesanan' => $id]);
                    }
                    break;

                case 'antar':
                    DB::table('pengiriman')->where('id_pesanan', $id)->update(['status_pengiriman' => 'DIANTAR']);
                    break;

                case 'terkirim':
                    DB::table('pengiriman')->where('id_pesanan', $id)->update([
                        'status_pengiriman' => 'TERKIRIM',
                        'waktu_terkirim'    => $today,
                    ]);
                    DB::table('pesanan')->where('id_pesanan', $id)->update(['status_pesanan' => 'SELESAI']);
                    break;

                case 'selesai_toko':
                    DB::table('pesanan')->where('id_pesanan', $id)->update(['status_pesanan' => 'SELESAI']);
                    break;
            }
        });

        return back()->with('success', 'Tahap pesanan diperbarui: ' . $next['label']);
    }
}
