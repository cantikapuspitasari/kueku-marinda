<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Pembeli;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun contoh dengan password umum, jangan pernah dipakai di server sungguhan
        if (app()->environment('production')) {
            $this->command->error('Seeder ini hanya untuk development.');
            return;
        }

        $password = Hash::make('rahasia123');

        // ---------- Staf ----------
        $staf = [
            ['nama' => 'Bu Marinda',     'email' => 'admin@gmail.com',  'no_telepon' => '082345678901', 'role' => 'ADMIN'],
            ['nama' => 'Pak Sandi',      'email' => 'owner@gmail.com',  'no_telepon' => '083567891200', 'role' => 'OWNER'],
            ['nama' => 'Deni Kurniawan', 'email' => 'kurir@gmail.com',  'no_telepon' => '084567891230', 'role' => 'KURIR'],
            ['nama' => 'Eko Prasetyo',   'email' => 'kurir2@gmail.com', 'no_telepon' => '085678912340', 'role' => 'KURIR'],
        ];

        foreach ($staf as $s) {
            User::updateOrCreate(
                ['email' => $s['email']],
                $s + ['password' => $password, 'status_akun' => 'AKTIF']
            );
        }

        // ---------- Pembeli ----------
        $pembeli = [
            ['nama' => 'Rina Amelia',  'email' => 'rina@gmail.com', 'no_telepon' => '081234567890'],
            ['nama' => 'Budi Santoso', 'email' => 'budi@gmail.com', 'no_telepon' => '081298765432'],
        ];

        foreach ($pembeli as $p) {
            $akun = Pembeli::updateOrCreate(
                ['email' => $p['email']],
                $p + ['password' => $password, 'status_akun' => 'AKTIF']
            );

            // Alamat untuk tes pesanan delivery
            DB::table('alamat')->updateOrInsert(
                ['id_pembeli' => $akun->id_pembeli, 'label_alamat' => 'Rumah'],
                [
                    'alamat_lengkap' => 'Jl. Melati No. 12, RT 01/RW 05',
                    'kota'           => 'Bandung',
                    'kode_pos'       => '40123',
                ]
            );
        }

        // ---------- Kategori & produk ----------
        $roti = Kategori::firstOrCreate(
            ['nama_kategori' => 'Roti'],
            ['deskripsi' => 'Berbagai pilihan roti lembut untuk sarapan dan camilan']
        );
        $kering = Kategori::firstOrCreate(
            ['nama_kategori' => 'Kue Kering'],
            ['deskripsi' => 'Kue kering untuk oleh-oleh dan hampers']
        );

        $produk = [
            ['id_kategori' => $roti->id_kategori,   'nama_produk' => 'Roti Abon Mayo', 'deskripsi' => 'Roti lembut dengan isian mayones dan abon.', 'harga' => 35000, 'stok' => 20, 'status_produk' => 'TERSEDIA'],
            ['id_kategori' => $kering->id_kategori, 'nama_produk' => 'Nastar Premium', 'deskripsi' => 'Nastar lumer berisi selai nanas.',           'harga' => 65000, 'stok' => 0,  'status_produk' => 'STOK_HABIS'],
            ['id_kategori' => $roti->id_kategori,   'nama_produk' => 'Roti Almond',    'deskripsi' => 'Roti lembut bertabur almond.',               'harga' => 20000, 'stok' => 5,  'status_produk' => 'NONAKTIF'],
        ];

        foreach ($produk as $item) {
            Produk::firstOrCreate(['nama_produk' => $item['nama_produk']], $item);
        }
    }
}