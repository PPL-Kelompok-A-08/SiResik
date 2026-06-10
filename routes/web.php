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
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RiwayatLayananController;
use App\Http\Controllers\SampahLiarController;
use App\Http\Controllers\StatusLayananController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Admin\AdminKegiatanController;
use App\Http\Controllers\Admin\AdminPengumumanController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\Admin\AdminEdukasiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Ekosistem Sistem Informasi SiResik
|--------------------------------------------------------------------------
*/

// =========================================================================
// JALUR UTAMA REACT (SIRESIK) & LANDING PAGE
// =========================================================================
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/landing-lama', [LandingPageController::class, 'index'])->name('landing.lama');

// =========================================================================
// RUTE HALAMAN PUBLIK / WARGA (TANPA SYARAT LOGIN)
// =========================================================================

// Fitur PBI 23: Katalog Kegiatan & Berita Mading Pengumuman
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->name('kegiatan.show');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');

// Fitur PBI 24: Katalog Portal Edukasi Lingkungan (SEO Friendly Slug)
Route::get('/edukasi', [EdukasiController::class, 'index'])->name('edukasi.index');
Route::get('/edukasi/{slug}', [EdukasiController::class, 'show'])->name('edukasi.show');


// =========================================================================
// GUEST MIDDLEWARE (HANYA UNTUK YANG BELUM LOGIN)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});


// =========================================================================
// AUTH MIDDLEWARE (WAJIB LOGIN TERLEBIH DAHULU)
// =========================================================================
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Pengalihan Beranda Global & Notifikasi Sistem
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Kategori Sampah & API Peta Lokasi
    Route::get('/kategori', [KategoriSampahController::class, 'index']);
    Route::post('/kategori/hitung', [KategoriSampahController::class, 'hitung']);
    Route::get('/api/titik-layanan', [PetaLokasiController::class, 'titikLayananJson'])->name('api.titik-layanan');

    // ---------------------------------------------------------------------
    // === GRUP OTORISASI ROLE: MASYARAKAT ===
    // ---------------------------------------------------------------------
    Route::middleware('role:masyarakat')->group(function () {
        Route::get('/dashboard/masyarakat', [DashboardController::class, 'masyarakat'])->name('dashboard.masyarakat');
        
        // Alur Komitmen Kuota PBI 23
        Route::post('/kegiatan/{id}/daftar', [KegiatanController::class, 'daftar'])->name('kegiatan.daftar');

        // Poin Tabungan & Penukaran Reward Warga
        Route::get('/poin', [App\Http\Controllers\RiwayatpoinpenggunaController::class, 'index'])->name('poin.index');
        Route::get('/reward', [App\Http\Controllers\PenukaranRewardController::class, 'index'])->name('reward.index');
        Route::post('/reward/{id}/redeem', [App\Http\Controllers\PenukaranRewardController::class, 'redeem'])->name('reward.redeem');

        // Formulir Pengajuan Penjemputan Sampah Mandiri
        Route::get('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'index'])->name('permintaan-penjemputan.index');
        Route::post('/permintaan-penjemputan', [PermintaanPenjemputanController::class, 'store'])->name('permintaan-penjemputan.store');
        Route::get('/permintaan-penjemputan/{permintaanPenjemputan}/success', [PermintaanPenjemputanController::class, 'success'])->name('permintaan-penjemputan.success');

        // Pengusulan Titik Lokasi Baru di Peta
        Route::get('/peta-lokasi/usulan-titik', [PetaLokasiController::class, 'usulanForm'])->name('peta.usulan-titik');
        Route::post('/peta-lokasi/usulan-titik', [PetaLokasiController::class, 'storeUsulan'])->name('peta.usulan-titik.store');

        // Pemantauan Status & Riwayat Layanan Warga
        Route::get('/status-layanan', [StatusLayananController::class, 'index'])->name('status-layanan.index');
        Route::prefix('riwayat-layanan')->group(function () {
            Route::get('/', [RiwayatLayananController::class, 'index'])->name('riwayat-layanan.index');
            Route::get('/{permintaanPenjemputan}', [RiwayatLayananController::class, 'show'])->name('riwayat-layanan.show');
        });
    });

    // ---------------------------------------------------------------------
    // === GRUP OTORISASI ROLE: PETUGAS ===
    // ---------------------------------------------------------------------
    Route::middleware('role:petugas')->group(function () {
        Route::get('/dashboard/petugas', [DashboardController::class, 'petugas'])->name('dashboard.petugas');
    });

    // ---------------------------------------------------------------------
    // === GRUP MULTIROLE: MASYARAKAT & PETUGAS (Akses Ring Peta) ===
    // ---------------------------------------------------------------------
    Route::middleware('role:masyarakat,petugas')->group(function () {
        Route::get('/peta-lokasi', [PetaLokasiController::class, 'masyarakat'])->name('peta.lokasi');
    });

    // ---------------------------------------------------------------------
    // === GRUP MULTIROLE: PETUGAS & ADMIN (Validasi Tugas Lapangan) ===
    // ---------------------------------------------------------------------
    Route::middleware('role:petugas,admin')->prefix('petugas')->group(function () {
        Route::get('/riwayat', [PetugasController::class, 'riwayat'])->name('petugas.riwayat');
        Route::post('/terima/{permintaanPenjemputan}', [PetugasController::class, 'terimaTugas'])->name('petugas.terima');
        Route::get('/bukti/{permintaanPenjemputan}', [PetugasController::class, 'showBukti'])->name('petugas.bukti.show');
        Route::post('/bukti/{permintaanPenjemputan}', [PetugasController::class, 'uploadBukti'])->name('petugas.bukti.upload');
    });

    // ---------------------------------------------------------------------
    // === GRUP OTORISASI ROLE: ADMIN ===
    // ---------------------------------------------------------------------
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::post('/dashboard/admin/permintaan/{permintaanPenjemputan}/schedule', [DashboardController::class, 'schedule'])->name('dashboard.admin.schedule');
        Route::put('/permintaan-penjemputan/{permintaanPenjemputan}/status', [PermintaanPenjemputanController::class, 'updateStatus'])->name('permintaan-penjemputan.update-status');

        // Konfigurasi Persetujuan/Penolakan Usulan Peta Warga
        Route::post('/dashboard/admin/usulan-titik/{usulan}/approve', [PetaLokasiController::class, 'approveUsulan'])->name('dashboard.admin.usulan.approve');
        Route::post('/dashboard/admin/usulan-titik/{usulan}/reject', [PetaLokasiController::class, 'rejectUsulan'])->name('dashboard.admin.usulan.reject');

        // Master Control CRUD Router Data Core SiResik
        Route::prefix('admin')->name('admin.')->group(function () {
            
            // Logika CRUD Tambahan Modul Baru Anda (PBI 23 & PBI 24)
            Route::resource('kegiatan', AdminKegiatanController::class);
            Route::resource('pengumuman', AdminPengumumanController::class)->except(['show']);
            Route::resource('edukasi', AdminEdukasiController::class);

            // FIX PERBAIKAN: Menggunakan map binding resource agar menghasilkan alias admin.kategori.destroy secara otomatis
            Route::resource('kategori', AdminController::class)->parameters(['kategori' => 'kategori'])->except(['index', 'create', 'edit', 'show']);

            // Manajemen Fasilitas Titik Layanan TPS / Bank Sampah
            Route::post('/titik-layanan', [AdminController::class, 'storeTitikLayanan'])->name('titik-layanan.store');
            Route::put('/titik-layanan/{titikLayanan}', [AdminController::class, 'updateTitikLayanan'])->name('titik-layanan.update');
            Route::delete('/titik-layanan/{titikLayanan}', [AdminController::class, 'destroyTitikLayanan'])->name('titik-layanan.destroy');

            // Data Akun Petugas Kebersihan
            Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('petugas.store');
            Route::put('/petugas/{petugas}', [AdminController::class, 'updatePetugas'])->name('petugas.update');
            Route::delete('/petugas/{petugas}', [AdminController::class, 'destroyPetugas'])->name('petugas.destroy');

            // Verifikasi Hasil Timbangan & Sistem Poin Reward
            Route::post('/verifikasi-laporan/{permintaan}', [AdminController::class, 'verifikasiLaporan'])->name('verifikasi-laporan');
            Route::post('/konfigurasi-poin', [AdminController::class, 'updateKonfigurasiPoin'])->name('konfigurasi-poin.update');

            // Manajemen Stok Katalog Hadiah (Reward)
            Route::post('/reward', [AdminController::class, 'storeReward'])->name('reward.store');
            Route::put('/reward/{reward}', [AdminController::class, 'updateReward'])->name('reward.update');
            Route::delete('/reward/{reward}', [AdminController::class, 'destroyReward'])->name('reward.destroy');

            // Manajemen Gambar Spasial Geometris (Zona Radius Wilayah)
            Route::post('/zona-layanan', [AdminController::class, 'storeZonaLayanan'])->name('zona-layanan.store');
            Route::put('/zona-layanan/{zonaLayanan}', [AdminController::class, 'updateZonaLayanan'])->name('zona-layanan.update');
            Route::delete('/zona-layanan/{zonaLayanan}', [AdminController::class, 'destroyZonaLayanan'])->name('zona-layanan.destroy');
            
            // Jadwal Operasional Titik Layanan
            Route::get('/titik-layanan/{titikLayanan}/jadwal', [App\Http\Controllers\JadwalOperasionalController::class, 'index'])->name('jadwal.index');
            Route::post('/titik-layanan/{titikLayanan}/jadwal', [App\Http\Controllers\JadwalOperasionalController::class, 'store'])->name('jadwal.store');
            Route::put('/jadwal/{jadwal}', [App\Http\Controllers\JadwalOperasionalController::class, 'update'])->name('jadwal.update');
            Route::delete('/jadwal/{jadwal}', [App\Http\Controllers\JadwalOperasionalController::class, 'destroy'])->name('jadwal.destroy');
        });
    });
});