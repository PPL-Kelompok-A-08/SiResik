<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wishlist Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

<<<<<<< HEAD:Consigment-Application-Website/resources/views/wishlist/index.blade.php
                    @if($wishlist->isEmpty())
                        <div class="text-center py-16">
                            <p class="text-gray-500">Wishlist Anda masih kosong.</p>
                            <a href="{{ route('dashboard') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Jelajahi Produk
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($wishlist as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded">
                                                @else
                                                    <div class="h-12 w-12 bg-gray-200 rounded"></div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-4">
                                                    <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                                    <a href="{{ route('wishlist.confirm_delete', $product) }}" class="text-red-600 hover:text-red-900">Hapus</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
=======
                    <h3 class="text-2xl font-bold mb-6">Barang Favorit Anda</h3>
                    
                    @if($wishlists->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($wishlists as $item)
                                @if($item->product)
                                <div class="relative border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <a href="{{ route('products.show', $item->product) }}" class="block">
                                        <div class="w-full h-48 bg-gray-200">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-bold text-lg truncate">{{ $item->product->name }}</h4>
                                            <p class="text-gray-800 font-semibold mt-2">Rp{{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('wishlist.destroy', $item) }}" method="POST" class="absolute top-2 right-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-white/70 hover:bg-white p-2 rounded-full text-red-500" title="Hapus dari wishlist">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p>Anda belum menambahkan barang apapun ke wishlist.</p>
>>>>>>> adinar:Consigment-Application-Website/resources/views/wishlists/index.blade.php
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>