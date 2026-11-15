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
        'id_user',
        'jumlah_bayar',
        'uang_diterima',
        'kembalian',
        'tempat_pembayaran',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'uang_diterima' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function penerima()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }
}
