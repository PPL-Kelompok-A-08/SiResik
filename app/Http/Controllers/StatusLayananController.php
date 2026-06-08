<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use Illuminate\View\View;

class StatusLayananController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $permintaan = PermintaanPenjemputan::with(['items.kategoriSampah', 'petugas'])
            ->where('pengguna_id', $user->id)
            ->latest()
            ->get();

        $trackingRequests = $permintaan->take(3)->values();

        $upcomingRequest = $permintaan
            ->where('status', 'Diproses')
            ->sortBy('scheduled_at')
            ->first();

        $stats = [
            'total'    => $permintaan->count(),
            'menunggu' => $permintaan->where('status', 'Menunggu')->count(),
            'diproses' => $permintaan->where('status', 'Diproses')->count(),
            'selesai'  => $permintaan->where('status', 'Selesai')->count(),
        ];

        $weeklySchedules = [
            [
                'hari'     => 'Senin',
                'kategori' => 'Organik (Sisa Makanan)',
                'jam'      => '08:00 - 10:00',
                'zona'     => 'Zona A',
            ],
            [
                'hari'     => 'Rabu',
                'kategori' => 'Anorganik (Plastik, Kertas)',
                'jam'      => '08:00 - 10:00',
                'zona'     => 'Zona A',
            ],
            [
                'hari'     => 'Jumat',
                'kategori' => 'Residu (Popok, Tisu)',
                'jam'      => '09:00 - 11:00',
                'zona'     => 'Zona A',
            ],
        ];

        return view('status-layanan.index', compact(
            'user',
            'permintaan',
            'trackingRequests',
            'upcomingRequest',
            'stats',
            'weeklySchedules'
        ));
    }
}
