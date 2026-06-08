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

        $trackingRequests = $permintaan->take(5)->values();

        $upcomingRequest = $permintaan
            ->where('status', 'Diproses')
            ->sortBy('scheduled_at')
            ->first();

        // Hitung total berat setoran (semua permintaan)
        $totalBerat = $permintaan->flatMap(fn($p) => $p->items)->sum('berat_kg');

        // Hitung total poin dari permintaan selesai
        $totalPoin = $permintaan->where('status', 'Selesai')->sum('total_estimasi_poin');

        // Kontribusi CO2 (estimasi: setiap 1 kg sampah daur ulang = ~0.087 kg CO2 hemat)
        $kontribusiCo2 = round($totalBerat * 0.087, 1);

        // Jadwal terdekat
        if ($upcomingRequest && $upcomingRequest->scheduled_at) {
            $jadwalTerdekat = \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)
                ->translatedFormat('d M, H:i') . ' WIB';
        } else {
            $jadwalTerdekat = 'Belum ada';
        }

        $stats = [
            'total'          => $permintaan->count(),
            'menunggu'       => $permintaan->where('status', 'Menunggu')->count(),
            'diproses'       => $permintaan->where('status', 'Diproses')->count(),
            'selesai'        => $permintaan->where('status', 'Selesai')->count(),
            'total_berat'    => $totalBerat,
            'total_poin'     => $totalPoin,
            'kontribusi_co2' => $kontribusiCo2,
            'jadwal_terdekat'=> $jadwalTerdekat,
        ];

        // Titik layanan untuk peta
        $titikLayanan = TitikLayanan::all();

        // Total warga aktif (role masyarakat)
        $totalWarga = User::where('role', 'masyarakat')->count();

        return view('dashboard.masyarakat', compact(
            'user', 'permintaan', 'stats',
            'trackingRequests', 'upcomingRequest',
            'titikLayanan', 'totalWarga'
        ));
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
        $laporanSampahLiar = SampahLiar::with('pengguna')
            ->latest()
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
