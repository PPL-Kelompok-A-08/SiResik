<?php

namespace App\Http\Controllers;

use App\Models\TitikLayanan;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PetaLokasiController extends Controller
{
    public function masyarakat(): View
    {
        $user = auth()->user();
        $titikLayanan = TitikLayanan::orderBy('nama')->get();

        return view('peta.lokasi-masyarakat', compact('user', 'titikLayanan'));
    }

    public function admin(): View
    {
        $user = auth()->user();
        $titikLayanan = TitikLayanan::orderBy('nama')->get();

        return view('peta.lokasi-admin', compact('user', 'titikLayanan'));
    }

    public function titikLayananJson(): JsonResponse
    {
        $data = TitikLayanan::orderBy('nama')->get();

        return response()->json($data);
    }
}
