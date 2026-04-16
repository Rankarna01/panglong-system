<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER LOGIN ---
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| ROLE: ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);
    Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);
    Route::resource('stok-masuk', \App\Http\Controllers\Admin\StokMasukController::class);
    Route::resource('supplier', \App\Http\Controllers\Admin\SupplierController::class);
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    // Contoh menu dengan tambahan logic print
    Route::resource('penjualan', \App\Http\Controllers\Admin\PenjualanController::class);
    Route::get('penjualan/{id}/print', [\App\Http\Controllers\Admin\PenjualanController::class, 'print'])->name('penjualan.print');
});

/*
|--------------------------------------------------------------------------
| ROLE: KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kasir\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('penjualan', \App\Http\Controllers\Kasir\PenjualanController::class);
    Route::get('penjualan/{id}/struk', [\App\Http\Controllers\Kasir\PenjualanController::class, 'cetakStruk'])->name('penjualan.struk');
});

/*
|--------------------------------------------------------------------------
| ROLE: STAFF GUDANG ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('barang', \App\Http\Controllers\Staff\BarangController::class)->only(['index', 'show']); // Staff mungkin hanya lihat
    Route::resource('stok-masuk', \App\Http\Controllers\Staff\StokMasukController::class);
    Route::resource('stok-keluar', \App\Http\Controllers\Staff\StokKeluarController::class);
    Route::resource('stok-opname', \App\Http\Controllers\Staff\StokOpnameController::class);
});