<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $table = 'pembeli';

    protected $primaryKey = 'id_pembeli';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
    ];

    protected $hidden = [
        'password',
    ];

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
