<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pembeli extends Authenticatable
{
    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
        'status_akun',
    ];

    protected $hidden = [
        'password',
    ];

    // Tabel tidak punya remember_token, fitur "remember me" dimatikan
    public function getRememberToken() { return null; }
    public function setRememberToken($value) {}
    public function getRememberTokenName() { return ''; }

    // ---------- Relasi ----------

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pembeli', 'id_pembeli');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pembeli', 'id_pembeli');
    }
}