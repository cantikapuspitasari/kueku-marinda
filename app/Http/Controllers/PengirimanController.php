<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    // Memastikan tugas pengiriman adalah milik kurir yang login
    private function milikSaya($idPesanan)
    {
        $pengiriman = Pengiriman::where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::guard('web')->id())
            ->first();

        abort_if(!$pengiriman, 404);

        return $pengiriman;
    }

    // Daftar tugas kurir
    public function index()
    {
        $idKurir = Auth::guard('web')->id();

        // Pesanan delivery yang belum ditugaskan kepada kurir
        $siapDiambil = Pesanan::with(['pembeli', 'alamat'])
            ->where('jenis_pengambilan', 'DELIVERY')
            ->where('status_pesanan', 'DIPROSES')
            ->whereDoesntHave('pengiriman')
            ->orderBy('tanggal_ambil')
            ->get();

        // Tugas milik kurir yang sedang login
        $tugasSaya = Pengiriman::with(['pesanan.pembeli', 'pesanan.alamat'])
            ->where('id_user', $idKurir)
            ->whereIn('status_pengiriman', [
                'MENUNGGU',
                'DIAMBIL_KURIR',
                'DIANTAR'
            ])
            ->get();

        return view('pengiriman.index', compact('siapDiambil', 'tugasSaya'));
    }

    // Kurir mengambil pesanan dari toko
    public function ambilDariToko($idPesanan)
    {
        $idKurir = Auth::guard('web')->id();

        try {
            DB::transaction(function () use ($idPesanan, $idKurir) {

                $pesanan = Pesanan::lockForUpdate()->findOrFail($idPesanan);

                if ($pesanan->jenis_pengambilan !== 'DELIVERY') {
                    throw new \DomainException(
                        'Pesanan ini bukan pesanan delivery'
                    );
                }

                if ($pesanan->status_pesanan !== 'DIPROSES') {
                    throw new \DomainException(
                        "Pesanan berstatus {$pesanan->status_pesanan} belum bisa diambil"
                    );
                }

                $pengiriman = $pesanan->pengiriman;

                if ($pengiriman && (int) $pengiriman->id_user !== (int) $idKurir) {
                    throw new \DomainException(
                        'Pesanan ini sudah ditugaskan ke kurir lain'
                    );
                }

                if ($pengiriman && $pengiriman->status_pengiriman !== 'MENUNGGU') {
                    throw new \DomainException(
                        'Pesanan ini sudah ditandai diambil'
                    );
                }

                Pengiriman::updateOrCreate(
                    ['id_pesanan' => $pesanan->id_pesanan],
                    [
                        'id_user'           => $idKurir,
                        'status_pengiriman' => 'DIAMBIL_KURIR',
                        'waktu_diambil'     => now()->toDateString(),
                    ]
                );
            });

        } catch (\DomainException $e) {
            return back()->withErrors([
                'pengiriman' => $e->getMessage()
            ]);
        }

        return back()->with(
            'success',
            'Pesanan ditandai sudah diambil dari toko'
        );
    }

    // Kurir memberi tahu admin bahwa siap berangkat
    public function mulaiAntar($idPesanan)
    {
        $this->milikSaya($idPesanan);

        return back()->with(
            'error',
            'Status Di Perjalanan ditandai oleh admin. Kabari admin bahwa Anda siap berangkat.'
        );
    }

    // Kurir menandai pesanan sudah sampai ke pembeli
    // Pembayaran harus sudah dicatat terlebih dahulu
    public function tandaiTerkirim($idPesanan)
    {
        $idKurir = Auth::guard('web')->id();

        try {
            DB::transaction(function () use ($idPesanan, $idKurir) {

                $pesanan = Pesanan::lockForUpdate()->findOrFail($idPesanan);

                $pengiriman = Pengiriman::where('id_pesanan', $pesanan->id_pesanan)
                    ->where('id_user', $idKurir)
                    ->first();

                if (!$pengiriman) {
                    throw new \DomainException(
                        'Pengiriman ini bukan tugas Anda'
                    );
                }

                if (!in_array($pengiriman->status_pengiriman, [
                    'DIAMBIL_KURIR',
                    'DIANTAR'
                ])) {
                    throw new \DomainException(
                        'Pesanan belum diambil dari toko atau sudah terkirim'
                    );
                }

                if (!$pesanan->pembayaran()->exists()) {
                    throw new \DomainException(
                        'Catat pembayaran dulu sebelum menandai terkirim'
                    );
                }

                $pengiriman->update([
                    'status_pengiriman' => 'TERKIRIM',
                    'waktu_terkirim'    => now()->toDateString(),
                ]);

                $pesanan->update([
                    'status_pesanan' => 'SELESAI'
                ]);
            });

        } catch (\DomainException $e) {
            return back()->withErrors([
                'pengiriman' => $e->getMessage()
            ]);
        }

        return back()->with(
            'success',
            'Pesanan berhasil ditandai terkirim'
        );
    }
}
