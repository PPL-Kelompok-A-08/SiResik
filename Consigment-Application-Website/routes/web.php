<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
<<<<<<< Updated upstream
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
=======
use Illuminate\Support\Facades\Route;
use App\Models\Product; // <-- TAMBAHKAN IMPORT INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

Route::get('/', function () {
    return view('welcome');
});

// === ROUTE DASHBOARD YANG DIUBAH ===
Route::get('/dashboard', function () {
    // Ambil semua produk, dan sertakan data penjualnya (user)
    $products = Product::with('user')->latest()->get();
    
    // Kirim data products ke view
    return view('dashboard', compact('products'));

})->middleware(['auth', 'verified'])->name('dashboard');
// ===================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    
});

require __DIR__.'/auth.php';

