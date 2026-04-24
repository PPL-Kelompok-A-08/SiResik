<?php

namespace App\Http\Controllers;

use App\Models\KategoriSampah;
use App\Models\TitikLayanan;
use App\Models\User;
use App\Models\PermintaanPenjemputan;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // === KATEGORI SAMPAH ===
    public function storeKategori(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_per_kg' => 'required|integer|min:0',
        ]);

        KategoriSampah::create($validated);

        return redirect()->back()->with('success', 'Kategori sampah berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, KategoriSampah $kategori): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_per_kg' => 'required|integer|min:0',
        ]);

        $kategori->update($validated);

        return redirect()->back()->with('success', 'Kategori sampah berhasil diperbarui.');
    }

    public function destroyKategori(KategoriSampah $kategori): RedirectResponse
    {
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori sampah berhasil dihapus.');
    }

    // === TITIK LAYANAN ===
    public function storeTitikLayanan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:tps,bank_sampah,usulan',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'jenis_sampah_diterima' => 'nullable|string',
        ]);

        TitikLayanan::create($validated);

        return redirect()->back()->with('success', 'Titik layanan berhasil ditambahkan.');
    }

    public function updateTitikLayanan(Request $request, TitikLayanan $titikLayanan): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:tps,bank_sampah,usulan',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'jenis_sampah_diterima' => 'nullable|string',
        ]);

        $titikLayanan->update($validated);

        return redirect()->back()->with('success', 'Titik layanan berhasil diperbarui.');
    }

    public function destroyTitikLayanan(TitikLayanan $titikLayanan): RedirectResponse
    {
        $titikLayanan->delete();

        return redirect()->back()->with('success', 'Titik layanan berhasil dihapus.');
    }

    // === PETUGAS ===
    public function storePetugas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas',
        ]);

        return redirect()->back()->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function updatePetugas(Request $request, User $petugas): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $petugas->id,
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $petugas->update($updateData);

        return redirect()->back()->with('success', 'Petugas berhasil diperbarui.');
    }

    public function destroyPetugas(User $petugas): RedirectResponse
    {
        $petugas->delete();

        return redirect()->back()->with('success', 'Petugas berhasil dihapus.');
    }

    // === VERIFIKASI LAPORAN ===
    public function verifikasiLaporan(Request $request, PermintaanPenjemputan $permintaan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $status = $validated['status'] === 'disetujui' ? 'Selesai' : 'Dibatalkan';

        $permintaan->update(['status' => $status]);

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi.');
    }

    // === KONFIGURASI POIN ===
    public function updateKonfigurasiPoin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'poin_minimal_tukar' => 'required|integer|min:0',
            'bonus_poin_bulanan' => 'required|integer|min:0',
            'maksimal_poin_harian' => 'required|integer|min:0',
        ]);

        // Simpan ke config atau database (untuk sekarang simpan ke session sebagai contoh)
        session([
            'poin_minimal_tukar' => $validated['poin_minimal_tukar'],
            'bonus_poin_bulanan' => $validated['bonus_poin_bulanan'],
            'maksimal_poin_harian' => $validated['maksimal_poin_harian'],
        ]);

        return redirect()->back()->with('success', 'Konfigurasi poin berhasil diperbarui.');
    }

    // === REWARD ===
    public function storeReward(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_diperlukan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Reward::create($validated);

        return redirect()->back()->with('success', 'Reward berhasil ditambahkan.');
    }

    public function updateReward(Request $request, Reward $reward): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_diperlukan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'aktif' => 'boolean',
        ]);

        $reward->update($validated);

        return redirect()->back()->with('success', 'Reward berhasil diperbarui.');
    }

    public function destroyReward(Reward $reward): RedirectResponse
    {
        $reward->delete();

        return redirect()->back()->with('success', 'Reward berhasil dihapus.');
    }
}
