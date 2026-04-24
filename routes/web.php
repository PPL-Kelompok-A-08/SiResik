<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
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

        // Admin CRUD Routes
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            // Kategori Sampah
            Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('admin.kategori.store');
            Route::put('/kategori/{kategori}', [AdminController::class, 'updateKategori'])->name('admin.kategori.update');
            Route::delete('/kategori/{kategori}', [AdminController::class, 'destroyKategori'])->name('admin.kategori.destroy');

            // Titik Layanan
            Route::post('/titik-layanan', [AdminController::class, 'storeTitikLayanan'])->name('admin.titik-layanan.store');
            Route::put('/titik-layanan/{titikLayanan}', [AdminController::class, 'updateTitikLayanan'])->name('admin.titik-layanan.update');
            Route::delete('/titik-layanan/{titikLayanan}', [AdminController::class, 'destroyTitikLayanan'])->name('admin.titik-layanan.destroy');

            // Petugas
            Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('admin.petugas.store');
            Route::put('/petugas/{petugas}', [AdminController::class, 'updatePetugas'])->name('admin.petugas.update');
            Route::delete('/petugas/{petugas}', [AdminController::class, 'destroyPetugas'])->name('admin.petugas.destroy');

            // Verifikasi Laporan
            Route::post('/verifikasi-laporan/{permintaan}', [AdminController::class, 'verifikasiLaporan'])->name('admin.verifikasi-laporan');

            // Konfigurasi Poin
            Route::post('/konfigurasi-poin', [AdminController::class, 'updateKonfigurasiPoin'])->name('admin.konfigurasi-poin.update');

            // Reward
            Route::post('/reward', [AdminController::class, 'storeReward'])->name('admin.reward.store');
            Route::put('/reward/{reward}', [AdminController::class, 'updateReward'])->name('admin.reward.update');
            Route::delete('/reward/{reward}', [AdminController::class, 'destroyReward'])->name('admin.reward.destroy');
        });

        Route::get('/kategori', [KategoriSampahController::class, 'index']);
        Route::post('/kategori/hitung', [KategoriSampahController::class, 'hitung']);
        Route::get('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'index'])->name('permintaan-penjemputan.index');
        Route::post('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'store'])->name('permintaan-penjemputan.store');
        Route::get('/permintaan-penjemputan/{permintaanPenjemputan}/success', [PermintaanPenjemputanController::class, 'success'])->name('permintaan-penjemputan.success');

        Route::get('/peta-lokasi', [PetaLokasiController::class, 'masyarakat'])
            ->middleware('role:masyarakat,petugas')
            ->name('peta.lokasi');

        Route::get('/dashboard/admin/peta-titik-layanan', [PetaLokasiController::class, 'admin'])
            ->middleware('role:admin')
            ->name('dashboard.admin.peta');

        Route::get('/api/titik-layanan', [PetaLokasiController::class, 'titikLayananJson'])->name('api.titik-layanan');
    });Route::get('/reward', fn() => view('reward'));
