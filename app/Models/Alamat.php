<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';
    protected $primaryKey = 'id_alamat';
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli',
        'label_alamat',
        'alamat_lengkap',
        'kota',
        'kode_pos',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_alamat', 'id_alamat');
    }
}