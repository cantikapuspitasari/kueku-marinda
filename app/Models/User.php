<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
        'role',
        'status_akun',
    ];

    protected $hidden = [
        'password',
    ];

    public function getRememberToken() { return null; }
    public function setRememberToken($value) {}
    public function getRememberTokenName() { return ''; }

    // ---------- Relasi ----------
    // Catatan: User (staf) tidak lagi punya relasi ke Alamat/Pesanan,
    // karena itu sekarang milik Pembeli.

    public function pembayaranDiterima()
    {
        return $this->hasMany(Pembayaran::class, 'id_user', 'id_user');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_user', 'id_user');
    }
}