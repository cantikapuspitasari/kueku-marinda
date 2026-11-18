<?php

namespace App\Http\Controllers;

use App\Models\BuktiTransfer;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Verifikasi pembayaran Transfer Bank oleh Admin
class AdminTransferController extends Controller
{
    // Daftar pembayaran transfer (pesanan bermetode TRANSFER), bisa difilter per status
    public function index(Request $request)
    {
        $filter = $request->query('status', 'MENUNGGU_VERIFIKASI');
        $valid  = ['SEMUA', 'BELUM_BAYAR', 'MENUNGGU_VERIFIKASI', 'DITOLAK', 'LUNAS'];
        if (! in_array($filter, $valid, true)) {
            $filter = 'MENUNGGU_VERIFIKASI';
        }

        $dasar = Pesanan::where('metode_pembayaran', 'TRANSFER')
            ->where('status_pesanan', '!=', 'DIBATALKAN');

        $hitung = [
            'SEMUA' => (clone $dasar)->count(),
        ];
        foreach (array_slice($valid, 1) as $s) {
            $hitung[$s] = (clone $dasar)->where('status_pembayaran', $s)->count();
        }

        $q = (clone $dasar)->with('pembeli');
        if ($filter !== 'SEMUA') {
            $q->where('status_pembayaran', $filter);
        }
        $pesanan = $q->orderByDesc('tanggal_pesan')->orderByDesc('id_pesanan')->get();

        // Bukti terakhir tiap pesanan (satu query)
        $buktiTerakhir = BuktiTransfer::whereIn('id_pesanan', $pesanan->pluck('id_pesanan'))
            ->orderBy('id_bukti')
            ->get()
            ->keyBy('id_pesanan');

        return view('admin.transfer.index', compact('pesanan', 'buktiTerakhir', 'hitung', 'filter'));
    }

    // Detail satu pesanan transfer: nominal, bukti, riwayat verifikasi
    public function show($idPesanan)
    {
        $pesanan = Pesanan::with(['pembeli', 'detailPesanan.produk'])
            ->where('metode_pembayaran', 'TRANSFER')
            ->findOrFail($idPesanan);

        $riwayat = $pesanan->buktiTransfer()->with('verifikator')->get();

        return view('admin.transfer.show', [
            'pesanan' => $pesanan,
            'riwayat' => $riwayat,
            'bukti'   => $riwayat->first(),
            'bank'    => Pesanan::BANK_TRANSFER[$pesanan->bank_tujuan] ?? null,
        ]);
    }

    // Terima bukti -> pembayaran LUNAS
    public function terima($idBukti)
    {
        try {
            $idPesanan = DB::transaction(function () use ($idBukti) {
                $bukti   = BuktiTransfer::lockForUpdate()->findOrFail($idBukti);
                $pesanan = Pesanan::lockForUpdate()->findOrFail($bukti->id_pesanan);

                $this->pastikanBisaDiverifikasi($bukti, $pesanan);

                // Nominal yang diverifikasi harus sama dengan total pesanan saat ini
                if ((int) $bukti->nominal !== (int) round($pesanan->total_harga)) {
                    throw new \DomainException('Nominal bukti tidak sama dengan total pesanan.');
                }

                $bukti->update([
                    'status'            => 'DITERIMA',
                    'catatan_admin'     => null,
                    'id_verifikator'    => Auth::guard('web')->id(),
                    'diverifikasi_pada' => now(),
                ]);

                $pesanan->update([
                    'status_pembayaran' => 'LUNAS',
                    'waktu_bayar'       => now(),
                ]);

                return $pesanan->id_pesanan;
            });
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.transfer.show', $idPesanan)
            ->with('success', 'Pembayaran diterima. Status pembayaran pesanan: LUNAS. Pesanan sekarang bisa diverifikasi dan diproses.');
    }

    // Tolak bukti -> pembeli melihat alasan dan boleh unggah ulang
    public function tolak(Request $request, $idBukti)
    {
        $data = $request->validate([
            'catatan_admin' => 'required|string|min:5|max:255',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi agar pembeli tahu apa yang harus diperbaiki.',
            'catatan_admin.min'      => 'Alasan penolakan minimal 5 karakter.',
            'catatan_admin.max'      => 'Alasan penolakan maksimal 255 karakter.',
        ]);

        try {
            $idPesanan = DB::transaction(function () use ($idBukti, $data) {
                $bukti   = BuktiTransfer::lockForUpdate()->findOrFail($idBukti);
                $pesanan = Pesanan::lockForUpdate()->findOrFail($bukti->id_pesanan);

                $this->pastikanBisaDiverifikasi($bukti, $pesanan);

                $bukti->update([
                    'status'            => 'DITOLAK',
                    'catatan_admin'     => trim(strip_tags($data['catatan_admin'])),
                    'id_verifikator'    => Auth::guard('web')->id(),
                    'diverifikasi_pada' => now(),
                ]);

                $pesanan->update(['status_pembayaran' => 'DITOLAK']);

                return $pesanan->id_pesanan;
            });
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.transfer.show', $idPesanan)
            ->with('success', 'Bukti transfer ditolak. Pembeli akan melihat alasannya dan dapat mengunggah ulang.');
    }

    // Hanya bukti yang masih MENUNGGU dari pesanan transfer aktif yang boleh diproses
    private function pastikanBisaDiverifikasi(BuktiTransfer $bukti, Pesanan $pesanan): void
    {
        if (! $pesanan->isTransfer()) {
            throw new \DomainException('Pesanan ini tidak dibayar dengan Transfer Bank.');
        }

        if ($pesanan->status_pesanan === 'DIBATALKAN') {
            throw new \DomainException('Pesanan sudah dibatalkan.');
        }

        if ($bukti->status !== 'MENUNGGU') {
            throw new \DomainException('Bukti ini sudah diverifikasi sebelumnya.');
        }

        // Hanya bukti TERBARU yang valid (cegah memproses bukti lama)
        $terbaru = BuktiTransfer::where('id_pesanan', $pesanan->id_pesanan)->max('id_bukti');
        if ((int) $terbaru !== (int) $bukti->id_bukti) {
            throw new \DomainException('Ada bukti yang lebih baru untuk pesanan ini. Muat ulang halaman.');
        }

        if ($pesanan->statusBayar() !== 'MENUNGGU_VERIFIKASI') {
            throw new \DomainException('Status pembayaran pesanan bukan MENUNGGU VERIFIKASI.');
        }
    }
}
