<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $table = 'pembeli';

    protected $primaryKey = 'id_pembeli';

=======
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pembeli extends Authenticatable
{
    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';
>>>>>>> origin/feature/backend
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
<<<<<<< HEAD
=======
        'status_akun',
>>>>>>> origin/feature/backend
    ];

    protected $hidden = [
        'password',
    ];

<<<<<<< HEAD
    public function pesanan()
    {
        return $this->hasMany(
            Pesanan::class,
            'id_pembeli',
            'id_pembeli'
        );
    }

    public function alamat()
    {
        return $this->hasMany(
            Alamat::class,
            'id_pembeli',
            'id_pembeli'
        );
    }
}
=======
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
>>>>>>> origin/feature/backend
