<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermintaanPenjemputanController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\KategoriSampahController;

Route::get('/', [LandingPageController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/dashboard/masyarakat', [DashboardController::class, 'masyarakat'])->middleware('role:masyarakat')->name('dashboard.masyarakat');
    Route::get('/dashboard/petugas', [DashboardController::class, 'petugas'])->middleware('role:petugas')->name('dashboard.petugas');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->middleware('role:admin')->name('dashboard.admin');
    Route::post('/dashboard/admin/permintaan/{permintaanPenjemputan}/schedule', [DashboardController::class, 'schedule'])->middleware('role:admin')->name('dashboard.admin.schedule');

    Route::get('/kategori', [KategoriSampahController::class, 'index']);
    Route::post('/kategori/hitung', [KategoriSampahController::class, 'hitung']);
    Route::get('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'index'])->name('permintaan-penjemputan.index');
    Route::post('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'store'])->name('permintaan-penjemputan.store');
    Route::get('/permintaan-penjemputan/{permintaanPenjemputan}/success', [PermintaanPenjemputanController::class, 'success'])->name('permintaan-penjemputan.success');
});

Route::get('/maps', fn() => view('maps'));
Route::get('/reward', fn() => view('reward'));
