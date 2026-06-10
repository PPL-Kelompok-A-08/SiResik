<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';
    protected $fillable = ['judul', 'deskripsi', 'lokasi', 'tanggal', 'kuota'];
    protected $casts = ['tanggal' => 'date'];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(PendaftaranKegiatan::class, 'kegiatan_id');
    }

    public function peserta(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pendaftaran_kegiatan', 'kegiatan_id', 'user_id')->withTimestamps();
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota - $this->pendaftaran()->count());
    }

    public function isPenuh(): bool
    {
        return $this->sisaKuota() <= 0;
    }

    public function isUserTerdaftar($userId): bool
    {
        if (!$userId) return false;
        return $this->pendaftaran()->where('user_id', $userId)->exists();
    }
}