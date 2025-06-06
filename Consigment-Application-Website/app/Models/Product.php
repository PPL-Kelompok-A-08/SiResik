<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id', // Pastikan kolom ini ada di tabel database Anda
        'image',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
<<<<<<< Updated upstream

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the average rating for the product.
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}
=======
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
>>>>>>> Stashed changes
