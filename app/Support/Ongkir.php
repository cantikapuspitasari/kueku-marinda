<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

// Ongkos kirim berdasarkan jarak lokasi pengantaran ke toko.
// Jarak diambil dari rute jalan (OSRM / data OpenStreetMap). Kalau layanan peta sedang
// tidak bisa dihubungi, dipakai jarak garis lurus x FAKTOR_JALAN supaya pesanan tetap bisa dibuat.
class Ongkir
{
    // Titik toko (ganti dengan koordinat toko sebenarnya: Google Maps -> klik kanan di toko -> salin angka koordinat)
    const TOKO_LAT = -7.0979546;
    const TOKO_LNG = 107.4676555;

    // Tarif per jarak (km lewat jalan): [batas atas km, ongkir Rp]. Ubah angka di sini bila tarif berubah.
    const TARIF = [
        [2,  3000],
        [4,  4000],
        [6,  5000],
        [10, 7000],
        [14, 8500],
        [20, 10000],
    ];

    // Lebih jauh dari ini = di luar jangkauan pengantaran
    const MAKS_KM = 20;

    // Pengali jarak garis lurus menjadi perkiraan jarak jalan (hanya untuk cadangan)
    const FAKTOR_JALAN = 1.4;

    // Hasil hitung ongkir berlaku sekian menit (lalu harus dihitung ulang)
    const TOKEN_MENIT = 30;

    // Jarak garis lurus (km), rumus Haversine
    public static function jarakLurus(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * $r * asin(min(1, sqrt($a)));
    }

    // Jarak toko -> lokasi pelanggan. Return [km, metode] dengan metode "rute" atau "perkiraan"
    public static function jarakKm(float $lat, float $lng): array
    {
        try {
            $url = sprintf(
                'https://router.project-osrm.org/route/v1/driving/%F,%F;%F,%F',
                self::TOKO_LNG, self::TOKO_LAT, $lng, $lat
            );
            $res = Http::timeout(4)
                ->withHeaders(['User-Agent' => 'KuekuMarinda/1.0 (tugas sekolah)'])
                ->get($url, ['overview' => 'false']);

            if ($res->successful() && ($res->json('code') === 'Ok') && $res->json('routes.0.distance') !== null) {
                return [round($res->json('routes.0.distance') / 1000, 1), 'rute'];
            }
        } catch (\Throwable $e) {
            // layanan peta tidak terjangkau -> pakai perkiraan di bawah
        }

        $km = self::jarakLurus(self::TOKO_LAT, self::TOKO_LNG, $lat, $lng) * self::FAKTOR_JALAN;

        return [round($km, 1), 'perkiraan'];
    }

    // Ongkir untuk jarak tertentu, null kalau di luar jangkauan
    public static function tarif(float $km): ?int
    {
        if ($km > self::MAKS_KM) {
            return null;
        }
        foreach (self::TARIF as [$batas, $harga]) {
            if ($km <= $batas) {
                return $harga;
            }
        }

        return null;
    }

    // Koordinat harus masuk akal (wilayah Indonesia)
    public static function koordinatValid(float $lat, float $lng): bool
    {
        return $lat >= -11.5 && $lat <= 6.5 && $lng >= 94 && $lng <= 141.5;
    }

    // Token terenkripsi berisi hasil hitung, supaya browser tidak bisa mengarang ongkir sendiri
    public static function buatToken(float $lat, float $lng, float $km, int $ongkir): string
    {
        return Crypt::encryptString(json_encode([
            'lat' => $lat, 'lng' => $lng, 'km' => $km, 'ongkir' => $ongkir,
            'exp' => time() + self::TOKEN_MENIT * 60,
        ]));
    }

    // Return array data token, atau null kalau rusak / kedaluwarsa
    public static function bacaToken(?string $token): ?array
    {
        if (!$token) {
            return null;
        }
        try {
            $d = json_decode(Crypt::decryptString($token), true);
        } catch (\Throwable $e) {
            return null;
        }
        if (!is_array($d) || !isset($d['exp'], $d['ongkir']) || $d['exp'] < time()) {
            return null;
        }

        return $d;
    }
}
