<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    
    public function index()
    {
        $wishlist_products = Auth::user()->wishlist()->with('category')->get();
        return view('wishlist.index', ['wishlist' => $wishlist_products]);
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $userId = Auth::id();
        $productId = $request->input('product_id');
        $wishlistTable = 'product_user_wishlist';
        $existing = DB::table($wishlistTable)->where('user_id', $userId)->where('product_id', $productId)->first();
        $isInWishlist = false;
        if ($existing) {
            DB::table($wishlistTable)->where('id', $existing->id)->delete();
            $isInWishlist = false;
        } else {
            DB::table($wishlistTable)->insert(['user_id' => $userId, 'product_id' => $productId, 'created_at' => now(),'updated_at' => now(),]);
            $isInWishlist = true;
        }
        return response()->json(['status' => 'success', 'in_wishlist' => $isInWishlist]);
    }

    // === METHOD BARU UNTUK HALAMAN KONFIRMASI ===
    public function confirmDelete(Product $product)
    {
        return view('wishlist.delete', compact('product'));
    }

    // === METHOD BARU UNTUK MENGHAPUS DATA ===
    public function destroy(Product $product)
    {
        // Menghapus relasi antara user yang login dengan produk ini
        Auth::user()->wishlist()->detach($product->id);

        // Redirect kembali ke halaman daftar wishlist dengan pesan sukses
        return redirect()->route('wishlist.index')->with('success', 'Produk berhasil dihapus dari wishlist.');
    }
}