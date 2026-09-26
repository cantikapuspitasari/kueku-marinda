<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'status_produk',
        'foto_produk',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    // Produk termasuk dalam satu kategori
    public function kategori()
    {
        return $this->belongsTo(
            Kategori::class,
            'id_kategori',
            'id_kategori'
        );
    }

    // Produk dapat muncul pada banyak detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_produk',
            'id_produk'
        );
    }
}