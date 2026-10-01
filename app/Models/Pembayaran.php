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
        'tempat_pembayaran',
        'tanggal_bayar',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Staf (Admin saat pickup, atau Kurir saat delivery) yang menerima uang
    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}