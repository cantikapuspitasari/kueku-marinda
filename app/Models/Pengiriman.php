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
        'id_kurir',
        'status_pengiriman',
        'waktu_diambil',
        'waktu_terkirim',
    ];

    protected $casts = [
        'waktu_diambil' => 'date',
        'waktu_terkirim' => 'date',
    ];

    // Pengiriman untuk satu pesanan
    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Pengiriman ditangani oleh satu kurir
    public function kurir()
    {
        return $this->belongsTo(
            User::class,
            'id_kurir',
            'id_user'
        );
    }
}