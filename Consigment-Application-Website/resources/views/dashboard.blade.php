<x-app-layout>
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row gap-8">

                <!-- Sidebar Filter Fungsional -->
                <aside class="w-full md:w-72 flex-shrink-0">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-xl text-gray-800">Filter Products</h3>
                            <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                Reset All
                            </a>
                        </div>
                        
                        <form id="filter-form" action="{{ route('dashboard') }}" method="GET" class="space-y-6">
                            <!-- Input tersembunyi untuk sorting -->
                            <input type="hidden" name="sort" id="sort-input" value="{{ request('sort') }}">

                            <!-- Filter Pencarian -->
                            <div class="relative">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" id="search" placeholder="Gitar Taylor..." value="{{ request('search') }}" 
                                       class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>

                            <!-- Filter Kategori -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" id="category" 
                                    class="mt-1 block w-full rounded-lg border border-gray-300 py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Rentang Harga -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Price Range</label>
                                <div class="flex items-center space-x-4">
                                    <input type="number" name="min_price" id="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full pl-3 pr-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <span class="text-gray-400">to</span>
                                    <input type="number" name="max_price" id="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full pl-3 pr-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Konten Utama -->
                <main class="flex-1">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Explore Products</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $products->total() }} products found</p>
                        </div>
                        
                        <div class="flex items-center">
                            <label for="sort" class="text-sm font-medium text-gray-700 mr-2">Sort by:</label>
                            <select id="sort" class="rounded-lg border border-gray-300 py-1.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    @if($products->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <div class="group relative bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                                    <a href="{{ route('products.show', $product) }}" class="block">
                                        <div class="w-full aspect-square bg-gray-50 overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                     <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-bold text-lg truncate">{{ $product->name }}</h4>
                                            <p class="text-gray-900 font-semibold text-lg mt-2">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </a>
                                    
                                    <!-- === Tombol Wishlist yang Hilang === -->
                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-4 right-4">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white transition-all shadow-sm hover:shadow-md" aria-label="Add to wishlist">
                                            @if(isset($wishlistedProductIds) && in_array($product->id, $wishlistedProductIds))
                                                <!-- Ikon Hati Penuh -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <!-- Ikon Hati Kosong -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <!-- ======================================= -->
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-10">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                            <p class="mt-2 text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filter-form');
            const sortSelect = document.getElementById('sort');
            const sortInput = document.getElementById('sort-input');

            // Fungsi untuk mensubmit form ketika dropdown sort diubah
            sortSelect.addEventListener('change', function() {
                sortInput.value = this.value;
                filterForm.submit();
            });
        });
    </script>
    @endpush
</x-app-layout>
