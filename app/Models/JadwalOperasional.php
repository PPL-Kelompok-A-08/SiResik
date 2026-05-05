<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalOperasional extends Model
{
    protected $fillable = [
        'titik_layanan_id',
        'hari',
        'jam_buka',
        'jam_tutup',
        'keterangan',
    ];

    protected $appends = ['status_buka'];

    /**
     * Get the titik layanan that owns the schedule.
     */
    public function titikLayanan(): BelongsTo
    {
        return $this->belongsTo(TitikLayanan::class);
    }

    /**
     * Accessor to check if currently open based on server time.
     */
    public function getStatusBukaAttribute(): bool
    {
        // Mendapatkan hari ini dalam bahasa Indonesia
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $hariIni = $hariMap[now()->format('l')] ?? null;
        
        // Cek hari (memungkinkan format 'Senin-Kamis' atau 'Senin')
        $hariMatched = false;
        $hariData = str_replace(' ', '', $this->hari);
        if (str_contains($hariData, '-')) {
            $parts = explode('-', $hariData);
            if (count($parts) == 2) {
                $days = array_values($hariMap);
                $startIdx = array_search(ucfirst(strtolower($parts[0])), $days);
                $endIdx = array_search(ucfirst(strtolower($parts[1])), $days);
                
                if ($startIdx !== false && $endIdx !== false) {
                    $currentIdx = array_search($hariIni, $days);
                    if ($startIdx <= $endIdx) {
                        $hariMatched = $currentIdx >= $startIdx && $currentIdx <= $endIdx;
                    } else {
                        // wrap around week
                        $hariMatched = $currentIdx >= $startIdx || $currentIdx <= $endIdx;
                    }
                }
            }
        } else {
            $hariMatched = strtolower(trim($this->hari)) === strtolower(trim($hariIni));
        }

        if (!$hariMatched) {
            return false;
        }

        $waktuSekarang = now()->format('H:i:s');
        return $waktuSekarang >= $this->jam_buka && $waktuSekarang <= $this->jam_tutup;
    }
}
