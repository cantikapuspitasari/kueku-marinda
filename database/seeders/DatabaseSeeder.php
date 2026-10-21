<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Salin semua pelanggan dari tabel "pembeli" ke tabel "users"
        // supaya bisa login. Aman dijalankan berulang (tidak membuat data dobel).
        foreach (DB::table('pembeli')->get() as $p) {
            User::updateOrCreate(
                ['email' => $p->email],
                [
                    'nama'        => $p->nama,
                    'password'    => $p->password,   // hash lama dipakai apa adanya
                    'no_telepon'  => $p->no_telepon,
                    'role'        => 'PEMBELI',
                    'status_akun' => 'AKTIF',
                ]
            );
        }
    }
}
