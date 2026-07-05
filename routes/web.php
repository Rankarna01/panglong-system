<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER LOGIN ---
use App\Http\Controllers\Auth\LoginController;

// --- CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\StockInController as AdminStockInController;
use App\Http\Controllers\Admin\StockOutController as AdminStockOutController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\SaleHistoryController as AdminSaleHistoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\WarehouseMonitorController;
use App\Http\Controllers\Admin\SettingController;

// --- CONTROLLER KASIR ---
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\SaleHistoryController as KasirSaleHistoryController;

// --- CONTROLLER STAFF GUDANG ---
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\StockInController as StaffStockInController;
use App\Http\Controllers\Staff\StockOutController as StaffStockOutController;
use App\Http\Controllers\Staff\ProductCheckController;
use App\Http\Controllers\Staff\SupplierController as StaffSupplierController;

/*
|--------------------------------------------------------------------------
| ROOT ROUTE (Solusi 404)
|--------------------------------------------------------------------------
| Mengalihkan user yang mengakses domain utama langsung ke halaman login.
*/
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'kasir') return redirect()->route('kasir.dashboard');
        if ($role === 'staff') return redirect()->route('gudang.dashboard');
    }
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
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('barang', ProductController::class);
    Route::post('barang/{id}/konversi', [ProductController::class, 'storeConversion'])->name('barang.konversi.store');
    Route::delete('konversi/{id}', [ProductController::class, 'destroyConversion'])->name('barang.konversi.destroy');
    Route::resource('kategori', CategoryController::class);
    Route::resource('satuan', UnitController::class);
    Route::resource('diskon', AdminDiscountController::class)->except(['create', 'show', 'edit']);
    Route::resource('supplier', AdminSupplierController::class);
    Route::resource('stok-masuk', AdminStockInController::class)->only(['index']);
    Route::get('stok-masuk/export', [AdminStockInController::class, 'export'])->name('stok-masuk.export');
    Route::resource('stok-keluar', AdminStockOutController::class)->only(['index']);
    Route::get('stok-keluar/export', [AdminStockOutController::class, 'export'])->name('stok-keluar.export');
    Route::get('laporan-stok', [StockReportController::class, 'index'])->name('laporan.stok');
    Route::get('laporan-stok/export', [StockReportController::class, 'export'])->name('laporan.stok.export');
    Route::get('laporan-penjualan', [SalesReportController::class, 'index'])->name('laporan.penjualan');
    Route::get('laporan-penjualan/export', [SalesReportController::class, 'export'])->name('laporan.penjualan.export');
    Route::get('riwayat-penjualan', [AdminSaleHistoryController::class, 'index'])->name('riwayat.penjualan');
    Route::resource('user', UserController::class);
    Route::resource('gudang', WarehouseController::class)->except(['create', 'show', 'edit']);
    Route::get('monitoring-gudang', [WarehouseMonitorController::class, 'index'])->name('gudang.monitoring');
    Route::post('monitoring-gudang/{id}/allocate', [WarehouseMonitorController::class, 'allocate'])->name('gudang.allocate');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| ROLE: KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', function() { return redirect()->route('kasir.pos.index'); })->name('dashboard');

    Route::resource('pos', PosController::class)->only(['index', 'store']);
    
    // 👇 Tambahkan ini untuk Cetak Struk 👇
    Route::get('pos/print/{id}', [PosController::class, 'print'])->name('pos.print');
    
    Route::get('riwayat-penjualan', [KasirSaleHistoryController::class, 'index'])->name('riwayat-penjualan');
});

/*
|--------------------------------------------------------------------------
| ROLE: STAFF GUDANG ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::resource('stok-masuk', StaffStockInController::class)->except(['create', 'show']);
    Route::resource('stok-keluar', StaffStockOutController::class)->except(['create', 'edit', 'update', 'show']);
    
    // 👇 UBAH BAGIAN INI (Hapus kata gudang. di dalam name) 👇
    Route::get('cek-barang', [ProductCheckController::class, 'index'])->name('cek-barang.index');
    Route::get('cek-barang/{id}', [ProductCheckController::class, 'show'])->name('cek-barang.show');
    
    Route::get('supplier', [StaffSupplierController::class, 'index'])->name('supplier.index');
});