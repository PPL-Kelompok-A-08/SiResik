<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriSampahController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PetaLokasiController;
use App\Http\Controllers\PermintaanPenjemputanController;
use App\Http\Controllers\RiwayatLayananController;
use App\Http\Controllers\PetugasController;

// =========================================================================
// MODIFIKASI JALUR UTAMA REACT (SIRESIK)
// =========================================================================
// Kami mengalihkan rute utama '/' agar langsung memuat berkas welcome.blade.php
// tempat komponen dashboard React, simulator, dan tombol login berada.
Route::get('/', function () {
    return view('welcome');
});

// Jika sewaktu-waktu dosen atau kelompok Anda ingin melihat Landing Page lama:
Route::get('/landing-lama', [LandingPageController::class, 'index'])->name('landing.lama');

// Autentikasi GUEST (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

// Autentikasi AUTH (Harus Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Global Dashboard Redirect & Notifikasi
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Kategori & API global didalam aplikasi
    Route::get('/kategori', [KategoriSampahController::class, 'index']);
    Route::post('/kategori/hitung', [KategoriSampahController::class, 'hitung']);
    Route::get('/api/titik-layanan', [PetaLokasiController::class, 'titikLayananJson'])->name('api.titik-layanan');

    // === ROLE: MASYARAKAT ===
    Route::middleware('role:masyarakat')->group(function () {
        Route::get('/dashboard/masyarakat', [DashboardController::class, 'masyarakat'])->name('dashboard.masyarakat');
        
        // Poin & Reward
        Route::get('/poin', [App\Http\Controllers\RiwayatpoinpenggunaController::class, 'index'])->name('poin.index');
        Route::get('/reward', [App\Http\Controllers\PenukaranRewardController::class, 'index'])->name('reward.index');
        Route::post('/reward/{id}/redeem', [App\Http\Controllers\PenukaranRewardController::class, 'redeem'])->name('reward.redeem');

        // Permintaan Penjemputan Sampah
        Route::get('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'index'])->name('permintaan-penjemputan.index');
        Route::post('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'store'])->name('permintaan-penjemputan.store');
        Route::get('/permintaan-penjemputan/{permintaanPenjemputan}/success', [PermintaanPenjemputanController::class, 'success'])->name('permintaan-penjemputan.success');

        // Usulan Titik Layanan Baru
        Route::get('/peta-lokasi/usulan-titik', [PetaLokasiController::class, 'usulanForm'])->name('peta.usulan-titik');
        Route::post('/peta-lokasi/usulan-titik', [PetaLokasiController::class, 'storeUsulan'])->name('peta.usulan-titik.store');

        // Riwayat Layanan
        Route::prefix('riwayat-layanan')->group(function () {
            Route::get('/', [RiwayatLayananController::class, 'index'])->name('riwayat-layanan.index');
            Route::get('/{permintaanPenjemputan}', [RiwayatLayananController::class, 'show'])->name('riwayat-layanan.show');
        });
    });

    // === ROLE: PETUGAS ===
    Route::middleware('role:petugas')->group(function () {
        Route::get('/dashboard/petugas', [DashboardController::class, 'petugas'])->name('dashboard.petugas');
    });

    // === MULTIROLE: PETUGAS & ADMIN (Tugas Lapangan) ===
    Route::middleware('role:petugas,admin')->prefix('petugas')->group(function () {
        Route::get('/riwayat', [PetugasController::class, 'riwayat'])->name('petugas.riwayat');
        Route::get('/bukti/{permintaanPenjemputan}', [PetugasController::class, 'showBukti'])->name('petugas.bukti.show');
        Route::post('/bukti/{permintaanPenjemputan}', [PetugasController::class, 'uploadBukti'])->name('petugas.bukti.upload');
    });

    // === MULTIROLE: MASYARAKAT & PETUGAS (Akses Peta) ===
    Route::middleware('role:masyarakat,petugas')->group(function () {
        Route::get('/peta-lokasi', [PetaLokasiController::class, 'masyarakat'])->name('peta.lokasi');
    });

    // === ROLE: ADMIN ===
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::post('/dashboard/admin/permintaan/{permintaanPenjemputan}/schedule', [DashboardController::class, 'schedule'])->name('dashboard.admin.schedule');
        Route::put('/permintaan-penjemputan/{permintaanPenjemputan}/status', [PermintaanPenjemputanController::class, 'updateStatus'])->name('permintaan-penjemputan.update-status');

        // Persetujuan Usulan Peta
        Route::post('/dashboard/admin/usulan-titik/{usulan}/approve', [PetaLokasiController::class, 'approveUsulan'])->name('dashboard.admin.usulan.approve');
        Route::post('/dashboard/admin/usulan-titik/{usulan}/reject', [PetaLokasiController::class, 'rejectUsulan'])->name('dashboard.admin.usulan.reject');

        // Admin CRUD Management
        Route::prefix('admin')->group(function () {
            // Kategori Sampah
            Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('admin.kategori.store');
            Route::put('/kategori/{kategori}', [AdminController::class, 'updateKategori'])->name('admin.kategori.update');
            Route::delete('/kategori/{kategori}', [AdminController::class, 'destroyKategori'])->name('admin.kategori.destroy');

            // Titik Layanan
            Route::post('/titik-layanan', [AdminController::class, 'storeTitikLayanan'])->name('admin.titik-layanan.store');
            Route::put('/titik-layanan/{titikLayanan}', [AdminController::class, 'updateTitikLayanan'])->name('admin.titik-layanan.update');
            Route::delete('/titik-layanan/{titikLayanan}', [AdminController::class, 'destroyTitikLayanan'])->name('admin.titik-layanan.destroy');

            // Data Petugas
            Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('admin.petugas.store');
            Route::put('/petugas/{petugas}', [AdminController::class, 'updatePetugas'])->name('admin.petugas.update');
            Route::delete('/petugas/{petugas}', [AdminController::class, 'destroyPetugas'])->name('admin.petugas.destroy');

            // Verifikasi & Konfigurasi
            Route::post('/verifikasi-laporan/{permintaan}', [AdminController::class, 'verifikasiLaporan'])->name('admin.verifikasi-laporan');
            Route::post('/konfigurasi-poin', [AdminController::class, 'updateKonfigurasiPoin'])->name('admin.konfigurasi-poin.update');

            // Reward Management
            Route::post('/reward', [AdminController::class, 'storeReward'])->name('admin.reward.store');
            Route::put('/reward/{reward}', [AdminController::class, 'updateReward'])->name('admin.reward.update');
            Route::delete('/reward/{reward}', [AdminController::class, 'destroyReward'])->name('admin.reward.destroy');

            // Zona Layanan (Area Cakupan)
            Route::post('/zona-layanan', [AdminController::class, 'storeZonaLayanan'])->name('admin.zona-layanan.store');
            Route::put('/zona-layanan/{zonaLayanan}', [AdminController::class, 'updateZonaLayanan'])->name('admin.zona-layanan.update');
            Route::delete('/zona-layanan/{zonaLayanan}', [AdminController::class, 'destroyZonaLayanan'])->name('admin.zona-layanan.destroy');
        });
    });
});


use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Admin\AdminKegiatanController;
use App\Http\Controllers\Admin\AdminPengumumanController;

// --- RUTE HALAMAN PUBLIK / WARGA ---
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->name('kegiatan.show');
Route::post('/kegiatan/{id}/daftar', [KegiatanController::class, 'daftar'])->name('kegiatan.daftar')->middleware('auth');

Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');

// --- RUTE MANAJEMEN SISTEM ADMINISTRATOR ---
// Catatan: Ganti middleware 'role:admin' sesuai dengan middleware otorisasi admin milik kelompok Anda
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD Kegiatan
    Route::resource('kegiatan', AdminKegiatanController::class);
    // CRUD Pengumuman
    Route::resource('pengumuman', AdminPengumumanController::class)->except(['show']);
});