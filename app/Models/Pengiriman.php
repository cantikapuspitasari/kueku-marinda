<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'status_pengiriman',
        'waktu_diambil',
        'waktu_terkirim',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}