<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEdukasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Edukasi::latest();
        if (!empty($search)) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        }

        $edukasis = $query->paginate(10)->withQueryString();
        
        return view('admin.edukasi.index', compact('edukasis', 'search'));
    }

    public function create()
    {
        $kategoriDefault = ['Sampah Organik', 'Sampah Anorganik', 'Daur Ulang', 'Gaya Hidup Hijau', 'Konservasi Energi'];
        return view('admin.edukasi.create', compact('kategoriDefault'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255|unique:edukasis,judul',
            'kategori' => 'required|string|max:100',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
            'isi' => 'required|string|min:20',
        ], [
            'judul.required' => 'Judul edukasi wajib diisi.',
            'judul.unique' => 'Judul edukasi ini sudah pernah digunakan.',
            'kategori.required' => 'Silakan pilih atau ketik kategori edukasi.',
            'gambar.required' => 'Gambar sampul artikel wajib diunggah.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar didukung: JPEG, PNG, JPG, WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 2MB.',
            'isi.required' => 'Isi materi edukasi tidak boleh kosong.',
            'isi.min' => 'Materi edukasi minimal harus berisi 20 karakter.',
        ]);

        $input = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // Menyimpan ke public disk (storage/app/public/edukasi)
            $pathFile = $file->store('edukasi', 'public');
            $input['gambar'] = $pathFile;
        }

        Edukasi::create($input);

        return redirect()->route('admin.edukasi.index')
            ->with('success', 'Konten edukasi lingkungan berhasil diterbitkan!');
    }

    public function show($id)
    {
        $edukasi = Edukasi::findOrFail($id);
        return view('admin.edukasi.show', compact('edukasi'));
    }

    public function edit($id)
    {
        $edukasi = Edukasi::findOrFail($id);
        $kategoriDefault = ['Sampah Organik', 'Sampah Anorganik', 'Daur Ulang', 'Gaya Hidup Hijau', 'Konservasi Energi'];
        
        return view('admin.edukasi.edit', compact('edukasi', 'kategoriDefault'));
    }

    public function update(Request $request, $id)
    {
        $edukasi = Edukasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255|unique:edukasis,judul,' . $edukasi->id,
            'kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'isi' => 'required|string|min:20',
        ], [
            'judul.required' => 'Judul edukasi wajib diisi.',
            'judul.unique' => 'Judul edukasi sudah digunakan pada artikel lain.',
            'kategori.required' => 'Kategori edukasi harus diisi.',
            'gambar.image' => 'File yang dimasukkan harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 2MB.',
            'isi.required' => 'Isi materi edukasi wajib diisi.',
            'isi.min' => 'Materi edukasi minimal berisi 20 karakter.',
        ]);

        $input = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama agar menghemat media penyimpanan
            if ($edukasi->gambar && Storage::disk('public')->exists($edukasi->gambar)) {
                Storage::disk('public')->delete($edukasi->gambar);
            }

            $file = $request->file('gambar');
            $pathFile = $file->store('edukasi', 'public');
            $input['gambar'] = $pathFile;
        } else {
            $input['gambar'] = $edukasi->gambar;
        }

        $edukasi->update($input);

        return redirect()->route('admin.edukasi.index')
            ->with('success', 'Konten edukasi lingkungan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $edukasi = Edukasi::findOrFail($id);

        // Hapus file fisik gambar yang terasosiasi
        if ($edukasi->gambar && Storage::disk('public')->exists($edukasi->gambar)) {
            Storage::disk('public')->delete($edukasi->gambar);
        }

        $edukasi->delete();

        return redirect()->route('admin.edukasi.index')
            ->with('success', 'Artikel edukasi berhasil dihapus secara permanen!');
    }
}