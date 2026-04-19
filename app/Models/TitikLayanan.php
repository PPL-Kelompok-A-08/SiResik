<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitikLayanan extends Model
{
    protected $fillable = [
        'nama',
        'jenis',
        'latitude',
        'longitude',
        'alamat',
        'jam_operasional',
        'jenis_sampah_diterima',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }
}
