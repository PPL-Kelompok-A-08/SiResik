<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSampah extends Model
{
    protected $table = 'kategori_sampah';

    protected $fillable = [
        'nama',
        'deskripsi',
        'poin_per_kg'
    ];
}