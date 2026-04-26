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

        $stats = [
            'total' => PermintaanPenjemputan::where('pengguna_id', $user->id)->count(),
            'menunggu' => PermintaanPenjemputan::where('pengguna_id', $user->id)->where('status', 'Menunggu')->count(),
            'diproses' => PermintaanPenjemputan::where('pengguna_id', $user->id)->where('status', 'Diproses')->count(),
            'selesai' => PermintaanPenjemputan::where('pengguna_id', $user->id)->where('status', 'Selesai')->count(),
            'total_poin' => PermintaanPenjemputan::where('pengguna_id', $user->id)->where('status', 'Selesai')->sum('total_estimasi_poin'),
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
