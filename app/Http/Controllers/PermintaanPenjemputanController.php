<?php

namespace App\Http\Controllers;

use App\Models\KategoriSampah;
use App\Models\PermintaanPenjemputan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PermintaanPenjemputanController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $canCreate = in_array($user->role, ['masyarakat', 'admin'], true);
        $users = $isAdmin ? User::orderBy('name')->get() : collect([$user]);
        $kategori = KategoriSampah::orderBy('nama')->get();
        $permintaan = PermintaanPenjemputan::with(['pengguna', 'items.kategoriSampah'])
            ->when($user->role === 'masyarakat', fn ($query) => $query->where('pengguna_id', $user->id))
            ->latest()
            ->get();

        $statusCounts = [
            'total' => $permintaan->count(),
            'menunggu' => $permintaan->where('status', 'Menunggu')->count(),
            'diproses' => $permintaan->where('status', 'Diproses')->count(),
            'selesai' => $permintaan->where('status', 'Selesai')->count(),
        ];

        return view('permintaan-penjemputan.index', compact('users', 'permintaan', 'statusCounts', 'isAdmin', 'canCreate', 'user', 'kategori'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        abort_unless(in_array($user->role, ['masyarakat', 'admin'], true), 403);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jadwal' => ['required', 'string', 'max:45'],
            'alamat' => ['required', 'string', 'max:45'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'catatan' => ['nullable', 'string', 'max:45'],
            'selected_categories' => ['required', 'array', 'min:1'],
            'selected_categories.*' => ['required', 'integer', 'exists:kategori_sampah,id'],
            'berat' => ['required', 'array'],
        ]);

        $penggunaId = $user->role === 'admin'
            ? $request->validate([
                'pengguna_id' => ['required', 'integer', 'exists:users,id'],
            ])['pengguna_id']
            : $user->id;

        $kategoriMap = KategoriSampah::whereIn('id', $validated['selected_categories'])->get()->keyBy('id');
        $items = collect($validated['selected_categories'])
            ->map(function ($kategoriId) use ($request, $kategoriMap) {
                $kategori = $kategoriMap->get((int) $kategoriId);
                $berat = (float) $request->input("berat.$kategoriId");

                return [
                    'kategori' => $kategori,
                    'berat_kg' => $berat,
                    'estimasi_poin' => (int) round($berat * $kategori->poin_per_kg),
                ];
            })
            ->filter(fn ($item) => $item['kategori'] !== null && $item['berat_kg'] > 0)
            ->values();

        if ($items->isEmpty()) {
            return back()
                ->withErrors([
                    'selected_categories' => 'Pilih minimal satu kategori sampah dengan berat lebih dari 0.',
                ])
                ->withInput();
        }

        $totalEstimasiPoin = $items->sum('estimasi_poin');

        $permintaan = DB::transaction(function () use ($penggunaId, $validated, $items, $totalEstimasiPoin) {
            $permintaan = PermintaanPenjemputan::create([
                'pengguna_id' => $penggunaId,
                'tanggal' => $validated['tanggal'],
                'jadwal' => $validated['jadwal'],
                'status' => 'Menunggu',
                'alamat' => $validated['alamat'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'catatan' => $validated['catatan'] ?? '-',
                'total_estimasi_poin' => $totalEstimasiPoin,
            ]);

            foreach ($items as $item) {
                $permintaan->items()->create([
                    'kategori_sampah_id' => $item['kategori']->id,
                    'berat_kg' => $item['berat_kg'],
                    'estimasi_poin' => $item['estimasi_poin'],
                ]);
            }

            return $permintaan;
        });

        return redirect()
            ->route('permintaan-penjemputan.success', $permintaan);
    }

    public function success(PermintaanPenjemputan $permintaanPenjemputan): View
    {
        $user = auth()->user();

        abort_unless(
            $user->role === 'admin' || $permintaanPenjemputan->pengguna_id === $user->id,
            403
        );

        $permintaanPenjemputan->load(['pengguna', 'items.kategoriSampah']);

        return view('permintaan-penjemputan.success', [
            'permintaan' => $permintaanPenjemputan,
            'user' => $user,
        ]);
    }
}
