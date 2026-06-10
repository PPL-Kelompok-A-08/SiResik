<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use App\Models\User;
use App\Models\Reward;
use App\Models\TitikLayanan;
use App\Models\ZonaLayanan;
use App\Models\UsulanTitikLayanan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

    public function admin(Request $request): View
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
        $completedRequests = $permintaan->where('status', 'Selesai')->values();
        $permintaanForStatus = $permintaan;

        // Calculate comprehensive statistics from database
        $stats = [
            'total_user' => User::count(),
            'masyarakat' => User::where('role', 'masyarakat')->count(),
            'petugas' => User::where('role', 'petugas')->count(),
            'total_permintaan' => PermintaanPenjemputan::count(),
            'menunggu' => $pendingRequests->count(),
            'diproses' => PermintaanPenjemputan::where('status', 'Diproses')->count(),
            'selesai' => $completedRequests->count(),
        ];

        [$reportFilters, $periodicReports, $periodicSummary, $chartData, $periodicExportRows] = $this->buildPeriodicReportData($request);

        return view('dashboard.admin', compact(
            'user',
            'permintaan',
            'stats',
            'petugas',
            'pendingRequests',
            'scheduledRequests',
            'completedRequests',
            'permintaanForStatus',
            'rewards',
            'titikLayanan',
            'zonaLayanan',
            'usulanMenunggu',
            'laporanSampahLiar',
            'reportFilters',
            'periodicReports',
            'periodicSummary',
            'chartData',
            'periodicExportRows'
        ));
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

    private function buildPeriodicReportData(Request $request): array
    {
        $today = now()->toDateString();
        $startDate = $this->dateFromRequest($request->query('report_start'), now()->subMonths(5)->startOfMonth()->toDateString());
        $endDate = $this->dateFromRequest($request->query('report_end'), $today);

        if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $type = in_array($request->query('report_type'), ['daily', 'weekly', 'monthly'], true)
            ? $request->query('report_type')
            : 'monthly';
        $status = in_array($request->query('report_status'), ['Menunggu', 'Diproses', 'Selesai', 'Dibatalkan'], true)
            ? $request->query('report_status')
            : 'all';

        $periodicReports = PermintaanPenjemputan::with(['pengguna', 'items.kategoriSampah', 'petugas'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();

        $periodicSummary = [
            'total' => $periodicReports->count(),
            'selesai' => $periodicReports->where('status', 'Selesai')->count(),
            'menunggu' => $periodicReports->where('status', 'Menunggu')->count(),
            'diproses' => $periodicReports->where('status', 'Diproses')->count(),
            'dibatalkan' => $periodicReports->where('status', 'Dibatalkan')->count(),
            'total_berat' => $periodicReports->sum(fn ($item) => $item->items->sum('berat_kg')),
            'total_poin' => $periodicReports->sum('total_estimasi_poin'),
            'pengguna_unik' => $periodicReports->pluck('pengguna_id')->filter()->unique()->count(),
        ];

        $chartData = [
            'trend' => $this->buildTrendDataset($periodicReports, $startDate, $endDate, $type),
            'status' => $this->buildStatusDataset($periodicReports),
            'category' => $this->buildCategoryDataset($periodicReports),
            'users' => $this->buildUserDataset($periodicReports),
        ];

        $reportFilters = [
            'start' => $startDate,
            'end' => $endDate,
            'type' => $type,
            'status' => $status,
        ];

        $periodicExportRows = $periodicReports
            ->map(fn ($report) => [
                'tanggal_laporan' => $report->created_at->format('Y-m-d'),
                'tanggal_jadwal' => $report->tanggal,
                'id_laporan' => $report->id,
                'pengguna' => $report->pengguna?->name ?? 'Pengguna Tidak Diketahui',
                'petugas' => $report->petugas?->name ?? '-',
                'status' => $report->status,
                'berat_kg' => round($report->items->sum('berat_kg'), 2),
                'poin' => $report->total_estimasi_poin,
            ])
            ->values();

        return [$reportFilters, $periodicReports, $periodicSummary, $chartData, $periodicExportRows];
    }

    private function dateFromRequest(mixed $date, string $fallback): string
    {
        try {
            return $date ? Carbon::parse($date)->toDateString() : $fallback;
        } catch (\Throwable) {
            return $fallback;
        }
    }

    private function buildTrendDataset($reports, string $startDate, string $endDate, string $type): array
    {
        $labels = [];
        $keys = [];
        $cursor = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($type === 'monthly') {
            $cursor = $cursor->startOfMonth();
            $end = $end->endOfMonth();
            while ($cursor->lte($end)) {
                $key = $cursor->format('Y-m');
                $keys[] = $key;
                $labels[] = $cursor->translatedFormat('M Y');
                $cursor->addMonth();
            }
        } elseif ($type === 'weekly') {
            $cursor = $cursor->startOfWeek();
            $end = $end->endOfWeek();
            while ($cursor->lte($end)) {
                $key = $cursor->format('o-W');
                $keys[] = $key;
                $labels[] = $cursor->format('d M') . ' - ' . $cursor->copy()->endOfWeek()->format('d M');
                $cursor->addWeek();
            }
        } else {
            foreach (CarbonPeriod::create($cursor, $end) as $date) {
                $keys[] = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
            }
        }

        $grouped = $reports->groupBy(function ($report) use ($type) {
            $date = Carbon::parse($report->created_at);

            return match ($type) {
                'weekly' => $date->format('o-W'),
                'daily' => $date->format('Y-m-d'),
                default => $date->format('Y-m'),
            };
        });

        return [
            'labels' => $labels,
            'reports' => collect($keys)->map(fn ($key) => $grouped->get($key, collect())->count())->values(),
            'weight' => collect($keys)->map(fn ($key) => round($grouped->get($key, collect())->sum(fn ($report) => $report->items->sum('berat_kg')), 2))->values(),
        ];
    }

    private function buildStatusDataset($reports): array
    {
        $statuses = ['Menunggu', 'Diproses', 'Selesai', 'Dibatalkan'];

        return [
            'labels' => $statuses,
            'data' => collect($statuses)->map(fn ($status) => $reports->where('status', $status)->count())->values(),
        ];
    }

    private function buildCategoryDataset($reports): array
    {
        $categories = $reports
            ->flatMap(fn ($report) => $report->items)
            ->groupBy(fn ($item) => $item->kategoriSampah?->nama ?? 'Tanpa Kategori')
            ->map(fn ($items) => round($items->sum('berat_kg'), 2))
            ->sortDesc()
            ->take(8);

        return [
            'labels' => $categories->keys()->values(),
            'data' => $categories->values(),
        ];
    }

    private function buildUserDataset($reports): array
    {
        $users = $reports
            ->groupBy(fn ($report) => $report->pengguna?->name ?? 'Pengguna Tidak Diketahui')
            ->map->count()
            ->sortDesc()
            ->take(8);

        return [
            'labels' => $users->keys()->values(),
            'data' => $users->values(),
        ];
    }
}
