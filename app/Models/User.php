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

    // Tabel kita nggak punya kolom remember_token, jadi fitur "remember me" dimatikan
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // sengaja dikosongkan
    }

    public function getRememberTokenName()
    {
        return '';
    }

    // ---------- Relasi ----------

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_user', 'id_user');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    public function pembayaranDiterima()
    {
        return $this->hasMany(Pembayaran::class, 'id_penerima', 'id_user');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_kurir', 'id_user');
    }
}