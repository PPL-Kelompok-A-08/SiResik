<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class AdminPengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('tanggal_publish', 'desc')->paginate(15);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
        ]);

        Pengumuman::create($validated);
        return redirect()->route('admin.pengumuman.index')->with('success', 'Broadcast pengumuman sukses diterbitkan.');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
        ]);

        $pengumuman->update($validated);
        return redirect()->route('admin.pengumuman.index')->with('success', 'Materi mading digital berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman terhapus secara permanen.');
    }
}