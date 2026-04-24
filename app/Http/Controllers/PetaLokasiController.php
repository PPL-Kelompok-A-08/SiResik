<?php

namespace App\Http\Controllers;

use App\Models\UsulanTitikLayanan;
use App\Models\TitikLayanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PetaLokasiController extends Controller
{
    public function masyarakat(): View
    {
        $user = auth()->user();
        $titikLayanan = TitikLayanan::orderBy('nama')->get();

        return view('peta.lokasi-masyarakat', compact('user', 'titikLayanan'));
    }

    public function admin(): View
    {
        $user = auth()->user();
        $titikLayanan = TitikLayanan::orderBy('nama')->get();
        $usulanMenunggu = UsulanTitikLayanan::with('pengusul')
            ->where('status', 'diajukan')
            ->latest()
            ->get();

        return view('peta.lokasi-admin', compact('user', 'titikLayanan', 'usulanMenunggu'));
    }

    public function usulanForm(): View
    {
        $user = auth()->user();

        return view('peta.usulan-titik', compact('user'));
    }

    public function storeUsulan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'alamat_detail' => ['required', 'string', 'max:500'],
            'jenis_layanan' => ['required', 'in:tps,bank_sampah'],
            'deskripsi_alasan' => ['required', 'string', 'max:1000'],
        ], [
            'latitude.required' => 'Lokasi di peta wajib dipilih.',
            'longitude.required' => 'Lokasi di peta wajib dipilih.',
        ]);

        $user = auth()->user();

        UsulanTitikLayanan::create([
            'user_id' => $user->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'alamat_detail' => $validated['alamat_detail'],
            'jenis_layanan' => $validated['jenis_layanan'],
            'deskripsi_alasan' => $validated['deskripsi_alasan'],
            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('peta.usulan-titik')
            ->with('success', 'Usulan lokasi berhasil disimpan dan menunggu verifikasi admin.');
    }

    public function approveUsulan(UsulanTitikLayanan $usulan): RedirectResponse
    {
        if ($usulan->status !== 'diajukan') {
            return redirect()
                ->route('dashboard.admin.peta')
                ->with('error', 'Usulan ini sudah diproses sebelumnya.');
        }

        $jenisLabel = $usulan->jenis_layanan === 'bank_sampah' ? 'Bank Sampah' : 'Titik Sampah';
        $jenisTitik = $usulan->jenis_layanan === 'bank_sampah' ? 'bank_sampah' : 'tps';

        DB::transaction(function () use ($usulan, $jenisLabel, $jenisTitik): void {
            TitikLayanan::create([
                'nama' => 'Usulan ' . $jenisLabel . ' - ' . Str::of($usulan->alamat_detail)->limit(40, ''),
                'jenis' => $jenisTitik,
                'latitude' => $usulan->latitude,
                'longitude' => $usulan->longitude,
                'alamat' => $usulan->alamat_detail,
                'jam_operasional' => null,
                'jenis_sampah_diterima' => null,
            ]);

            $usulan->update([
                'status' => 'disetujui',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'catatan_verifikasi' => null,
            ]);
        });

        return redirect()
            ->route('dashboard.admin.peta')
            ->with('success', 'Usulan berhasil disetujui dan sudah menjadi titik layanan aktif.');
    }

    public function rejectUsulan(Request $request, UsulanTitikLayanan $usulan): RedirectResponse
    {
        if ($usulan->status !== 'diajukan') {
            return redirect()
                ->route('dashboard.admin.peta')
                ->with('error', 'Usulan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_verifikasi' => ['nullable', 'string', 'max:500'],
        ]);

        $usulan->update([
            'status' => 'ditolak',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
        ]);

        return redirect()
            ->route('dashboard.admin.peta')
            ->with('success', 'Usulan ditolak dan tidak ditambahkan ke titik layanan.');
    }

    public function titikLayananJson(): JsonResponse
    {
        $data = TitikLayanan::orderBy('nama')->get();

        return response()->json($data);
    }
}
