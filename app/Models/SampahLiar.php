<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampahLiar extends Model
{
    protected $fillable = [
        'pengguna_id',
        'latitude',
        'longitude',
        'lokasi',
        'deskripsi',
        'foto',
        'status',
        'jumlah_estimasi',
        'catatan_admin',
        'petugas_id',
        'bukti_penanganan',
        'ditangani_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'ditangani_at' => 'datetime',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
