<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalOperasionalController extends Controller
{
    /**
     * Menampilkan dashboard Status Layanan untuk Masyarakat.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Ambil semua permintaan penjemputan milik user beserta kategori sampah dan petugas
        $permintaan = PermintaanPenjemputan::with(['items.kategoriSampah', 'petugas'])
            ->where('pengguna_id', $user->id)
            ->latest()
            ->get();

        // Mengambil 3 permintaan terbaru untuk pelacakan real-time
        $trackingRequests = $permintaan->take(3)->values();
        
        // Mengambil jadwal penjemputan terdekat yang sedang diproses
        $upcomingRequest = $permintaan
            ->where('status', 'Diproses')
            ->sortBy('scheduled_at')
            ->first();

        // Statistik dashboard
        $stats = [
            'total' => $permintaan->count(),
            'menunggu' => $permintaan->where('status', 'Menunggu')->count(),
            'diproses' => $permintaan->where('status', 'Diproses')->count(),
            'selesai' => $permintaan->where('status', 'Selesai')->count(),
        ];

        // Kalender jadwal reguler mingguan
        $weeklySchedules = [
            [
                'hari' => 'Senin',
                'kategori' => 'Organik (Sisa Makanan)',
                'jam' => '08:00 - 10:00',
                'zona' => 'Bojongsoang, Desa Buah batu',
            ],
            [
                'hari' => 'Selasa',
                'kategori' => 'Anorganik (Plastik, Kertas)',
                'jam' => '08:00 - 10:00',
                'zona' => 'Bojongsoang, Desa Bojongsoang ',
            ],
            [
                'hari' => 'Rabu',
                'kategori' => 'Residu (Popok, Tisu)',
                'jam' => '09:00 - 11:00',
                'zona' => 'Baleendah, Kelurahan Jelekong',
            ],
        ];

        return view('dashboard.masyarakat', compact('user', 'permintaan', 'stats', 'trackingRequests', 'upcomingRequest', 'weeklySchedules'));
    }
}
