<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::withCount('pendaftaran')->orderBy('tanggal', 'asc')->paginate(9);
        return view('kegiatan.index', compact('kegiatan'));
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::withCount('pendaftaran')->findOrFail($id);
        $sudahDaftar = Auth::check() ? $kegiatan->isUserTerdaftar(Auth::id()) : false;
        return view('kegiatan.show', compact('kegiatan', 'sudahDaftar'));
    }

    public function daftar(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $userId = Auth::id();

        if ($kegiatan->tanggal->isPast() && !$kegiatan->tanggal->isToday()) {
            return redirect()->back()->with('error', 'Pendaftaran gagal karena agenda kegiatan sudah selesai.');
        }
        if ($kegiatan->isUserTerdaftar($userId)) {
            return redirect()->back()->with('warning', 'Anda sudah mengamankan kuota kegiatan ini sebelumnya.');
        }
        if ($kegiatan->isPenuh()) {
            return redirect()->back()->with('error', 'Pendaftaran dibatasi karena kuota penuh.');
        }

        PendaftaranKegiatan::create(['user_id' => $userId, 'kegiatan_id' => $id]);
        return redirect()->route('kegiatan.show', $id)->with('success', 'Selamat! Anda sukses terdaftar untuk kegiatan: ' . $kegiatan->judul);
    }
}