<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';

    protected $primaryKey = 'id_alamat';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'label_alamat',
        'alamat_lengkap',
        'kota',
        'kode_pos',
    ];

    // Alamat milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Alamat dapat digunakan pada banyak pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_alamat', 'id_alamat');
    }
}