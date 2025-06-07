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

                    <h3 class="text-2xl font-bold mb-6">Barang Favorit Anda</h3>
                    
                    @if($wishlists->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($wishlists as $item)
                                {{-- Pastikan relasi product tidak null sebelum ditampilkan --}}
                                @if($item->product) 
                                <div class="relative border border-gray-200 rounded-lg overflow-hidden shadow-sm group">
                                    <a href="{{ route('products.show', $item->product) }}" class="block">
                                        <div class="w-full h-48 bg-gray-200">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">[Tidak ada gambar]</div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-bold text-lg truncate">{{ $item->product->name }}</h4>
                                            <p class="text-gray-800 font-semibold mt-2">Rp{{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </a>
                                    <!-- Tombol Hapus dari Wishlist -->
                                    <form action="{{ route('wishlist.destroy', $item) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
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
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
