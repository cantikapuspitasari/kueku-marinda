<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

Route::resource('produk', ProdukController::class);
use App\Http\Controllers\PesananController;

Route::resource('pesanan', PesananController::class)->except(['edit', 'update', 'destroy']);
Route::patch('pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengirimanController;

Route::post('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
Route::get('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran.show');

Route::get('pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
Route::patch('pesanan/{idPesanan}/ambil-toko', [PengirimanController::class, 'ambilDariToko'])->name('pengiriman.ambilDariToko');
Route::patch('pesanan/{idPesanan}/terkirim', [PengirimanController::class, 'tandaiTerkirim'])->name('pengiriman.tandaiTerkirim');
use App\Http\Controllers\KategoriController;

Route::resource('kategori', KategoriController::class);

use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Lindungi route yang butuh login
Route::middleware('auth')->group(function () {
    Route::resource('pesanan', PesananController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::post('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
    Route::patch('pesanan/{idPesanan}/ambil-toko', [PengirimanController::class, 'ambilDariToko'])->name('pengiriman.ambilDariToko');
    Route::patch('pesanan/{idPesanan}/terkirim', [PengirimanController::class, 'tandaiTerkirim'])->name('pengiriman.tandaiTerkirim');
});

Route::get('/', function () {
    return view('welcome');
});
