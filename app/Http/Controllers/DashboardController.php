<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use App\Models\User;
use App\Models\Reward;
use App\Models\TitikLayanan;
use App\Models\ZonaLayanan;
use App\Models\UsulanTitikLayanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $route = match (auth()->user()->role) {
            'admin' => 'dashboard.admin',
            'petugas' => 'dashboard.petugas',
            default => 'dashboard.masyarakat',
        };

        return redirect()->route($route);
    }

    public function masyarakat(): View
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
            'total' => $permintaan->count(),
            'menunggu' => $permintaan->where('status', 'Menunggu')->count(),
            'diproses' => $permintaan->where('status', 'Diproses')->count(),
            'selesai' => $permintaan->where('status', 'Selesai')->count(),
        ];

        $weeklySchedules = [
            [
                'hari' => 'Senin',
                'kategori' => 'Organik (Sisa Makanan)',
                'jam' => '08:00 - 10:00',
                'zona' => 'Zona A',
            ],
            [
                'hari' => 'Rabu',
                'kategori' => 'Anorganik (Plastik, Kertas)',
                'jam' => '08:00 - 10:00',
                'zona' => 'Zona A',
            ],
            [
                'hari' => 'Jumat',
                'kategori' => 'Residu (Popok, Tisu)',
                'jam' => '09:00 - 11:00',
                'zona' => 'Zona A',
            ],
        ];

        return view('dashboard.masyarakat', compact('user', 'permintaan', 'stats', 'trackingRequests', 'upcomingRequest', 'weeklySchedules'));
    }

    public function petugas(): View
    {
        $user = auth()->user();
        $permintaan = PermintaanPenjemputan::with('pengguna')
            ->where(function ($q) use ($user) {
                $q->where('petugas_id', $user->id)
                  ->orWhereNull('petugas_id');
            })
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'jadwal_hari_ini' => PermintaanPenjemputan::where('tanggal', now()->toDateString())->count(),
            'menunggu' => PermintaanPenjemputan::where('status', 'Menunggu')->count(),
            'diproses' => PermintaanPenjemputan::where('status', 'Diproses')->count(),
            'selesai' => PermintaanPenjemputan::where('status', 'Selesai')->count(),
        ];

        return view('dashboard.petugas', compact('user', 'permintaan', 'stats'));
    }

    public function admin(): View
    {
        $user = auth()->user();
        $permintaan = PermintaanPenjemputan::with(['pengguna', 'items.kategoriSampah', 'petugas'])
            ->latest()
            ->get();
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        $rewards = Reward::orderBy('nama')->get();
        $titikLayanan = TitikLayanan::orderBy('nama')->get();
        $zonaLayanan = ZonaLayanan::orderBy('nama')->get();
        $usulanMenunggu = UsulanTitikLayanan::with('pengusul')
            ->where('status', 'diajukan')
            ->latest()
            ->get();
        $pendingRequests = $permintaan->where('status', 'Menunggu')->values();
        $scheduledRequests = $permintaan->where('status', 'Diproses')->take(4)->values();

        $stats = [
            'total_user' => User::count(),
            'masyarakat' => User::where('role', 'masyarakat')->count(),
            'petugas' => User::where('role', 'petugas')->count(),
            'total_permintaan' => PermintaanPenjemputan::count(),
            'menunggu' => $pendingRequests->count(),
        ];

        return view('dashboard.admin', compact('user', 'permintaan', 'stats', 'petugas', 'pendingRequests', 'scheduledRequests', 'rewards', 'titikLayanan', 'zonaLayanan', 'usulanMenunggu'));
    }

    public function schedule(Request $request, PermintaanPenjemputan $permintaanPenjemputan): RedirectResponse
    {
        $validated = $request->validate([
            'scheduled_at' => ['required', 'date'],
            'petugas_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $petugas = User::where('role', 'petugas')->findOrFail($validated['petugas_id']);

        $permintaanPenjemputan->update([
            'scheduled_at' => $validated['scheduled_at'],
            'petugas_id' => $petugas->id,
            'status' => 'Diproses',
            'jadwal' => \Illuminate\Support\Carbon::parse($validated['scheduled_at'])->format('d M Y, H:i'),
        ]);

        return redirect()
            ->route('dashboard.admin')
            ->with('success', 'Jadwal penjemputan berhasil diperbarui.');
    }
}
