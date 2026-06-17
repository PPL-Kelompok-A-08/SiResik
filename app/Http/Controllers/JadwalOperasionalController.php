<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use App\Models\JadwalOperasional;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JadwalOperasionalController extends Controller
{
    public static function getWeeklySchedules(): array
    {
        return JadwalOperasional::with('petugas')
            ->whereNotNull('zona')
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END")
            ->orderBy('jam_buka')
            ->get()
            ->map(function ($item) {
                // Tentukan kategori default berdasarkan hari/zona
                $kategori = 'Organik (Sisa Makanan)';
                $lowerZona = strtolower($item->zona);
                if (str_contains($lowerZona, 'anorganik') || ($item->hari === 'Selasa' && str_contains($lowerZona, 'bojongsoang'))) {
                    $kategori = 'Anorganik (Plastik, Kertas)';
                } elseif (str_contains($lowerZona, 'residu') || str_contains($lowerZona, 'baleendah')) {
                    $kategori = 'Residu (Popok, Tisu)';
                }

                $time = \Carbon\Carbon::parse($item->jam_buka);
                $timeEnd = \Carbon\Carbon::parse($item->jam_tutup);
                $jamFormat = $time->format('H:i') . ' - ' . $timeEnd->format('H:i');
                $jamSingkat = $time->format('H:i') . ' WIB';

                return [
                    'id' => $item->id,
                    'hari' => $item->hari,
                    'kategori' => $kategori,
                    'jam' => $jamFormat,
                    'jam_singkat' => $jamSingkat,
                    'jam_raw' => $time->format('H:i'),
                    'jam_tutup_raw' => $timeEnd->format('H:i'),
                    'zona' => $item->zona,
                    'petugas' => $item->petugas?->name ?? 'Petugas SiResik',
                    'petugas_id' => $item->petugas_id,
                    'keterangan' => $item->keterangan,
                    'jenis_sampah' => $item->jenis_sampah ?? [],
                ];
            })
            ->toArray();
    }

    /**
     * Menampilkan daftar jadwal operasional untuk titik layanan tertentu (Admin).
     */
    public function index(\App\Models\TitikLayanan $titikLayanan): View
    {
        $jadwal = $titikLayanan->jadwalOperasional()->orderBy('jam_buka')->get();
        return view('admin.jadwal.index', compact('titikLayanan', 'jadwal'));
    }

    /**
     * Menyimpan jadwal operasional baru untuk titik layanan (Admin).
     */
    public function store(Request $request, \App\Models\TitikLayanan $titikLayanan): RedirectResponse
    {
        $validated = $request->validate([
            'hari' => ['required', 'string', 'max:50'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['required', 'date_format:H:i', 'after:jam_buka'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $titikLayanan->jadwalOperasional()->create($validated);

        return redirect()
            ->back()
            ->with('success', 'Jadwal operasional berhasil ditambahkan.');
    }

    /**
     * Memperbarui jadwal operasional titik layanan (Admin).
     */
    public function update(Request $request, JadwalOperasional $jadwal): RedirectResponse
    {
        $validated = $request->validate([
            'hari' => ['required', 'string', 'max:50'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['required', 'date_format:H:i', 'after:jam_buka'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $jadwal->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Jadwal operasional berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal operasional titik layanan (Admin).
     */
    public function destroy(JadwalOperasional $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()
            ->back()
            ->with('success', 'Jadwal operasional berhasil dihapus.');
    }

    /**
     * Menyimpan jadwal area baru.
     */
    public function storeJadwalArea(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hari' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'zona' => ['required', 'string', 'max:255'],
            'jam' => ['required', 'string'],
            'petugas_id' => ['nullable', 'integer', 'exists:users,id'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'jenis_sampah' => ['nullable', 'array'],
            'jenis_sampah.*' => ['string'],
        ]);

        $time = \Carbon\Carbon::parse($validated['jam']);

        JadwalOperasional::create([
            'hari' => $validated['hari'],
            'zona' => $validated['zona'],
            'jam_buka' => $time->format('H:i:s'),
            'jam_tutup' => $time->copy()->addHours(2)->format('H:i:s'),
            'petugas_id' => $validated['petugas_id'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'jenis_sampah' => $validated['jenis_sampah'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Jadwal reguler area berhasil ditambahkan.');
    }

    /**
     * Memperbarui jadwal area.
     */
    public function updateJadwalArea(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'hari' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'zona' => ['required', 'string', 'max:255'],
            'jam' => ['required', 'string'],
            'petugas_id' => ['nullable', 'integer', 'exists:users,id'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'jenis_sampah' => ['nullable', 'array'],
            'jenis_sampah.*' => ['string'],
        ]);

        $jadwal = JadwalOperasional::findOrFail($id);
        $time = \Carbon\Carbon::parse($validated['jam']);

        $jadwal->update([
            'hari' => $validated['hari'],
            'zona' => $validated['zona'],
            'jam_buka' => $time->format('H:i:s'),
            'jam_tutup' => $time->copy()->addHours(2)->format('H:i:s'),
            'petugas_id' => $validated['petugas_id'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'jenis_sampah' => $validated['jenis_sampah'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Jadwal reguler area berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal area.
     */
    public function destroyJadwalArea($id): RedirectResponse
    {
        $jadwal = JadwalOperasional::findOrFail($id);
        $jadwal->delete();

        return redirect()
            ->back()
            ->with('success', 'Jadwal reguler area berhasil dihapus.');
    }
}
