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

    // ---------- Relasi ----------

<<<<<<< HEAD
    // Relasi ke user
    public function user()
=======
    public function pembeli()
>>>>>>> origin/feature/backend
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

<<<<<<< HEAD
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
=======
>>>>>>> origin/feature/backend
    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'id_alamat');
    }

<<<<<<< HEAD
    // Relasi detail pesanan
=======
>>>>>>> origin/feature/backend
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

<<<<<<< HEAD
    // Relasi pembayaran
=======
>>>>>>> origin/feature/backend
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

<<<<<<< HEAD
    // Relasi pengiriman
=======
>>>>>>> origin/feature/backend
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pesanan', 'id_pesanan');
    }
}
