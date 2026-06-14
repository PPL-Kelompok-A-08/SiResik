<?php

namespace App\Http\Controllers;

use App\Models\SampahLiar;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SampahLiarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();
        $myReports = SampahLiar::where('pengguna_id', $user->id)
            ->latest()
            ->paginate(10);

        // Laporan di sekitar (semua laporan dari user lain, termasuk pending)
        $nearbyReports = SampahLiar::where('pengguna_id', '!=', $user->id)
            ->latest()
            ->limit(6)
            ->get();

        return view('sampah-liar.index', compact('myReports', 'nearbyReports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('sampah-liar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'deskripsi' => 'required|string|min:10',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'jumlah_estimasi' => 'required|integer|min:1',
        ], [
            'foto.required' => 'Foto bukti harus diunggah',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, atau gif',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'deskripsi.min' => 'Deskripsi minimal harus 10 karakter',
            'latitude.between' => 'Latitude tidak valid',
            'longitude.between' => 'Longitude tidak valid',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('sampah-liar', 'public');
        }

        SampahLiar::create([
            'pengguna_id' => auth()->id(),
            'lokasi' => $validated['lokasi'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'deskripsi' => $validated['deskripsi'],
            'foto' => $fotoPath,
            'jumlah_estimasi' => $validated['jumlah_estimasi'],
            'status' => 'pending',
        ]);

        return redirect()->route('sampah-liar.index')
            ->with('success', 'Laporan sampah liar berhasil dikirim. Terima kasih atas kontribusi Anda!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SampahLiar $sampahLiar): View
    {
        return view('sampah-liar.show', compact('sampahLiar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampahLiar $sampahLiar): View
    {
        $this->authorize('update', $sampahLiar);
        return view('sampah-liar.edit', compact('sampahLiar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampahLiar $sampahLiar): RedirectResponse
    {
        $this->authorize('update', $sampahLiar);

        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'deskripsi' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'jumlah_estimasi' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old file if exists
            if ($sampahLiar->foto) {
                \Storage::disk('public')->delete($sampahLiar->foto);
            }
            $validated['foto'] = $request->file('foto')->store('sampah-liar', 'public');
        }

        $sampahLiar->update($validated);

        return redirect()->route('sampah-liar.show', $sampahLiar)
            ->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampahLiar $sampahLiar): RedirectResponse
    {
        $this->authorize('delete', $sampahLiar);

        if ($sampahLiar->foto) {
            \Storage::disk('public')->delete($sampahLiar->foto);
        }

        $sampahLiar->delete();

        return redirect()->route('sampah-liar.index')
            ->with('success', 'Laporan berhasil dihapus');
    }
}
