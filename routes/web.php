<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermintaanPenjemputanController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\KategoriSampahController;
use App\Http\Controllers\PetaLokasiController;

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

    Route::get('/peta-lokasi', [PetaLokasiController::class, 'masyarakat'])
        ->middleware('role:masyarakat,petugas')
        ->name('peta.lokasi');

    Route::get('/peta-lokasi/usulan-titik', [PetaLokasiController::class, 'usulanForm'])
        ->middleware('role:masyarakat')
        ->name('peta.usulan-titik');
    Route::post('/peta-lokasi/usulan-titik', [PetaLokasiController::class, 'storeUsulan'])
        ->middleware('role:masyarakat')
        ->name('peta.usulan-titik.store');

    Route::get('/dashboard/admin/peta-titik-layanan', [PetaLokasiController::class, 'admin'])
        ->middleware('role:admin')
        ->name('dashboard.admin.peta');
    Route::post('/dashboard/admin/usulan-titik/{usulan}/approve', [PetaLokasiController::class, 'approveUsulan'])
        ->middleware('role:admin')
        ->name('dashboard.admin.usulan.approve');
    Route::post('/dashboard/admin/usulan-titik/{usulan}/reject', [PetaLokasiController::class, 'rejectUsulan'])
        ->middleware('role:admin')
        ->name('dashboard.admin.usulan.reject');

    Route::get('/api/titik-layanan', [PetaLokasiController::class, 'titikLayananJson'])->name('api.titik-layanan');
});

Route::get('/reward', fn() => view('reward'));
