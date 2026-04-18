<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanPenjemputan extends Model
{
    protected $table = 'permintaan_penjemputan';

    protected $fillable = [
        'tanggal',
        'jadwal',
        'scheduled_at',
        'status',
        'alamat',
        'nomor_telepon',
        'catatan',
        'total_estimasi_poin',
        'pengguna_id',
        'petugas_id',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PermintaanPenjemputanItem::class, 'permintaan_penjemputan_id');
    }
}
