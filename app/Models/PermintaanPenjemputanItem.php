<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanPenjemputanItem extends Model
{
    protected $table = 'permintaan_penjemputan_items';

    protected $fillable = [
        'permintaan_penjemputan_id',
        'kategori_sampah_id',
        'berat_kg',
        'estimasi_poin',
    ];

    public function permintaanPenjemputan(): BelongsTo
    {
        return $this->belongsTo(PermintaanPenjemputan::class, 'permintaan_penjemputan_id');
    }

    public function kategoriSampah(): BelongsTo
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_sampah_id');
    }
}
