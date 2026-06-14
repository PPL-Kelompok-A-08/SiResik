<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermintaanPenjemputan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatLayananController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = PermintaanPenjemputan::with(['petugas', 'items.kategoriSampah'])
            ->where('pengguna_id', $user->id)
            ->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $riwayat = $query->paginate(8)->withQueryString();

        // Hitung total berat dari semua item penjemputan user
        $allRiwayat = PermintaanPenjemputan::with('items')
            ->where('pengguna_id', $user->id)
            ->get();

        $totalBerat = $allRiwayat->flatMap(fn($r) => $r->items)->sum('berat_kg');

        $stats = [
            'total'       => $allRiwayat->count(),
            'menunggu'    => $allRiwayat->where('status', 'Menunggu')->count(),
            'diproses'    => $allRiwayat->where('status', 'Diproses')->count(),
            'selesai'     => $allRiwayat->where('status', 'Selesai')->count(),
            'total_poin'  => $allRiwayat->where('status', 'Selesai')->sum('total_estimasi_poin'),
            'total_berat' => $totalBerat,
        ];

        return view('riwayat-layanan.index', compact('user', 'riwayat', 'stats'));
    }

    public function show(PermintaanPenjemputan $permintaanPenjemputan): View
    {
        $user = auth()->user();

        abort_unless($permintaanPenjemputan->pengguna_id === $user->id, 403);

        $permintaanPenjemputan->load(['pengguna', 'petugas', 'items.kategoriSampah']);

        return view('riwayat-layanan.show', [
            'permintaan' => $permintaanPenjemputan,
            'user' => $user,
        ]);
    }
}
