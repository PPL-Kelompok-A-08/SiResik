<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatpoinpenggunaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $poins = $user->poins()->orderByDesc('tanggal')->orderByDesc('created_at')->get();

        $totalPoin = $poins->reduce(function ($carry, $poin) {
            return $carry + ($poin->tipe === 'masuk' ? $poin->jumlah : -$poin->jumlah);
        }, 0);

        return view('poin.index', compact('user', 'poins', 'totalPoin'));
    }
}
