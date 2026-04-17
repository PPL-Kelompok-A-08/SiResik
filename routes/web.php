<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\KategoriSampahController;


Route::get('/', [LandingPageController::class, 'index']);
Route::get('/kategori', [KategoriSampahController::class, 'index']);
Route::get('/kategori/{id}', [KategoriSampahController::class, 'show']);
Route::post('/kategori/hitung', [KategoriSampahController::class, 'hitung']);
Route::get('/maps', fn() => view('maps'));
Route::get('/reward', fn() => view('reward'));