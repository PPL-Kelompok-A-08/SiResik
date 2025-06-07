<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import jika digunakan

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // === UBAH BAGIAN INI ===
        // Mulai query dengan mengambil produk milik user yang login saja
        $query = Product::with('category')->where('user_id', auth()->id()); 
        // ========================
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhereHas('category', function($cat) use ($search) {
                      $cat->where('name', 'like', "%$search%");
                  });
            });
        }
    
        $perPage = $request->get('perPage', 10);
        $products = $query->latest()->paginate($perPage);
        $categories = Category::all(); // Tetap ambil semua kategori untuk filter
    
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Tambahkan ID user yang sedang login
        $data['user_id'] = auth()->id();

        // Pastikan folder upload ada
        $uploadPath = storage_path('app/public/products');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            try {
                $image->move(storage_path('app/public/products'), $imageName);
                $data['image'] = 'products/' . $imageName;
            } catch (\Exception $e) {
                Log::error('Gagal upload gambar: ' . $e->getMessage());
                return redirect()->back()
                    ->withErrors(['image' => 'Gagal upload gambar. Pastikan folder storage bisa ditulis.'])
                    ->withInput();
            }
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Pastikan data user juga di-load untuk halaman detail
        $product->load('user', 'category');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            try {
                $image->move(storage_path('app/public/products'), $imageName);
                $data['image'] = 'products/' . $imageName;
            } catch (\Exception $e) {
                Log::error('Gagal upload gambar: ' . $e->getMessage());
                return redirect()->back()
                    ->withErrors(['image' => 'Gagal upload gambar. Pastikan folder storage bisa ditulis.'])
                    ->withInput();
            }
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * API: Get all products
     */
    public function apiIndex()
    {
        $products = Product::with('category')->latest()->get();
        return response()->json($products);
    }

    /**
     * API: Get specific product
     */
    public function apiShow(Product $product)
    {
        return response()->json($product->load('category'));
    }
}
