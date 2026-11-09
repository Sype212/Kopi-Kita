<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Redirect root ke dashboard (atau login jika belum auth)
Route::get('/', fn () => redirect()->route('dashboard'));

// --- Auth ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// --- Aplikasi (butuh login) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/transaksi/qris-upload', [TransaksiController::class, 'uploadQris'])->name('transaksi.qris-upload');
    Route::get('/transaksi/{order}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{product}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{product}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.export-csv');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});
