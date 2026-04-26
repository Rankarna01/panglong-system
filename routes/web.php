<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER LOGIN ---
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| ROOT ROUTE (Solusi 404)
|--------------------------------------------------------------------------
| Mengalihkan user yang mengakses domain utama langsung ke halaman login.
*/
Route::get('/', function () {
    return redirect()->route('login');
});

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
    
    Route::resource('barang', \App\Http\Controllers\Admin\ProductController::class);
    Route::post('barang/{id}/konversi', [\App\Http\Controllers\Admin\ProductController::class, 'storeConversion'])->name('barang.konversi.store');
    Route::delete('konversi/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyConversion'])->name('barang.konversi.destroy');
    Route::resource('kategori', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('satuan', \App\Http\Controllers\Admin\UnitController::class);
    Route::resource('supplier', \App\Http\Controllers\Admin\SupplierController::class);
    Route::resource('stok-masuk', \App\Http\Controllers\Admin\StockInController::class)->only(['index']);
    Route::resource('stok-keluar', \App\Http\Controllers\Admin\StockOutController::class)->only(['index']);
    Route::get('laporan-stok-opname', [\App\Http\Controllers\Admin\StockOpnameController::class, 'index'])->name('laporan.stok-opname');
    Route::get('laporan-stok', [\App\Http\Controllers\Admin\StockReportController::class, 'index'])->name('laporan.stok');
    Route::get('laporan-penjualan', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('laporan.penjualan');
    Route::get('riwayat-penjualan', [\App\Http\Controllers\Admin\SaleHistoryController::class, 'index'])->name('riwayat.penjualan');
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('rak', \App\Http\Controllers\Admin\RackController::class)->except(['create', 'show', 'edit']);
    Route::get('monitoring-rak', [\App\Http\Controllers\Admin\RackMonitorController::class, 'index'])->name('rak.monitoring');
    Route::post('monitoring-rak/{id}/allocate', [\App\Http\Controllers\Admin\RackMonitorController::class, 'allocate'])->name('rak.allocate');
});

/*
|--------------------------------------------------------------------------
| ROLE: KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', function() { return redirect()->route('kasir.pos.index'); })->name('dashboard');

    Route::resource('pos', \App\Http\Controllers\Kasir\PosController::class)->only(['index', 'store']);
    
    // 👇 Tambahkan ini untuk Cetak Struk 👇
    Route::get('pos/print/{id}', [\App\Http\Controllers\Kasir\PosController::class, 'print'])->name('pos.print');
    
    Route::get('riwayat-penjualan', [\App\Http\Controllers\Kasir\SaleHistoryController::class, 'index'])->name('riwayat-penjualan');
});

/*
|--------------------------------------------------------------------------
| ROLE: STAFF GUDANG ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('stok-masuk', \App\Http\Controllers\Staff\StockInController::class)->except(['create', 'edit', 'update', 'show']);
    Route::resource('stok-keluar', \App\Http\Controllers\Staff\StockOutController::class)->except(['create', 'edit', 'update', 'show']);
    Route::resource('stok-opname', \App\Http\Controllers\Staff\StockOpnameController::class)->except(['create', 'edit', 'update', 'show']);
    
    // 👇 UBAH BAGIAN INI (Hapus kata gudang. di dalam name) 👇
    Route::get('cek-barang', [\App\Http\Controllers\Staff\ProductCheckController::class, 'index'])->name('cek-barang.index');
    Route::get('cek-barang/{id}', [\App\Http\Controllers\Staff\ProductCheckController::class, 'show'])->name('cek-barang.show');
});