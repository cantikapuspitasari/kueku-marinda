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
        'id_pembeli',
        'id_alamat',
        'tanggal_pesan',
        'tanggal_ambil',
        'jenis_pengambilan',
        'status_pesanan',
        'total_harga',
    ];

    // ---------- Relasi ----------

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'id_alamat');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pesanan', 'id_pesanan');
    }
}