<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HealthCheckController;

// ---------- Halaman utama ----------
Route::get('/', function () {
    return view('welcome');
});

Route::get('healthcheck', [HealthCheckController::class, 'index'])->name('healthcheck');

// ---------- Auth Pembeli (publik) ----------
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ---------- Auth Staf (Admin/Owner/Kurir) ----------
Route::get('staff/login', [AuthController::class, 'showLoginStaff'])->name('staff.login');
Route::post('staff/login', [AuthController::class, 'loginStaff']);
Route::post('staff/logout', [AuthController::class, 'logoutStaff'])->name('staff.logout');


// ---------- Produk & Kategori: boleh dilihat siapa saja (termasuk belum login) ----------
Route::get('produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');

Route::middleware('auth:pembeli')->group(function () {
    Route::resource('pesanan', PesananController::class)->except(['edit', 'update', 'destroy']);
    Route::get('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran.show');
});


// ---------- Khusus Admin: kelola produk & kategori, verifikasi pesanan ----------
Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::post('produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::put('produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');

    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');

    Route::patch('pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::post('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
});

// ---------- Khusus Kurir: pengiriman ----------
Route::middleware(['auth', 'role:KURIR'])->group(function () {
    Route::get('pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
    Route::patch('pesanan/{idPesanan}/ambil-toko', [PengirimanController::class, 'ambilDariToko'])->name('pengiriman.ambilDariToko');
    Route::patch('pesanan/{idPesanan}/terkirim', [PengirimanController::class, 'tandaiTerkirim'])->name('pengiriman.tandaiTerkirim');
});

// ---------- Khusus Owner: kelola akun Admin & laporan penjualan ----------
Route::middleware(['auth', 'role:OWNER'])->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::patch('user/{id}/nonaktifkan', [UserController::class, 'nonaktifkan'])->name('user.nonaktifkan');
    Route::patch('user/{id}/aktifkan', [UserController::class, 'aktifkan'])->name('user.aktifkan');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});