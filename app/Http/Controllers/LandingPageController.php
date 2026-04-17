<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index() {
        // Ambil data dummy untuk statistik
        $stats = [
            'total_user' => 2500,
            'total_sampah' => '15 Ton',
            'poin_terbagi' => '1.2 Juta',
            'petugas_aktif' => 48
        ];

        return view('landing', compact('stats'));
    }
}