<?php

namespace App\Support;

/**
 * Menerjemahkan data database (pesanan + pengiriman) menjadi 5 tahap
 * yang dilihat pelanggan. Dipakai bersama oleh Admin dan halaman pelanggan,
 * jadi keduanya selalu sepakat.
 *
 * DELIVERY (tahap 0-4):
 *   0 Pesanan di konfirmasi   = pesanan DIKONFIRMASI
 *   1 Sedang di kemas         = pesanan DIPROSES (belum ada kurir mengambil)
 *   2 Di serahkan ke kurir    = pengiriman DIAMBIL_KURIR
 *   3 Kurir menuju lokasimu   = pengiriman DIANTAR
 *   4 Pesanan sampai tujuan   = pengiriman TERKIRIM / pesanan SELESAI
 * PICKUP: 0 konfirmasi, 1 dikemas, 4 diterima (SELESAI).
 */
class TahapPesanan
{
    public static function hitung(string $statusPesanan, string $jenis, ?string $statusKirim): array
    {
        if ($statusPesanan === 'DIBATALKAN') return ['state' => 'batal', 'tahap' => -1];
        if ($statusPesanan === 'PENDING')    return ['state' => 'menunggu', 'tahap' => -1];
        if ($statusPesanan === 'SELESAI')    return ['state' => 'ok', 'tahap' => 4];
        if ($statusPesanan === 'DIKONFIRMASI') return ['state' => 'ok', 'tahap' => 0];

        // DIPROSES
        if ($jenis === 'DELIVERY') {
            $tahap = match ($statusKirim) {
                'DIAMBIL_KURIR' => 2,
                'DIANTAR'       => 3,
                'TERKIRIM'      => 4,
                default         => 1,
            };
            return ['state' => 'ok', 'tahap' => $tahap];
        }
        return ['state' => 'ok', 'tahap' => 1];
    }

    /** Langkah berikutnya yang boleh dilakukan admin (null kalau tidak ada). */
    public static function berikutnya(string $statusPesanan, string $jenis, ?string $statusKirim): ?array
    {
        if ($statusPesanan === 'DIKONFIRMASI') {
            return ['aksi' => 'kemas', 'label' => 'Mulai dikemas (dapur)', 'butuh_kurir' => false];
        }
        if ($statusPesanan !== 'DIPROSES') return null;

        if ($jenis === 'PICKUP') {
            return ['aksi' => 'selesai_toko', 'label' => 'Pesanan sudah diambil pelanggan', 'butuh_kurir' => false];
        }
        return match ($statusKirim) {
            'DIAMBIL_KURIR' => ['aksi' => 'antar',    'label' => 'Kurir berangkat mengantar', 'butuh_kurir' => false],
            'DIANTAR'       => ['aksi' => 'terkirim', 'label' => 'Pesanan sudah sampai ke pembeli', 'butuh_kurir' => false],
            'TERKIRIM'      => null,
            default         => ['aksi' => 'serahkan', 'label' => 'Serahkan ke kurir', 'butuh_kurir' => true],
        };
    }
}
