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

    // Poin & Reward (Masyarakat)
    Route::get('/poin', [App\Http\Controllers\RiwayatpoinpenggunaController::class, 'index'])
        ->middleware('role:masyarakat')
        ->name('poin.index');
    Route::get('/reward', [App\Http\Controllers\PenukaranRewardController::class, 'index'])
        ->middleware('role:masyarakat')
        ->name('reward.index');
    Route::post('/reward/{id}/redeem', [App\Http\Controllers\PenukaranRewardController::class, 'redeem'])
        ->middleware('role:masyarakat')
        ->name('reward.redeem');

    // Admin CRUD routes
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

        // Zona Layanan (Area Cakupan)
        Route::post('/zona-layanan', [AdminController::class, 'storeZonaLayanan'])->name('admin.zona-layanan.store');
        Route::put('/zona-layanan/{zonaLayanan}', [AdminController::class, 'updateZonaLayanan'])->name('admin.zona-layanan.update');
        Route::delete('/zona-layanan/{zonaLayanan}', [AdminController::class, 'destroyZonaLayanan'])->name('admin.zona-layanan.destroy');

        // Jadwal Operasional
        Route::get('/titik-layanan/{titikLayanan}/jadwal', [App\Http\Controllers\JadwalOperasionalController::class, 'index'])->name('admin.jadwal.index');
        Route::post('/titik-layanan/{titikLayanan}/jadwal', [App\Http\Controllers\JadwalOperasionalController::class, 'store'])->name('admin.jadwal.store');
        Route::put('/jadwal/{jadwal}', [App\Http\Controllers\JadwalOperasionalController::class, 'update'])->name('admin.jadwal.update');
        Route::delete('/jadwal/{jadwal}', [App\Http\Controllers\JadwalOperasionalController::class, 'destroy'])->name('admin.jadwal.destroy');
    });

    // Kategori (akses dalam sistem)
    Route::get('/kategori', [KategoriSampahController::class, 'index']);
    Route::post('/kategori/hitung', [KategoriSampahController::class, 'hitung']);
    Route::get('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'index'])->name('permintaan-penjemputan.index');
    Route::post('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'store'])->name('permintaan-penjemputan.store');
    Route::get('/permintaan-penjemputan/{permintaanPenjemputan}/success', [PermintaanPenjemputanController::class, 'success'])->name('permintaan-penjemputan.success');
});

Route::get('/maps', fn() => view('maps'));
Route::get('/reward', fn() => view('reward'));
