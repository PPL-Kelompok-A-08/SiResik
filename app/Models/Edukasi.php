<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Edukasi extends Model
{
    use HasFactory;

    protected $table = 'edukasis';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'gambar',
        'isi',
    ];

    /**
     * Boot function untuk auto-generate slug unik saat membuat & mengubah judul artikel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($edukasi) {
            $slug = Str::slug($edukasi->judul);
            
            // Cek duplikasi slug agar tetap unik
            $existingCount = static::where('slug', 'LIKE', $slug . '%')->count();
            if ($existingCount > 0) {
                $slug = $slug . '-' . ($existingCount + 1);
            }
            
            $edukasi->slug = $slug;
        });

        static::updating(function ($edukasi) {
            if ($edukasi->isDirty('judul')) {
                $slug = Str::slug($edukasi->judul);
                $existingCount = static::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $edukasi->id)->count();
                if ($existingCount > 0) {
                    $slug = $slug . '-' . ($existingCount + 1);
                }
                $edukasi->slug = $slug;
            }
        });
    }

    /**
     * Scope pencarian untuk mempermudah query filter di controller.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('judul', 'like', '%' . $search . '%')
                     ->orWhere('kategori', 'like', '%' . $search . '%')
                     ->orWhere('isi', 'like', '%' . $search . '%');
    }
}