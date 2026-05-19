<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPenjemputan;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PetugasController extends Controller
{
    public function showBukti(PermintaanPenjemputan $permintaanPenjemputan): View
    {
        $user = auth()->user();

        // Hanya petugas yang ditugaskan yang bisa akses, atau admin
        abort_unless(
            $user->role === 'admin' ||
            ($user->role === 'petugas' && $permintaanPenjemputan->petugas_id === $user->id),
            403
        );

        $permintaanPenjemputan->load(['pengguna', 'items.kategoriSampah', 'petugas']);

        return view('petugas.bukti-penyelesaian', [
            'permintaan' => $permintaanPenjemputan,
            'user' => $user,
        ]);
    }

    public function uploadBukti(Request $request, PermintaanPenjemputan $permintaanPenjemputan): RedirectResponse
    {
        $user = auth()->user();

        abort_unless(
            $user->role === 'admin' ||
            ($user->role === 'petugas' && $permintaanPenjemputan->petugas_id === $user->id),
            403
        );

        abort_if($permintaanPenjemputan->status === 'Selesai', 403, 'Tugas sudah diselesaikan.');

        $validated = $request->validate([
            'bukti_foto' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // max 5MB
            'catatan_penyelesaian' => ['nullable', 'string', 'max:500'],
        ], [
            'bukti_foto.required' => 'Foto bukti penyelesaian wajib diunggah.',
            'bukti_foto.image' => 'File harus berupa gambar.',
            'bukti_foto.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'bukti_foto.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Hapus foto lama jika ada
        if ($permintaanPenjemputan->bukti_penyelesaian) {
            Storage::disk('public')->delete($permintaanPenjemputan->bukti_penyelesaian);
        }

        // Simpan foto baru
        $path = $request->file('bukti_foto')->store('bukti-penyelesaian', 'public');

        $permintaanPenjemputan->update([
            'bukti_penyelesaian' => $path,
            'catatan_penyelesaian' => $validated['catatan_penyelesaian'] ?? null,
            'status' => 'Selesai',
            'diselesaikan_at' => now(),
        ]);

        return redirect()
            ->route('petugas.bukti.show', $permintaanPenjemputan)
            ->with('success', 'Bukti penyelesaian berhasil diunggah. Tugas ditandai selesai!');
    }

    public function riwayat(Request $request): View
    {
        $user = auth()->user();

        $query = PermintaanPenjemputan::with(['pengguna', 'petugas', 'items.kategoriSampah'])
            ->latest('created_at');

        // Petugas hanya lihat yang ditugaskan ke dia
        if ($user->role === 'petugas') {
            $query->where('petugas_id', $user->id);
        }

        // Filter status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $permintaan = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => PermintaanPenjemputan::when($user->role === 'petugas', fn ($q) => $q->where('petugas_id', $user->id))->count(),
            'menunggu' => PermintaanPenjemputan::where('status', 'Menunggu')->when($user->role === 'petugas', fn ($q) => $q->where('petugas_id', $user->id))->count(),
            'diproses' => PermintaanPenjemputan::where('status', 'Diproses')->when($user->role === 'petugas', fn ($q) => $q->where('petugas_id', $user->id))->count(),
            'selesai' => PermintaanPenjemputan::where('status', 'Selesai')->when($user->role === 'petugas', fn ($q) => $q->where('petugas_id', $user->id))->count(),
        ];

        return view('petugas.riwayat', compact('user', 'permintaan', 'stats'));
    }

}
