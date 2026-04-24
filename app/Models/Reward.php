<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'poin_diperlukan',
        'stok',
        'aktif'
    ];
}
