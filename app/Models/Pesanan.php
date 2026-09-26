<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    public $timestamps = false;

    protected $fillable = [
        'kode_pesanan',
        'id_user',
        'id_alamat',
        'tanggal_pesan',
        'tanggal_ambil',
        'jenis_pengambilan',
        'status_pesanan',
        'total_harga',
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'tanggal_ambil' => 'date',
        'total_harga' => 'decimal:2',
    ];

    // Pesanan dibuat oleh satu user/pembeli
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    // Pesanan menggunakan satu alamat
    public function alamat()
    {
        return $this->belongsTo(
            Alamat::class,
            'id_alamat',
            'id_alamat'
        );
    }

    // Satu pesanan memiliki banyak detail produk
    public function detailPesanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Satu pesanan memiliki satu pembayaran
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Satu pesanan memiliki satu pengiriman
    public function pengiriman()
    {
        return $this->hasOne(
            Pengiriman::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}