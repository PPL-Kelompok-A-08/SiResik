<?php

namespace App\Http\Controllers;

use App\Models\Edukasi;
use Illuminate\Http\Request;

class EdukasiController extends Controller
{
    /**
     * Menampilkan daftar artikel edukasi kepada publik.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Edukasi::latest();

        // Penerapan pencarian
        if (!empty($search)) {
            $query->search($search);
        }

        // Penerapan filter kategori
        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        $edukasis = $query->paginate(6)->withQueryString();

        // Ambil kategori unik untuk navigasi sidebar
        $daftarKategori = Edukasi::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');

        return view('edukasi.index', compact('edukasis', 'daftarKategori', 'search', 'kategori'));
    }

    /**
     * Menampilkan detail artikel berdasarkan slug.
     */
    public function show($slug)
    {
        $edukasi = Edukasi::where('slug', $slug)->firstOrFail();

        // Mengambil 3 artikel rekomendasi yang satu kategori
        $artikelTerkait = Edukasi::where('kategori', $edukasi->kategori)
            ->where('id', '!=', $edukasi->id)
            ->limit(3)
            ->get();

        return view('edukasi.show', compact('edukasi', 'artikelTerkait'));
    }
}