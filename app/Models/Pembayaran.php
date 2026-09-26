<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'id_penerima',
        'jumlah_bayar',
        'tempat_pembayaran',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    // Pembayaran untuk satu pesanan
    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Pembayaran diterima oleh satu user
    // Bisa ADMIN atau KURIR sesuai data sistem
    public function penerima()
    {
        return $this->belongsTo(
            User::class,
            'id_penerima',
            'id_user'
        );
    }
}