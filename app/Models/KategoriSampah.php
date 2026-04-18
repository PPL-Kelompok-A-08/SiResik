<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSampah extends Model
{
    protected $table = 'kategori_sampah';

    protected $fillable = [
        'nama',
        'deskripsi',
        'poin_per_kg'
    ];

    public function permintaanItems(): HasMany
    {
        return $this->hasMany(PermintaanPenjemputanItem::class, 'kategori_sampah_id');
    }
}
