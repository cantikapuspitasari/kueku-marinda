<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

// Simulasi pembayaran QRIS (tanpa uang sungguhan)
//
// Alur:
// 1. Pembeli checkout dengan metode QRIS -> diarahkan ke halaman QR Code (bayar)
// 2. QR Code berisi URL bertanda tangan (signed URL) khusus untuk pesanan itu
// 3. Pembeli scan dengan HP -> terbuka halaman simulasi (simulasi)
// 4. Pembeli menekan "Konfirmasi Pembayaran" (konfirmasi)
// 5. Server memvalidasi, status_pembayaran pesanan menjadi LUNAS
class QrisController extends Controller
{
    // Masa berlaku QR Code (menit)
    const BERLAKU_MENIT = 60;

    // ------------------------------------------------------------------
    // Halaman pembeli: menampilkan QR Code dan status pembayaran
    // ------------------------------------------------------------------
    public function bayar(Request $request, $id)
    {
        $pesanan = $this->pesananMilikSaya($id);

        if (! $pesanan->isQris()) {
            return redirect()->route('pesanan.saya')
                ->with('error', 'Pesanan ini tidak dibayar dengan QRIS.');
        }

        if ($pesanan->status_pesanan === 'DIBATALKAN') {
            return redirect()->route('pesanan.saya')
                ->with('error', 'Pesanan ini sudah dibatalkan.');
        }

        $lunas = $pesanan->status_pembayaran === 'LUNAS';
        $urlQr = $lunas ? null : $this->urlSimulasi($request, $pesanan->id_pesanan);

        // HP tidak bisa membuka alamat localhost milik laptop
        $hostQr = $urlQr ? parse_url($urlQr, PHP_URL_HOST) : null;
        $hostLokal = in_array($hostQr, ['localhost', '127.0.0.1', '::1', '[::1]'], true);

        return view('qris.bayar', [
            'pesanan'   => $pesanan,
            'lunas'     => $lunas,
            'urlQr'     => $urlQr,
            'hostLokal' => $hostLokal,
            'menit'     => self::BERLAKU_MENIT,
        ]);
    }

    // Dipanggil halaman pembeli tiap beberapa detik untuk mengecek status
    public function status($id)
    {
        $pesanan = $this->pesananMilikSaya($id);

        return response()
            ->json([
                'lunas'             => $pesanan->status_pembayaran === 'LUNAS',
                'status_pembayaran' => $pesanan->status_pembayaran ?? 'BELUM_BAYAR',
                'status_pesanan'    => $pesanan->status_pesanan,
            ])
            ->header('Cache-Control', 'no-store');
    }

    // ------------------------------------------------------------------
    // Halaman simulasi yang terbuka setelah QR Code di-scan (route "signed")
    // ------------------------------------------------------------------
    public function simulasi($id)
    {
        $pesanan = Pesanan::with('pembeli')->findOrFail($id);
        abort_unless($pesanan->isQris(), 404);

        $konfirmasiUrl = URL::temporarySignedRoute(
            'qris.konfirmasi',
            now()->addMinutes(self::BERLAKU_MENIT),
            ['id' => $pesanan->id_pesanan]
        );

        return view('qris.simulasi', [
            'pesanan'       => $pesanan,
            'lunas'         => $pesanan->status_pembayaran === 'LUNAS',
            'batal'         => $pesanan->status_pesanan === 'DIBATALKAN',
            'konfirmasiUrl' => $konfirmasiUrl,
        ]);
    }

    // Pembeli menekan tombol konfirmasi di halaman simulasi (route "signed")
    public function konfirmasi($id)
    {
        try {
            DB::transaction(function () use ($id) {

                // Kunci pesanan supaya konfirmasi ganda tidak diproses dua kali
                $pesanan = Pesanan::lockForUpdate()->findOrFail($id);

                if (! $pesanan->isQris()) {
                    throw new \DomainException('Pesanan ini tidak dibayar dengan QRIS.');
                }

                if ($pesanan->status_pesanan === 'DIBATALKAN') {
                    throw new \DomainException('Pesanan sudah dibatalkan, pembayaran tidak dapat diproses.');
                }

                // Sudah lunas: tidak diubah lagi (aman bila tombol ditekan dua kali)
                if ($pesanan->status_pembayaran === 'LUNAS') {
                    return;
                }

                $pesanan->update([
                    'status_pembayaran' => 'LUNAS',
                    'waktu_bayar'       => now(),
                ]);
            });
        } catch (\DomainException $e) {
            return back()->with('qris_error', $e->getMessage());
        }

        // Kembali ke halaman simulasi yang kini menampilkan status LUNAS
        return redirect()->to(URL::temporarySignedRoute(
            'qris.simulasi',
            now()->addMinutes(self::BERLAKU_MENIT),
            ['id' => (int) $id]
        ));
    }

    // ------------------------------------------------------------------
    // Pembantu
    // ------------------------------------------------------------------

    // Pembeli hanya boleh membuka pesanannya sendiri
    private function pesananMilikSaya($id): Pesanan
    {
        return Pesanan::where('id_pesanan', $id)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->firstOrFail();
    }

    // URL simulasi bertanda tangan yang dimasukkan ke QR Code.
    // Alamat dasarnya harus bisa dibuka dari HP (bukan localhost).
    private function urlSimulasi(Request $request, $idPesanan): string
    {
        $rootAsal = $request->root();

        try {
            URL::forceRootUrl($this->alamatDasar($request));

            return URL::temporarySignedRoute(
                'qris.simulasi',
                now()->addMinutes(self::BERLAKU_MENIT),
                ['id' => $idPesanan]
            );
        } finally {
            URL::forceRootUrl($rootAsal);
        }
    }

    // Prioritas alamat dasar QR Code:
    // 1. QRIS_BASE_URL di .env (mis. http://192.168.1.10:8000 atau domain/ngrok)
    // 2. Bila situs dibuka lewat localhost: alamat IP laptop di jaringan WiFi
    // 3. Alamat situs yang sedang dibuka
    private function alamatDasar(Request $request): string
    {
        $env = rtrim((string) env('QRIS_BASE_URL', ''), '/');
        if ($env !== '') {
            return $env;
        }

        if (in_array($request->getHost(), ['localhost', '127.0.0.1', '::1'], true)) {
            $ip = $this->ipLan();
            if ($ip) {
                $port = $request->getPort();
                $pakaiPort = $port && ! in_array((int) $port, [80, 443], true);

                return $request->getScheme() . '://' . $ip . ($pakaiPort ? ':' . $port : '');
            }
        }

        return $request->root();
    }

    // Alamat IPv4 laptop/server pada jaringan lokal
    private function ipLan(): ?string
    {
        $ip = null;

        // Cara 1: tanya OS interface mana yang dipakai keluar (tidak mengirim data)
        $sock = @stream_socket_client('udp://8.8.8.8:53', $errno, $errstr, 1);
        if ($sock) {
            $nama = stream_socket_get_name($sock, false);
            fclose($sock);
            if ($nama) {
                $ip = explode(':', $nama)[0];
            }
        }

        // Cara 2: dari nama host komputer
        if (! $ip || str_starts_with($ip, '127.') || $ip === '0.0.0.0') {
            $ip = @gethostbyname(gethostname());
        }

        if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && ! str_starts_with($ip, '127.')) {
            return $ip;
        }

        return null;
    }
}
