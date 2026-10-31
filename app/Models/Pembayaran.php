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
        'id_user',            // staf yang menerima pembayaran (admin atau kurir)
        'jumlah_bayar',
        'tempat_pembayaran',  // TOKO atau ALAMAT_PEMBELI
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah_bayar'  => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Staf penerima pembayaran (tabel users, kolom id_user)
    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
