<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.register');
});

// Route untuk Dashboard Marketplace
Route::get('/dashboard', function (Request $request) {
    $query = Product::with('user');

    // Filter
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Sorting
    if ($request->sort == 'price_asc') {
        $query->orderBy('price', 'asc');
    } elseif ($request->sort == 'price_desc') {
        $query->orderBy('price', 'desc');
    } else {
        $query->latest();
    }
    
    $products = $query->paginate(12)->withQueryString();
    $categories = Category::all();
    $wishlistedProductIds = Auth::check() ? Auth::user()->wishlists->pluck('product_id')->toArray() : [];

    return view('dashboard', compact('products', 'categories', 'wishlistedProductIds'));
    
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup Route yang Memerlukan Login
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist
    Route::get('/wishlists', [WishlistController::class, 'index'])->name('wishlists.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

require __DIR__.'/auth.php';