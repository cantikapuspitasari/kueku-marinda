<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    // Metode pembayaran yang dipilih pembeli saat checkout
    const METODE_PEMBAYARAN = [
        'TUNAI' => 'Tunai (Cash)',
        'QRIS'  => 'QRIS',
        'TRANSFER' => 'Transfer Bank',
    ];

    // Label status pembayaran (kolom status_pembayaran)
    const STATUS_PEMBAYARAN = [
        'BELUM_BAYAR'         => 'Belum Bayar',
        'MENUNGGU_VERIFIKASI' => 'Menunggu Verifikasi',
        'DITOLAK'             => 'Bukti Ditolak',
        'LUNAS'               => 'Lunas',
    ];

    // Rekening tujuan Transfer Bank. KHUSUS SIMULASI: bukan rekening sungguhan.
    const BANK_TRANSFER = [
        'BCA'     => ['nama' => 'Bank BCA',     'rekening' => '8888 0011 2233', 'atas_nama' => 'KUEKU MARINDA'],
        'BRI'     => ['nama' => 'Bank BRI',     'rekening' => '8888 0044 5566', 'atas_nama' => 'KUEKU MARINDA'],
        'BNI'     => ['nama' => 'Bank BNI',     'rekening' => '8888 0077 8899', 'atas_nama' => 'KUEKU MARINDA'],
        'MANDIRI' => ['nama' => 'Bank Mandiri', 'rekening' => '8888 0099 0011', 'atas_nama' => 'KUEKU MARINDA'],
    ];

    protected $fillable = [
    'kode_pesanan',
    'id_pembeli',
    'id_alamat',
    'tanggal_pesan',
    'tanggal_ambil',
    'jam_ambil',
    'jenis_pengambilan',
    'metode_pembayaran',
    'bank_tujuan',
    'status_pembayaran',
    'waktu_bayar',
    'status_pesanan',
    'total_harga',
];

    protected $casts = [
        'waktu_bayar' => 'datetime',
    ];

    // Pesanan ini dibayar dengan QRIS?
    public function isQris(): bool
    {
        return $this->metode_pembayaran === 'QRIS';
    }

    // Pesanan ini dibayar dengan Transfer Bank?
    public function isTransfer(): bool
    {
        return $this->metode_pembayaran === 'TRANSFER';
    }

    // QRIS dan Transfer Bank dibayar pembeli sendiri (bukan dicatat staf seperti tunai)
    public function isNonTunai(): bool
    {
        return in_array($this->metode_pembayaran, ['QRIS', 'TRANSFER'], true);
    }

    // Status pembayaran dengan nilai bawaan BELUM_BAYAR
    public function statusBayar(): string
    {
        return $this->status_pembayaran ?: 'BELUM_BAYAR';
    }

    // Pembeli boleh mengunggah bukti transfer (pertama kali, atau ulang setelah ditolak)
    public function bisaUnggahBukti(): bool
    {
        return $this->isTransfer()
            && $this->status_pesanan !== 'DIBATALKAN'
            && in_array($this->statusBayar(), ['BELUM_BAYAR', 'DITOLAK'], true);
    }

    // Pesanan yang dihitung sebagai omzet: tidak batal, dan QRIS/Transfer hanya bila sudah LUNAS.
    // Transfer yang belum diverifikasi Admin (atau ditolak) tidak dianggap lunas.
    public function scopeTerhitungOmzet($q)
    {
        return $q->where('status_pesanan', '!=', 'DIBATALKAN')
            ->where(function ($w) {
                $w->where('metode_pembayaran', 'TUNAI')
                  ->orWhere('status_pembayaran', 'LUNAS');
            });
    }

    public function buktiTransfer()
    {
        return $this->hasMany(BuktiTransfer::class, 'id_pesanan', 'id_pesanan')
            ->orderByDesc('id_bukti');
    }

    // Sudah dibayar = QRIS berstatus LUNAS, atau tunai yang sudah dicatat staf
    // (data lama sebelum ada kolom status_pembayaran dikenali lewat tabel pembayaran)
    public function sudahDibayar(): bool
    {
        return $this->status_pembayaran === 'LUNAS'
            || $this->pembayaran()->exists();
    }

    public function pembeli()
    {
        return $this->belongsTo(
            Pembeli::class,
            'id_pembeli',
            'id_pembeli'
        );
    }

    public function alamat()
    {
        return $this->belongsTo(
            Alamat::class,
            'id_alamat',
            'id_alamat'
        );
    }

    public function detailPesanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pengiriman()
    {
        return $this->hasOne(
            Pengiriman::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
