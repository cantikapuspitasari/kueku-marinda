
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LacakController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\StaffAkunController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\StrukController;

// ---------- Halaman utama ----------
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('healthcheck', [HealthCheckController::class, 'index'])
    ->name('healthcheck');

// ---------- Auth Pembeli ----------
Route::get('login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('login', [AuthController::class, 'login']);

Route::get('register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('register', [AuthController::class, 'register']);

Route::post('logout', [AuthController::class, 'logout'])
    ->name('logout');

// ---------- Auth Staf ----------
Route::get('staff/login', [AuthController::class, 'showLoginStaff'])
    ->name('staff.login');

Route::post('staff/login', [AuthController::class, 'loginStaff']);

Route::post('staff/logout', [AuthController::class, 'logoutStaff'])
    ->name('staff.logout');

Route::get('login-staff', function () {
    return redirect()->route('staff.login');
});

// ---------- Login Google ----------
Route::get('auth/google', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

// ---------- Produk dan Kategori Publik ----------
Route::get('favorit', function () {
    return view('produk.favorit', [
        'produkJs' => ProdukController::katalogJs()
    ]);
})->name('favorit');

Route::get('produk', [ProdukController::class, 'index'])
    ->name('produk.index');

Route::get('produk/{id}', [ProdukController::class, 'show'])
    ->name('produk.show');

Route::get('kategori', [KategoriController::class, 'index'])
    ->name('kategori.index');

Route::get('kategori/{kategori}', [KategoriController::class, 'show'])
    ->whereNumber('kategori')
    ->name('kategori.show');

// ---------- Keranjang dan Checkout ----------
Route::get('keranjang', [KeranjangController::class, 'index'])
    ->name('keranjang.index');

Route::get('checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

// ---------- Khusus Pembeli ----------
Route::middleware('auth:pembeli')->group(function () {

    Route::get('pesanan/selesai', [PesananController::class, 'selesai'])
        ->name('pesanan.selesai');

    Route::get('pesanan/detail', [PesananController::class, 'detail'])
        ->name('pesanan.detail');

    Route::get('pesanan/lacak/{kode}', [LacakController::class, 'status'])
        ->name('pesanan.lacak');

    Route::resource('pesanan', PesananController::class)
        ->except(['edit', 'update', 'destroy']);

    Route::get('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'show'])
        ->name('pembayaran.show');

    Route::get('pesanan/{idPesanan}/struk', [StrukController::class, 'show'])
        ->name('pesanan.struk');

    // Pesanan Saya dan Profil
    Route::get('pesanan-saya', [PelangganController::class, 'pesanan'])
        ->name('pesanan.saya');

    Route::get('profil', [PelangganController::class, 'profil'])
        ->name('profil');

    Route::get('profil/informasi', [PelangganController::class, 'info'])
        ->name('profil.info');

    Route::put('profil/informasi', [PelangganController::class, 'updateInfo'])
        ->name('profil.info.update');

    Route::get('profil/alamat', [PelangganController::class, 'alamat'])
        ->name('profil.alamat');

    Route::post('profil/alamat', [PelangganController::class, 'tambahAlamat'])
        ->name('profil.alamat.store');

    Route::put('profil/alamat/{id}', [PelangganController::class, 'ubahAlamat'])
        ->name('profil.alamat.update');

    Route::delete('profil/alamat/{id}', [PelangganController::class, 'hapusAlamat'])
        ->name('profil.alamat.destroy');
});

// ---------- Akun Staf ----------
Route::middleware(['auth', 'role:ADMIN,OWNER,KURIR'])->group(function () {

    Route::get('akun-saya', [StaffAkunController::class, 'index'])
        ->name('akun.saya');

    Route::put('akun-saya', [StaffAkunController::class, 'updateInfo'])
        ->name('akun.saya.update');

    Route::put('akun-saya/password', [StaffAkunController::class, 'updatePassword'])
        ->name('akun.saya.password');
});

// ---------- Khusus Admin ----------
Route::middleware(['auth', 'role:ADMIN'])->group(function () {

    // Produk
    Route::get('produk/create', [ProdukController::class, 'create'])
        ->name('produk.create');

    Route::post('produk', [ProdukController::class, 'store'])
        ->name('produk.store');

    Route::get('produk/{produk}/edit', [ProdukController::class, 'edit'])
        ->name('produk.edit');

    Route::put('produk/{produk}', [ProdukController::class, 'update'])
        ->name('produk.update');

    Route::delete('produk/{produk}', [ProdukController::class, 'destroy'])
        ->name('produk.destroy');

    // Kategori
    Route::get('kategori/create', [KategoriController::class, 'create'])
        ->name('kategori.create');

    Route::post('kategori', [KategoriController::class, 'store'])
        ->name('kategori.store');

    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])
        ->name('kategori.update');

    Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])
        ->name('kategori.destroy');

    // Dashboard Admin
    Route::redirect('admin', '/admin/dashboard');

    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('admin/pesanan', [AdminPesananController::class, 'index'])
        ->name('admin.pesanan');

    Route::patch('admin/pesanan/{id}/maju', [AdminPesananController::class, 'maju'])
        ->name('admin.pesanan.maju');

    // Kelola Produk Admin
    Route::get('admin/produk', [AdminProdukController::class, 'index'])
        ->name('admin.produk.index');

    Route::get('admin/produk/create', [AdminProdukController::class, 'create'])
        ->name('admin.produk.create');

    Route::post('admin/produk', [AdminProdukController::class, 'store'])
        ->name('admin.produk.store');

    Route::get('admin/produk/{id}/edit', [AdminProdukController::class, 'edit'])
        ->name('admin.produk.edit');

    Route::put('admin/produk/{id}', [AdminProdukController::class, 'update'])
        ->name('admin.produk.update');

    Route::patch('admin/produk/{id}/stok', [AdminProdukController::class, 'updateStok'])
        ->name('admin.produk.stok');

    Route::delete('admin/produk/{id}', [AdminProdukController::class, 'destroy'])
        ->name('admin.produk.destroy');

    // Kelola Kategori Admin
    Route::get('admin/kategori', [AdminKategoriController::class, 'index'])
        ->name('admin.kategori.index');

    Route::post('admin/kategori', [AdminKategoriController::class, 'store'])
        ->name('admin.kategori.store');

    Route::put('admin/kategori/{id}', [AdminKategoriController::class, 'update'])
        ->name('admin.kategori.update');

    Route::delete('admin/kategori/{id}', [AdminKategoriController::class, 'destroy'])
        ->name('admin.kategori.destroy');

    // Update Status Pesanan
    Route::patch('pesanan/{id}/status', [PesananController::class, 'updateStatus'])
        ->name('pesanan.updateStatus');
});

// ---------- Pembayaran Admin dan Kurir ----------
Route::middleware(['auth', 'role:ADMIN,KURIR'])->group(function () {

    Route::post('pesanan/{idPesanan}/pembayaran', [PembayaranController::class, 'store'])
        ->name('pembayaran.store');
});

// ---------- Pesanan Admin dan Owner ----------
Route::middleware(['auth', 'role:ADMIN,OWNER'])->group(function () {

    Route::get('pesanan-staff', [PesananController::class, 'indexStaff'])
        ->name('pesanan.indexStaff');
});

// ---------- Khusus Kurir ----------
Route::middleware(['auth', 'role:KURIR'])->group(function () {

    Route::redirect('pengiriman', '/kurir/dashboard')
        ->name('pengiriman.index');

    Route::get('kurir/dashboard', [KurirController::class, 'dashboard'])
        ->name('kurir.dashboard');

    Route::get('kurir/pesanan', [KurirController::class, 'pesanan'])
        ->name('kurir.pesanan');

    Route::patch('pesanan/{idPesanan}/ambil-toko', [PengirimanController::class, 'ambilDariToko'])
        ->name('pengiriman.ambilDariToko');

    Route::patch('pesanan/{idPesanan}/mulai-antar', [PengirimanController::class, 'mulaiAntar'])
        ->name('pengiriman.mulaiAntar');

    Route::patch('pesanan/{idPesanan}/terkirim', [PengirimanController::class, 'tandaiTerkirim'])
        ->name('pengiriman.tandaiTerkirim');
});

// ---------- Khusus Owner ----------
Route::middleware(['auth', 'role:OWNER'])->group(function () {

    Route::redirect('owner', '/owner/dashboard');

    Route::get('owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner.dashboard');

    Route::get('owner/monitoring', [OwnerMonitoringController::class, 'index'])
        ->name('owner.monitoring');

    Route::get('owner/laporan/unduh', [OwnerLaporanController::class, 'unduh'])
        ->name('owner.laporan.unduh');

    Route::get('owner/laporan', [OwnerLaporanController::class, 'index'])
        ->name('owner.laporan');

    Route::get('owner/akun', [OwnerAkunController::class, 'index'])
        ->name('owner.akun');

    Route::post('owner/akun', [OwnerAkunController::class, 'store'])
        ->name('owner.akun.store');

    Route::put('owner/akun/{id}', [OwnerAkunController::class, 'update'])
        ->name('owner.akun.update');

    Route::patch('owner/akun/{id}/nonaktifkan', [OwnerAkunController::class, 'nonaktifkan'])
        ->name('owner.akun.nonaktifkan');

    Route::patch('owner/akun/{id}/aktifkan', [OwnerAkunController::class, 'aktifkan'])
        ->name('owner.akun.aktifkan');

    // Alias route lama
    Route::redirect('user', '/owner/akun')
        ->name('user.index');

    Route::redirect('laporan', '/owner/laporan')
        ->name('laporan.index');
});
