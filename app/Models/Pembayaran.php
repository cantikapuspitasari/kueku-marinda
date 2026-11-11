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
<<<<<<< HEAD
        'id_user',            // staf yang menerima pembayaran (admin atau kurir)
=======
        'id_user',
>>>>>>> origin/feature/backend
        'jumlah_bayar',
        'tempat_pembayaran',  // TOKO atau ALAMAT_PEMBELI
        'tanggal_bayar',
    ];

<<<<<<< HEAD
    protected $casts = [
        'jumlah_bayar'  => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

=======
>>>>>>> origin/feature/backend
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

<<<<<<< HEAD
    // Staf penerima pembayaran (tabel users, kolom id_user)
=======
    // Staf (Admin saat pickup, atau Kurir saat delivery) yang menerima uang
>>>>>>> origin/feature/backend
    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
