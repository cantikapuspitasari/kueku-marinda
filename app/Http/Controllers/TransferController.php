<?php

namespace App\Http\Controllers;

use App\Models\BuktiTransfer;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Pembayaran Transfer Bank (simulasi, tanpa uang sungguhan)
//
// Alur:
// 1. Pembeli checkout memilih Transfer Bank + bank tujuan
// 2. Halaman bayar menampilkan rekening SIMULASI, kode pesanan, dan total
// 3. Pembeli "transfer" lewat mobile banking / ATM (simulasi), lalu unggah bukti
// 4. status_pembayaran = MENUNGGU_VERIFIKASI (belum dianggap lunas)
// 5. Admin menerima -> LUNAS, atau menolak -> DITOLAK (pembeli boleh unggah ulang)
class TransferController extends Controller
{
    // ------------------------------------------------------------------
    // Halaman pembeli: rekening tujuan, unggah bukti, status verifikasi
    // ------------------------------------------------------------------
    public function bayar($id)
    {
        $pesanan = $this->pesananMilikSaya($id);

        if (! $pesanan->isTransfer()) {
            return redirect()->route('pesanan.saya')
                ->with('error', 'Pesanan ini tidak dibayar dengan Transfer Bank.');
        }

        $bank = Pesanan::BANK_TRANSFER[$pesanan->bank_tujuan] ?? null;

        return view('transfer.bayar', [
            'pesanan' => $pesanan,
            'bank'    => $bank,
            'riwayat' => $pesanan->buktiTransfer()->get(),
            'batal'   => $pesanan->status_pesanan === 'DIBATALKAN',
        ]);
    }

    // ------------------------------------------------------------------
    // Pembeli mengunggah bukti transfer
    // ------------------------------------------------------------------
    public function unggah(Request $request, $id)
    {
        $request->validate([
            // Isi file diperiksa dari kontennya (bukan hanya ekstensi): harus gambar JPG/PNG, maks 2 MB
            'bukti' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'mimetypes:image/jpeg,image/png', 'max:2048'],
        ], [
            'bukti.required' => 'Pilih file bukti transfer terlebih dahulu.',
            'bukti.image'    => 'Bukti transfer harus berupa gambar (JPG atau PNG).',
            'bukti.mimes'    => 'Format bukti transfer harus JPG atau PNG.',
            'bukti.mimetypes' => 'Format bukti transfer harus JPG atau PNG.',
            'bukti.max'      => 'Ukuran bukti transfer maksimal 2 MB.',
            'bukti.uploaded' => 'Unggahan gagal. Pastikan ukuran file maksimal 2 MB.',
        ]);

        $file = $request->file('bukti');
        $path = null;

        try {
            DB::transaction(function () use ($id, $file, &$path) {

                // Kunci pesanan supaya unggahan ganda tidak tercatat dua kali
                $pesanan = Pesanan::where('id_pesanan', $id)
                    ->where('id_pembeli', Auth::guard('pembeli')->id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $pesanan->isTransfer()) {
                    throw new \DomainException('Pesanan ini tidak dibayar dengan Transfer Bank.');
                }

                if ($pesanan->status_pesanan === 'DIBATALKAN') {
                    throw new \DomainException('Pesanan sudah dibatalkan.');
                }

                if ($pesanan->statusBayar() === 'LUNAS') {
                    throw new \DomainException('Pembayaran pesanan ini sudah LUNAS.');
                }

                if ($pesanan->statusBayar() === 'MENUNGGU_VERIFIKASI') {
                    throw new \DomainException('Bukti transfer sedang diperiksa Admin. Tunggu hasil verifikasi.');
                }

                // Disimpan di disk PRIVAT (storage/app/private), nama acak, tanpa nama asli dari pengguna.
                // File tidak bisa dibuka lewat URL langsung, hanya lewat route yang mengecek hak akses.
                $path = $file->storeAs(
                    'bukti-transfer',
                    bin2hex(random_bytes(16)) . '.' . ($file->guessExtension() === 'png' ? 'png' : 'jpg'),
                    'local'
                );

                BuktiTransfer::create([
                    'id_pesanan'    => $pesanan->id_pesanan,
                    'bank'          => $pesanan->bank_tujuan ?: '-',
                    'nominal'       => (int) round($pesanan->total_harga),
                    'file_path'     => $path,
                    'nama_asli'     => mb_substr(preg_replace('/[^\w .\-]/u', '', $file->getClientOriginalName()), 0, 150),
                    'status'        => 'MENUNGGU',
                    'diunggah_pada' => now(),
                ]);

                $pesanan->update(['status_pembayaran' => 'MENUNGGU_VERIFIKASI']);
            });
        } catch (\DomainException $e) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }

            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('transfer.bayar', $id)
            ->with('success', 'Bukti transfer berhasil diunggah. Status pembayaran: MENUNGGU VERIFIKASI.');
    }

    // ------------------------------------------------------------------
    // Menampilkan file bukti (hanya Admin / Owner, atau pembeli pemilik pesanan)
    // ------------------------------------------------------------------
    public function bukti($idBukti)
    {
        $bukti = BuktiTransfer::with('pesanan')->findOrFail($idBukti);

        $staf = Auth::guard('web')->user();

        if ($staf) {
            abort_unless(
                in_array($staf->role, ['ADMIN', 'OWNER'], true) && $staf->status_akun !== 'NONAKTIF',
                403
            );
        } else {
            $pembeli = Auth::guard('pembeli')->user();
            abort_unless(
                $pembeli && (int) $pembeli->id_pembeli === (int) ($bukti->pesanan->id_pembeli ?? 0),
                403
            );
        }

        // Nama file di DB dibuat server; pastikan tetap di dalam folder bukti-transfer
        abort_unless(str_starts_with($bukti->file_path, 'bukti-transfer/') && ! str_contains($bukti->file_path, '..'), 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($bukti->file_path), 404);

        $lokasi = $disk->path($bukti->file_path);

        // Jenis file ditentukan dari ISI file, bukan dari ekstensi
        $mime = function_exists('mime_content_type') ? @mime_content_type($lokasi) : null;
        if (! $mime) {
            $info = @getimagesize($lokasi);
            $mime = $info['mime'] ?? null;
        }
        if (! in_array($mime, ['image/jpeg', 'image/png'], true)) {
            abort(404, 'File bukti tidak dapat ditampilkan.');
        }

        // Buang keluaran yang mungkin sudah tertampung sebelum gambar dikirim (mencegah gambar rusak)
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }

        // BinaryFileResponse mengirim file apa adanya (aman untuk data biner)
        return response()->file($lokasi, [
            'Content-Type'           => $mime,
            'Content-Disposition'    => 'inline; filename="bukti-' . (int) $bukti->id_bukti . ($mime === 'image/png' ? '.png' : '.jpg') . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, no-store',
        ]);
    }

    // Pembeli hanya boleh membuka pesanannya sendiri
    private function pesananMilikSaya($id): Pesanan
    {
        return Pesanan::where('id_pesanan', $id)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->firstOrFail();
    }
}
