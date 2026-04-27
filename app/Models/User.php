<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function permintaanPenjemputan(): HasMany
    {
        return $this->hasMany(PermintaanPenjemputan::class, 'pengguna_id');
    }

    public function tugasPenjemputan(): HasMany
    {
        return $this->hasMany(PermintaanPenjemputan::class, 'petugas_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function poins(): HasMany
    {
        return $this->hasMany(Poin::class);
    }

    public function penukaranPoins(): HasMany
    {
        return $this->hasMany(PenukaranPoin::class);
    }
}
