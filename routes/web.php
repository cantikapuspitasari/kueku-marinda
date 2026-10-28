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
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminPesananController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\OwnerMonitoringController;
use App\Http\Controllers\OwnerLaporanController;
use App\Http\Controllers\OwnerAkunController;
use App\Http\Controllers\StrukController;

// ---------- Halaman utama ----------
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('healthcheck', [HealthCheckController::class, 'index'])->name('healthcheck');

// ---------- Auth (bebas diakses siapa saja) ----------
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::get('login-staff', function () {
    return view('auth.login-staff');
})->name('staff.login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ---------- Admin: route "create" HARUS di atas route {produk}/{kategori} ----------
// Kalau tidak, kata "create" terbaca sebagai ID dan masuk ke method show().
Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::get('produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
});

// ---------- Produk & Kategori: boleh dilihat siapa saja (termasuk belum login) ----------
Route::get('produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');

// ---------- Keranjang & Checkout ----------
Route::get('keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// ---------- Pesanan selesai: HARUS di atas route resource pesanan ----------
// Kalau tidak, kata "selesai" terbaca sebagai ID pesanan dan menghasilkan 404.
Route::get('pesanan/selesai', [PesananController::class, 'selesai'])->name('pesanan.selesai');
Route::get('pesanan/detail', [PesananController::class, 'detail'])->name('pesanan.detail');

// ---------- Semua yang sudah login (peran apapun) ----------
Route::middleware('auth')->group(function () {
    Route::resource('pesanan', PesananController::class)->except(['edit', 'update', 'destroy']);
    Route::get('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('pesanan/{idPesanan}/struk', [StrukController::class, 'show'])->name('pesanan.struk');
});

// ---------- Khusus Admin: kelola produk & kategori, verifikasi pesanan ----------
Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::post('produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::put('produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');

    // ----- Panel Admin -----
    Route::redirect('admin', '/admin/dashboard');
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/pesanan', [AdminPesananController::class, 'index'])->name('admin.pesanan');

    // Kelola Produk (route "create" harus di atas route yang memakai {id})
    Route::get('admin/produk', [AdminProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('admin/produk/create', [AdminProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('admin/produk', [AdminProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('admin/produk/{id}/edit', [AdminProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('admin/produk/{id}', [AdminProdukController::class, 'update'])->name('admin.produk.update');
    Route::patch('admin/produk/{id}/stok', [AdminProdukController::class, 'updateStok'])->name('admin.produk.stok');
    Route::delete('admin/produk/{id}', [AdminProdukController::class, 'destroy'])->name('admin.produk.destroy');

    // Kelola Kategori
    Route::get('admin/kategori', [AdminKategoriController::class, 'index'])->name('admin.kategori.index');
    Route::post('admin/kategori', [AdminKategoriController::class, 'store'])->name('admin.kategori.store');
    Route::put('admin/kategori/{id}', [AdminKategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('admin/kategori/{id}', [AdminKategoriController::class, 'destroy'])->name('admin.kategori.destroy');


    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
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

// ---------- Khusus Owner: dashboard, monitoring, laporan, kelola akun staf ----------
Route::middleware(['auth', 'role:OWNER'])->group(function () {
    Route::redirect('owner', '/owner/dashboard');
    Route::get('owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('owner/monitoring', [OwnerMonitoringController::class, 'index'])->name('owner.monitoring');

    // "unduh" harus di atas "laporan" agar tidak tertukar
    Route::get('owner/laporan/unduh', [OwnerLaporanController::class, 'unduh'])->name('owner.laporan.unduh');
    Route::get('owner/laporan', [OwnerLaporanController::class, 'index'])->name('owner.laporan');

    Route::get('owner/akun', [OwnerAkunController::class, 'index'])->name('owner.akun');
    Route::post('owner/akun', [OwnerAkunController::class, 'store'])->name('owner.akun.store');
    Route::put('owner/akun/{id}', [OwnerAkunController::class, 'update'])->name('owner.akun.update');
    Route::patch('owner/akun/{id}/nonaktifkan', [OwnerAkunController::class, 'nonaktifkan'])->name('owner.akun.nonaktifkan');
    Route::patch('owner/akun/{id}/aktifkan', [OwnerAkunController::class, 'aktifkan'])->name('owner.akun.aktifkan');

    // Alamat lama tetap hidup (diarahkan ke halaman baru) supaya link lama tidak 404
    Route::redirect('user', '/owner/akun')->name('user.index');
    Route::redirect('laporan', '/owner/laporan')->name('laporan.index');
});
