<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePesananRequest;

class PesananController extends Controller
{
    // Menampilkan daftar pesanan (beda isi tergantung role: pembeli lihat punya sendiri, admin lihat semua)
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'PEMBELI') {
            $pesanan = Pesanan::with('detailPesanan.produk')
                ->where('id_user', $user->id_user)
                ->orderBy('tanggal_pesan', 'desc')
                ->get();
        } else {
            // Admin/Owner lihat semua pesanan
            $pesanan = Pesanan::with(['detailPesanan.produk', 'pembeli'])
                ->orderBy('tanggal_pesan', 'desc')
                ->get();
        }

        return view('pesanan.index', compact('pesanan'));
    }

    // Form buat pesanan baru
    public function create()
    {
        $produk = Produk::where('status_produk', 'TERSEDIA')->get();
        return view('pesanan.create', compact('produk'));
    }

    // Simpan pesanan baru + detail item-nya sekaligus
    // S2-05: validasi input sudah dipindahkan ke StorePesananRequest (termasuk aturan
    // tanggal tidak boleh masa lalu dan alamat wajib untuk delivery)
    public function store(StorePesananRequest $request)
    {
        $validated = $request->validated();

        try {
            // Simpan pesanan + detail dalam satu transaction, biar kalau ada yang gagal, semua dibatalkan (rollback)
            $pesanan = DB::transaction(function () use ($validated) {

                $totalHarga = 0;
                $items = [];

                foreach ($validated['items'] as $item) {
                    // S2-06: lockForUpdate() mengunci baris produk ini sampai transaksi selesai.
                    // Pembeli lain yang coba pesan produk yang sama HARUS menunggu giliran,
                    // sehingga stok tidak pernah dibaca dua kali sebelum sempat dikurangi (anti-oversell).
                    $produk = Produk::where('id_produk', $item['id_produk'])
                        ->lockForUpdate()
                        ->first();

                    if (!$produk) {
                        throw new \Exception("Produk tidak ditemukan");
                    }
                    if ($produk->status_produk !== 'TERSEDIA') {
                        throw new \Exception("Produk {$produk->nama_produk} sedang tidak tersedia");
                    }
                    if ($produk->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$produk->nama_produk} tidak cukup (tersisa {$produk->stok})");
                    }

                    $subtotal = $produk->harga * $item['jumlah'];
                    $totalHarga += $subtotal;

                    $items[] = [
                        'id_produk' => $produk->id_produk,
                        'jumlah'    => $item['jumlah'],
                        'subtotal'  => $subtotal,
                    ];
                }

                // Bikin kode pesanan otomatis: KM-YYYYMMDD-XXX
                $kodePesanan = 'KM-' . now()->format('Ymd') . '-' . str_pad(Pesanan::count() + 1, 3, '0', STR_PAD_LEFT);

                $pesananBaru = Pesanan::create([
                    'kode_pesanan'      => $kodePesanan,
                    'id_user'           => Auth::id(),
                    'id_alamat'         => $validated['id_alamat'] ?? null,
                    'tanggal_ambil'     => $validated['tanggal_a