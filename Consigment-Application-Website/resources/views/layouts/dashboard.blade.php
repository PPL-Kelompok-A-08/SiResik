<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-2xl font-bold mb-6">Jelajahi Produk</h3>
                    
                    {{-- Grid untuk Kartu Produk --}}
                    @if($products->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($products as $product)
                                <a href="{{ route('products.show', $product) }}" class="block border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
                                    {{-- Gambar Produk --}}
                                    <div class="w-full h-48 bg-gray-200">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{-- Placeholder jika tidak ada gambar --}}
                                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                [Gambar tidak tersedia]
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Detail Produk di Kartu --}}
                                    <div class="p-4">
                                        <h4 class="font-bold text-lg truncate">{{ $product->name }}</h4>
                                        <p class="text-gray-800 font-semibold mt-2">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        {{-- Pemeriksaan aman untuk nama penjual --}}
                                        <p class="text-sm text-gray-500 mt-1">
                                            @if($product->user)
                                                {{ $product->user->name }}
                                            @else
                                                Penjual tidak terdaftar
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p>Belum ada produk untuk ditampilkan.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>