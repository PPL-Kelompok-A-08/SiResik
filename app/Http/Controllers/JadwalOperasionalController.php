<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\TitikLayanan;
use Illuminate\Http\Request;

class JadwalOperasionalController extends Controller
{

    public function index(TitikLayanan $titikLayanan)
    {
        $jadwal = $titikLayanan->jadwalOperasional()->orderBy('jam_buka')->get();
        return view('admin.jadwal.index', compact('titikLayanan', 'jadwal'));
    }
    
    public function store(Request $request, TitikLayanan $titikLayanan)
    {
        $validated = $request->validate([
            'hari' => ['required', 'string', 'max:50'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['required', 'date_format:H:i', 'after:jam_buka'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $titikLayanan->jadwalOperasional()->create($validated);

        return back()->with('success', 'Jadwal operasional berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalOperasional $jadwal)
    {
        $validated = $request->validate([
            'hari' => ['required', 'string', 'max:50'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['required', 'date_format:H:i', 'after:jam_buka'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $jadwal->update($validated);

        return back()->with('success', 'Jadwal operasional berhasil diperbarui.');
    }

    public function destroy(JadwalOperasional $jadwal)
    {
        $jadwal->delete();

        return back()->with('success', 'Jadwal operasional berhasil dihapus.');
    }
}
