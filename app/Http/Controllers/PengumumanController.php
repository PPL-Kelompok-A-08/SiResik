<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::where('tanggal_publish', '<=', Carbon::today())
            ->orderBy('tanggal_publish', 'desc')
            ->paginate(10);
        return view('pengumuman.index', compact('pengumuman'));
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::where('tanggal_publish', '<=', Carbon::today())->findOrFail($id);
        return view('pengumuman.show', compact('pengumuman'));
    }
}