<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembeli;
use App\Models\User;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    public $timestamps = false;

    protected $fillable = [
        'kode_pesanan',
        'id_pembeli',
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

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    // Relasi ke pembeli
    public function pembeli()
    {
        return $this->belongsTo(
            Pembeli::class,
            'id_pembeli',
            'id_pembeli'
        );
    }

    // Relasi alamat
    public function alamat()
    {
        return $this->belongsTo(
            Alamat::class,
            'id_alamat',
            'id_alamat'
        );
    }

    // Relasi detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi pembayaran
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi pengiriman
    public function pengiriman()
    {
        return $this->hasOne(
            Pengiriman::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
