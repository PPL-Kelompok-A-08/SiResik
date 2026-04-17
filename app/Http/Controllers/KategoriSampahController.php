<?php

namespace App\Http\Controllers;

use App\Models\KategoriSampah;
use Illuminate\Http\Request;

class KategoriSampahController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $kategori = KategoriSampah::all();
        return view('kategori.index', compact('kategori'));
    }

    // Detail + kalkulasi
    // public function show($id)
    // {
    //     $kategori = KategoriSampah::findOrFail($id);
    //     return view('kategori.show', compact('kategori'));
    // }

    // Hitung poin (optional AJAX)
    public function hitung(Request $request)
    {
        $kategori = KategoriSampah::findOrFail($request->kategori_id);

        $total = $request->berat * $kategori->poin_per_kg;

        return response()->json([
            'total_poin' => $total
        ]);
    }
}