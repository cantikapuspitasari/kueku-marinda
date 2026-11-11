<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;
=======
use App\Models\Pengiriman;
use App\Models\Pesanan;
>>>>>>> origin/feature/backend
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
<<<<<<< HEAD
    // Tugas antar milik kurir yang sedang login (pengiriman orang lain ditolak)
    private function milikSaya($idPesanan)
    {
        $pg = DB::table('pengiriman')
            ->where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::user()->id_user)
            ->first();
        abort_if(!$pg, 404);
        return $pg;
    }

    // Kurir mengambil pesanan dari toko (opsional, dipakai kalau tugas masih "MENUNGGU")
    public function ambilDariToko(Request $request, $idPesanan)
    {
        $this->milikSaya($idPesanan);

        DB::table('pengiriman')->where('id_pesanan', $idPesanan)->update([
            'status_pengiriman' => 'DIAMBIL_KURIR',
            'waktu_diambil'     => now()->toDateString(),
        ]);
        DB::table('pesanan')->where('id_pesanan', $idPesanan)->update(['status_pesanan' => 'DIPROSES']);

        return back()->with('success', 'Pesanan ditandai sudah diambil dari toko.');
    }

    // Langkah 2: "Berangkat mengantar" kini HANYA ditandai oleh admin (Admin > Pesanan > Kurir berangkat mengantar).
    // Kurir cukup mengabari admin; route ini dibiarkan supaya link lama tidak error.
    public function mulaiAntar($idPesanan)
    {
        $this->milikSaya($idPesanan);
        return back()->with('error', 'Status "Di Perjalanan" ditandai oleh admin. Kabari admin bahwa Anda siap berangkat.');
    }

    // Langkah 3: Serahkan pesanan & terima pembayaran cash -> "Selesai"
    public function tandaiTerkirim(Request $request, $idPesanan)
    {
        $pg = $this->milikSaya($idPesanan);
        if ($pg->status_pengiriman !== 'DIANTAR') {
            return back()->with('error', 'Mulai antar pesanan terlebih dahulu (Di Perjalanan).');
        }

        DB::transaction(function () use ($idPesanan) {
            DB::table('pengiriman')->where('id_pesanan', $idPesanan)->update([
                'status_pengiriman' => 'TERKIRIM',
                'waktu_terkirim'    => now()->toDateString(),
            ]);
            DB::table('pesanan')->where('id_pesanan', $idPesanan)->update(['status_pesanan' => 'SELESAI']);
        });

        return back()->with('success', 'Pesanan selesai. Terima kasih!');
=======
    // Daftar tugas Kurir:
    // 1) pesanan DELIVERY berstatus DIPROSES yang belum diambil siapa pun
    // 2) pengiriman milik kurir yang sedang login dan belum selesai
    public function index()
    {
        $idKurir = Auth::guard('web')->id();

        $siapDiambil = Pesanan::with(['pembeli', 'alamat'])
            ->where('jenis_pengambilan', 'DELIVERY')
            ->where('status_pesanan', 'DIPROSES')
            ->whereDoesntHave('pengiriman')
            ->orderBy('tanggal_ambil')
            ->get();

        $tugasSaya = Pengiriman::with(['pesanan.pembeli', 'pesanan.alamat'])
            ->where('id_user', $idKurir)
            ->whereIn('status_pengiriman', ['MENUNGGU', 'DIAMBIL_KURIR', 'DIANTAR'])
            ->get();

        return view('pengiriman.index', compact('siapDiambil', 'tugasSaya'));
    }

    // Kurir menandai sudah mengambil pesanan dari toko
    public function ambilDariToko($idPesanan)
    {
        $idKurir = Auth::guard('web')->id();

        try {
            DB::transaction(function () use ($idPesanan, $idKurir) {
                // Kunci pesanan supaya dua kurir tidak bisa mengambil bersamaan
                $pesanan = Pesanan::lockForUpdate()->findOrFail($idPesanan);

                if ($pesanan->jenis_pengambilan !== 'DELIVERY') {
                    throw new \DomainException('Pesanan ini bukan pesanan delivery');
                }

                if ($pesanan->status_pesanan !== 'DIPROSES') {
                    throw new \DomainException("Pesanan berstatus {$pesanan->status_pesanan} belum bisa diambil");
                }

                $pengiriman = $pesanan->pengiriman;

                if ($pengiriman && (int) $pengiriman->id_user !== (int) $idKurir) {
                    throw new \DomainException('Pesanan ini sudah ditugaskan ke kurir lain');
                }

                if ($pengiriman && $pengiriman->status_pengiriman !== 'MENUNGGU') {
                    throw new \DomainException('Pesanan ini sudah ditandai diambil');
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
            return back()->withErrors(['pengiriman' => $e->getMessage()]);
        }

        return back()->with('success', 'Pesanan ditandai sudah diambil dari toko');
    }

    // Kurir menandai pesanan sudah sampai ke pembeli.
    // Pembayaran harus sudah dicatat dulu (alur COD).
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
                    throw new \DomainException('Pengiriman ini bukan tugas Anda');
                }

                if (!in_array($pengiriman->status_pengiriman, ['DIAMBIL_KURIR', 'DIANTAR'])) {
                    throw new \DomainException('Pesanan belum diambil dari toko atau sudah terkirim');
                }

                if (!$pesanan->pembayaran()->exists()) {
                    throw new \DomainException('Catat pembayaran dulu sebelum menandai terkirim');
                }

                $pengiriman->update([
                    'status_pengiriman' => 'TERKIRIM',
                    'waktu_terkirim'    => now()->toDateString(),
                ]);

                $pesanan->update(['status_pesanan' => 'SELESAI']);
            });
        } catch (\DomainException $e) {
            return back()->withErrors(['pengiriman' => $e->getMessage()]);
        }

        return back()->with('success', 'Pesanan berhasil ditandai terkirim');
>>>>>>> origin/feature/backend
    }
}
