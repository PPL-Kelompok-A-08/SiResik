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
        'total_tagihan',
    ];

    public function permintaanPenjemputan(): BelongsTo
    {
        return $this->belongsTo(PermintaanPenjemputan::class, 'permintaan_penjemputan_id');
    }

    public function kategoriSampah(): BelongsTo
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_sampah_id');
    }

    /**
     * Hitung total tagihan berdasarkan berat dan harga per kg
     */
    public function hitungTagihan(): void
    {
        if ($this->kategoriSampah) {
            $this->total_tagihan = $this->berat_kg * $this->kategoriSampah->harga_per_kg;
        }
    }

    /**
     * Boot model untuk auto-calculate tagihan sebelum save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->hitungTagihan();
        });

        static::saved(function ($model) {
            // Update total tagihan di permintaan_penjemputan setelah item disimpan
            if ($model->permintaanPenjemputan) {
                $model->permintaanPenjemputan->hitungTotalTagihan();
                $model->permintaanPenjemputan->save();
            }
        });
    }
}
