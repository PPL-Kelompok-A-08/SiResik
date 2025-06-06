<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the wishlist.
     */
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Show the form to create a new wishlist entry.
     */
    public function create()
    {
        $products = Product::all();
        return view('wishlist.create', compact('products'));
    }

    /**
     * Store a new wishlist item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
        }

        return redirect()->route('wishlist.index')
            ->with('success', 'Produk berhasil ditambahkan ke wishlist.');
    }

    /**
     * Display a specific wishlist item.
     */
    public function show(Wishlist $wishlist)
    {
        $this->authorizeAccess($wishlist);

        return view('wishlist.show', compact('wishlist'));
    }

    /**
     * Show the form to edit a wishlist item.
     */
    public function edit(Wishlist $wishlist)
    {
        $this->authorizeAccess($wishlist);

        $products = Product::all();
        return view('wishlist.edit', compact('wishlist', 'products'));
    }

    /**
     * Update a wishlist item.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        $this->authorizeAccess($wishlist);

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist->update([
            'product_id' => $request->product_id
        ]);

        return redirect()->route('wishlist.index')
            ->with('success', 'Wishlist berhasil diperbarui.');
    }

    /**
     * Remove a wishlist item.
     */
    public function destroy(Wishlist $wishlist)
    {
        $this->authorizeAccess($wishlist);

        $wishlist->delete();

        return redirect()->route('wishlist.index')
            ->with('success', 'Wishlist berhasil dihapus.');
    }

    /**
     * Check that the wishlist belongs to the authenticated user.
     */
    protected function authorizeAccess(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
