<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class AdminKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::withCount('pendaftaran')->orderBy('tanggal', 'desc')->paginate(15);
        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date|after_or_equal:today',
            'kuota' => 'required|integer|min:1',
        ]);

        Kegiatan::create($validated);
        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan lingkungan berhasil dirilis ke publik.');
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with(['peserta'])->findOrFail($id);
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $pendaftarSaatIni = $kegiatan->peserta()->count();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer|min:' . $pendaftarSaatIni,
        ]);

        $kegiatan->update($validated);
        return redirect()->route('admin.kegiatan.index')->with('success', 'Rincian kegiatan sukses dirubah.');
    }

    public function destroy($id)
    {
        Kegiatan::findOrFail($id)->delete();
        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}