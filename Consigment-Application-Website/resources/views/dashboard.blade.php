<x-app-layout>
    <div class="bg-gray-100">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">

                <aside class="w-full md:w-1/4">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h3 class="font-bold text-lg mb-4">Filter</h3>
                        <form action="{{ route('dashboard') }}" method="GET">
                            <div class="space-y-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Produk</label>
                                    <input type="text" name="search" id="search" placeholder="Gitar Taylor..." value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </aside>

                <main class="w-full md:w-3/4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Jelajahi Produk</h2>
                      @if($products->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <a href="{{ route('products.show', $product) }}" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
                                    
                                    <div class="w-full h-48 bg-gray-200">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">[Tidak ada gambar]</div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <div class="flex justify-between items-start gap-2">
                                            <h4 class="font-bold text-lg truncate">{{ $product->name }}</h4>
                                            
                                            <button type="button" class="wishlist-toggle-btn p-1 text-gray-400 hover:text-red-500" data-product-id="{{ $product->id }}">
                                                <span class="sr-only">Tambah ke Wishlist</span>
                                                @if(Auth::check() && Auth::user()->wishlist->contains($product))
                                                    <i class="fas fa-heart text-red-500"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </button>
                                        </div>

                                        <p class="text-gray-900 font-semibold mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            @if($product->user)
                                                {{ $product->user->name }}
                                            @else
                                                Penjual
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <p class="text-gray-500">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wishlistButtons = document.querySelectorAll('.wishlist-toggle-btn');

            wishlistButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    const productId = this.getAttribute('data-product-id');
                    const icon = this.querySelector('i');

                    fetch("{{ route('wishlist.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 401) {
                                window.location.href = "{{ route('login') }}";
                            }
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            if (data.in_wishlist) {
                                icon.classList.remove('far');
                                icon.classList.add('fas', 'text-red-500');
                            } else {
                                icon.classList.remove('fas', 'text-red-500');
                                icon.classList.add('far');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    });
                });
            });
        });
    </script>
</x-app-layout>