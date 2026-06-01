<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->where('user_id', auth()->id())->get();
        return view('wishlists.index', compact('wishlists'));
    }

    public function store(Product $product)
    {
        $exists = Wishlist::where('user_id', auth()->id())
                          ->where('product_id', $product->id)
                          ->exists();

        if ($exists) {
            return back()->with('error', 'Produk sudah ada di wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();
        return back()->with('success', 'Produk dihapus dari wishlist.');
    }

    /**
     * Add or remove a product from the user's wishlist.
     */
    public function toggle(Product $product)
    {
        $wishlistItem = Wishlist::where('user_id', auth()->id())
                                ->where('product_id', $product->id)
                                ->first();

        if ($wishlistItem) {
            // Jika produk sudah ada di wishlist, hapus.
            $wishlistItem->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        } else {
            // Jika belum ada, tambahkan.
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
            return back()->with('success', 'Produk ditambahkan ke wishlist.');
        }
    }
}