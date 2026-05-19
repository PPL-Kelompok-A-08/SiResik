<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatpoinpenggunaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua riwayat poin, urut descending
        $poins = $user->poins()
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get();

        // Hitung saldo poin menggunakan aggregate query (lebih efisien dari PHP reduce)
        $totalMasuk  = $user->poins()->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = $user->poins()->where('tipe', 'keluar')->sum('jumlah');
        $totalPoin   = max(0, $totalMasuk - $totalKeluar);

        // Hitung total reward yang sudah ditukar pengguna
        $totalRewardDitukar = $user->penukaranPoins()->count();

        return view('poin.index', compact('user', 'poins', 'totalPoin', 'totalRewardDitukar'));
    }
}
