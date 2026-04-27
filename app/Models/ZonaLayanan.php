<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZonaLayanan extends Model
{
    protected $table = 'zona_layanans';

    protected $fillable = [
        'nama',
        'warna',
        'geojson',
    ];

    protected function casts(): array
    {
        return [
            'geojson' => 'array',
        ];
    }
}

