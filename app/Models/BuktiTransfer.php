<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Bukti transfer yang diunggah pembeli. Satu pesanan boleh punya beberapa baris
// (unggah ulang setelah ditolak) sehingga riwayat verifikasi tetap tersimpan.
class BuktiTransfer extends Model
{
    protected $table = 'bukti_transfer';
    protected $primaryKey = 'id_bukti';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'bank',
        'nominal',
        'file_path',
        'nama_asli',
        'status',
        'catatan_admin',
        'id_verifikator',
        'diunggah_pada',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'diunggah_pada'     => 'datetime',
        'diverifikasi_pada' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'id_verifikator', 'id_user');
    }
}
