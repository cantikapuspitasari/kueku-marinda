<?php

namespace App\Support;

class Telepon
{
    /** Samakan format nomor HP: 0812..., 812..., +62812..., 62812... -> 0812... */
    public static function normalisasi(?string $nomor): string
    {
        $d = preg_replace('/\D/', '', (string) $nomor);
        if ($d === '') return '';
        if (str_starts_with($d, '62')) return '0' . substr($d, 2);
        if (!str_starts_with($d, '0')) return '0' . $d;
        return $d;
    }
}
