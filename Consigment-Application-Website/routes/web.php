<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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

Route::get('/', function () {
    return view('welcome');
});

// === ROUTE DASHBOARD MARKETPLACE YANG SUDAH DIPERBAIKI ===
Route::get('/dashboard', function (Request $request) {
    // Mulai query untuk mengambil produk beserta data penjualnya
    $query = Product::with('user');

    // Terapkan filter jika ada input pencarian
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Terapkan filter jika ada kategori yang dipilih
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    
    // Ambil data produk dengan paginasi dan urutkan dari yang terbaru
    $products = $query->latest()->paginate(12);
    
    // Ambil semua kategori untuk ditampilkan di sidebar filter
    $categories = Category::all();

    // Kirim semua data yang dibutuhkan ke view 'dashboard'
    return view('dashboard', compact('products', 'categories'));
    
})->middleware(['auth', 'verified'])->name('dashboard');
// ========================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle');
    // Rute untuk menampilkan halaman konfirmasi hapus (method GET)
    Route::get('/wishlist/{product}/delete', [WishlistController::class, 'confirmDelete'])->name('wishlist.confirm_delete');
    // Rute untuk memproses penghapusan data (method DELETE)
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

require __DIR__.'/auth.php';