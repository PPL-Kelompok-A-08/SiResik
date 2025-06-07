<?php

namespace App\Models;

// ... use statements
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Tambahkan ini
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
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

    // --- GANTI FUNGSI DI BAWAH INI ---
    /*
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    */

    // --- DENGAN FUNGSI YANG BENAR INI ---
    /**
     * The products that the user has added to their wishlist.
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_user_wishlist')->withTimestamps();
    }
}