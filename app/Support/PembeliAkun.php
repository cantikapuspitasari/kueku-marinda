<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

// Menghubungkan akun pelanggan (users) dengan data pembeli.
// Dicocokkan lewat email (huruf besar/kecil & spasi diabaikan).
// Kalau tidak ketemu, dicoba lewat no. HP lalu nama, dan email pembeli diperbaiki otomatis.
class PembeliAkun
{
    // Semua id_pembeli milik akun ini (bisa lebih dari satu kalau ada baris ganda)
    public static function ids($user): array
    {
        $email = strtolower(trim((string) $user->email));

        $ids = DB::table('pembeli')
            ->whereRaw('LOWER(TRIM(email)) = ?', [$email])
            ->pluck('id_pembeli')->all();

        if (!$ids) {
            $q = DB::table('pembeli');
            $telp = trim((string) ($user->no_telepon ?? ''));
            if ($telp !== '') {
                $q->where('no_telepon', $telp);
            } else {
                $q->where('nama', $user->nama);
            }
            $ids = $q->pluck('id_pembeli')->all();

            // Perbaiki email supaya ke depannya langsung cocok
            if (count($ids) === 1) {
                DB::table('pembeli')->where('id_pembeli', $ids[0])->update(['email' => $user->email]);
            } else {
                $ids = [];
            }
        }

        return $ids;
    }

    // Satu id utama (dipakai saat membuat alamat/pesanan baru)
    public static function id($user)
    {
        $ids = self::ids($user);
        return $ids ? min($ids) : null;
    }
}
