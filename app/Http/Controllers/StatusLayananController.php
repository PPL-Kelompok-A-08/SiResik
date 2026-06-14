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

        $weeklySchedules = \App\Http\Controllers\JadwalOperasionalController::getWeeklySchedules();

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
