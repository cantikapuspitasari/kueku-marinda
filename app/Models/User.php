<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Nama tabel di database beda dari default Laravel (users -> tetap sama, tapi PK-nya beda)
    protected $table = 'users';

    // Primary key kita namanya id_user, bukan id bawaan Laravel
    protected $primaryKey = 'id_user';

    // Laravel default nambahin updated_at otomatis, tabel kita cuma punya created_at
    public $timestamps = false;

    // Kolom yang boleh diisi lewat mass-assignment (User::create([...]))
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
        'role',
        'status_akun',
    ];

    // Password jangan ikut ke-return kalau data user di-convert ke JSON/API
    protected $hidden = [
        'password',
    ];

    // ---------- Relasi ----------

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_user', 'id_user');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    // Sebagai admin/kurir yang menerima pembayaran
    public function pembayaranDiterima()
    {
        return $this->hasMany(Pembayaran::class, 'id_penerima', 'id_user');
    }

    // Sebagai kurir yang mengantar
    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_kurir', 'id_user');
    }
}