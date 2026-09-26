<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

Route::resource('produk', ProdukController::class);
use App\Http\Controllers\PesananController;

Route::resource('pesanan', PesananController::class)->except(['edit', 'update', 'destroy']);
Route::patch('pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

Route::get('/', function () {
    return view('welcome');
});
