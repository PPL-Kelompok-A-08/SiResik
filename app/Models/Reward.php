<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'poin_diperlukan',
        'stok',
        'aktif'
    ];

    public function penukaranPoins(): HasMany
    {
        return $this->hasMany(PenukaranPoin::class);
    }
}
