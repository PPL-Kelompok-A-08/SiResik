<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use App\Models\User;
use App\Models\Reward;
use App\Models\TitikLayanan;
use App\Models\ZonaLayanan;
use App\Models\UsulanTitikLayanan;
use App\Models\SampahLiar;
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

        $operationalHours = '08:00 - 16:00 WIB';

        // Aggregate contribution statistics for the user
        $totalKg = $permintaan->reduce(function ($carry, $p) {
            return $carry + (float) $p->items->sum('berat_kg');
        }, 0);

        $totalPickups = $permintaan->where('jenis', 'Pickup')->count();

        $totalPoints = (int) $permintaan->sum('total_estimasi_poin');

        return view('dashboard.masyarakat', compact('user', 'permintaan', 'stats', 'trackingRequests', 'upcomingRequest', 'weeklySchedules', 'operationalHours', 'totalKg', 'totalPickups', 'totalPoints'));
    }

    public function petugas(): View
    {
        $user = auth()->user();

        $permintaanQuery = PermintaanPenjemputan::with(['pengguna', 'petugas', 'items.kategoriSampah'])
            ->where(function ($q) use ($user) {
                $q->where('petugas_id', $user->id)
                  ->orWhereNull('petugas_id');
            });

        $permintaan = $permintaanQuery->latest()->take(12)->get();

        $stats = [
            'jadwal_hari_ini' => PermintaanPenjemputan::whereDate('tanggal', now()->toDateString())
                ->where('petugas_id', $user->id)
                ->count(),
            'all' => $permintaanQuery->count(),
            'pending' => PermintaanPenjemputan::where('status', 'Menunggu')
                ->where(function ($q) use ($user) {
                    $q->where('petugas_id', $user->id)
                      ->orWhereNull('petugas_id');
                })
                ->count(),
            'ongoing' => PermintaanPenjemputan::where('status', 'Diproses')
                ->where('petugas_id', $user->id)
                ->count(),
            'completed' => PermintaanPenjemputan::where('status', 'Selesai')
                ->where('petugas_id', $user->id)
                ->count(),
        ];

        // Sampah liar tasks for petugas: yang sudah diverifikasi dan belum ditangani, atau yang sudah ditugaskan ke petugas
        $laporanSampahLiar = SampahLiar::with('pengguna', 'petugas')
            ->where(function ($q) use ($user) {
                $q->whereNull('petugas_id')->orWhere('petugas_id', $user->id);
            })
            ->where('status', 'diverifikasi')
            ->latest()
            ->take(12)
            ->get();

        return view('dashboard.petugas', compact('user', 'permintaan', 'stats', 'laporanSampahLiar'));
    }

    public function admin(): View
    {
        $user = auth()->user();
        $permintaan = PermintaanPenjemputan::with(['pengguna', 'items.kategoriSampah', 'petugas'])
            ->orderBy('tanggal', 'asc')
            ->get();
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        $rewards = Reward::orderBy('nama')->get();
        $titikLayanan = TitikLayanan::orderBy('nama')->get();
        $zonaLayanan = ZonaLayanan::orderBy('nama')->get();
        $usulanMenunggu = UsulanTitikLayanan::with('pengusul')
            ->where('status', 'diajukan')
            ->latest()
            ->get();
        $laporanSampahLiar = SampahLiar::with('pengguna')
            ->orderBy('created_at', 'asc')
            ->get();
        $pendingRequests = $permintaan->where('status', 'Menunggu')->values();
        $scheduledRequests = $permintaan->where('status', 'Diproses')->take(4)->values();
        $permintaanForStatus = $permintaan->values();
        $permintaanForStatus = $permintaan;

        $stats = [
            'total_user' => User::count(),
            'masyarakat' => User::where('role', 'masyarakat')->count(),
            'petugas' => User::where('role', 'petugas')->count(),
            'total_permintaan' => PermintaanPenjemputan::count(),
            'menunggu' => $pendingRequests->count(),
        ];

        return view('dashboard.admin', compact('user', 'permintaan', 'stats', 'petugas', 'pendingRequests', 'scheduledRequests', 'permintaanForStatus', 'rewards', 'titikLayanan', 'zonaLayanan', 'usulanMenunggu', 'laporanSampahLiar'));
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
