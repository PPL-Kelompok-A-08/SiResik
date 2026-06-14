<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah',
        'tipe',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sinkronisasi poin berdasarkan perubahan status permintaan penjemputan.
     */
    public static function syncFromPermintaan(\App\Models\PermintaanPenjemputan $permintaan): void
    {
        $trkId = 'TRK-' . str_pad($permintaan->id, 4, '0', STR_PAD_LEFT);
        $keterangan = 'Poin Layanan Penjemputan Sampah ' . $trkId;

        // Jika status berubah menjadi Selesai
        if ($permintaan->status === 'Selesai') {
            $exists = self::where('user_id', $permintaan->pengguna_id)
                ->where('tipe', 'masuk')
                ->where('keterangan', $keterangan)
                ->exists();

            if (!$exists && $permintaan->total_estimasi_poin > 0) {
                self::create([
                    'user_id' => $permintaan->pengguna_id,
                    'jumlah' => $permintaan->total_estimasi_poin,
                    'tipe' => 'masuk',
                    'keterangan' => $keterangan,
                    'tanggal' => $permintaan->diselesaikan_at ?? now(),
                ]);
            }
        }
        // Jika status berubah dari Selesai ke status lain (misal dibatalkan/revert)
        elseif ($permintaan->getOriginal('status') === 'Selesai') {
            self::where('user_id', $permintaan->pengguna_id)
                ->where('tipe', 'masuk')
                ->where('keterangan', $keterangan)
                ->delete();
        }
    }
}
