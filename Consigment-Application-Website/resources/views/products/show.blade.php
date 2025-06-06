<<<<<<< Updated upstream
@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1>{{ $product->name }}</h1>

    @if ($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="200" class="mb-3">
    @endif

    <p><strong>Harga:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
    <p><strong>Stok:</strong> {{ $product->stock }}</p>
    <p><strong>Deskripsi:</strong><br> {{ $product->description }}</p>
    <p><strong>Rating:</strong>
        @if ($product->averageRating())
            {{ number_format($product->averageRating(), 1) }} / 5
        @else
            Belum ada rating
        @endif
    </p>
    <form action="{{ route('wishlist.store', $product) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-outline-danger btn-sm">❤️ Simpan ke Wishlist</button>
    </form>

    <h4 class="mt-4">Ulasan Pengguna:</h4>
    @forelse ($reviews as $review)
        <div class="border p-2 mb-2">
            <strong>{{ $review->user->name }}</strong> -
            <span>Rating: {{ $review->rating }} / 5</span>
            <p>{{ $review->comment }}</p>
        </div>
    @empty
        <p>Belum ada komentar.</p>
    @endforelse

    @if (auth()->check())
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reviews.store', $product) }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-2">
                <label for="rating">Rating (1-5)</label>
                <select name="rating" class="form-select" required>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-2">
                <label for="comment">Komentar</label>
                <textarea name="comment" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">Login</a> untuk memberikan komentar.</p>
    @endif

    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-4">Kembali</a>
</div>
@endsection
=======
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="md:flex">
                    
                    {{-- Kolom Kiri: Gambar Produk --}}
                    <div class="md:w-1/2">
                        <div class="w-full h-96 bg-gray-200">
                             @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    [Gambar tidak tersedia]
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kolom Kanan: Detail dan Tombol Aksi --}}
                    <div class="md:w-1/2 p-6 flex flex-col">
                        
                        {{-- Nama dan Kategori --}}
                        <div class="flex justify-between items-start mb-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                            {{-- Pemeriksaan aman untuk nama kategori --}}
                            @if($product->category)
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                        </div>
                        
                        {{-- Harga --}}
                        <p class="text-3xl font-bold text-gray-800 my-3">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                        {{-- Detail Penjual --}}
                        <div class="text-sm text-gray-600 mb-4">
                            Dijual oleh: 
                            <span class="font-semibold">
                                @if($product->user)
                                    {{ $product->user->name }}
                                @else
                                    Penjual tidak terdaftar
                                @endif
                            </span>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                        
                        {{-- Tombol WhatsApp (Bagian Paling Penting) --}}
                        <div class="mt-auto pt-6">
                            @if($product->user && $product->user->phone_number)
                                <a href="https://wa.me/{{ $product->user->phone_number }}?text={{ urlencode('Halo, saya tertarik dengan produk ' . $product->name . ' yang ada di marketplace.') }}" 
                                   target="_blank" 
                                   class="w-full flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded text-center transition-colors duration-300">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M16.75 13.96c.25.13.41.2.52.34.11.14.15.33.11.52s-.14.34-.3.5c-.16.16-.35.28-.56.34-.21.06-.46.06-.74 0-.28-.06-.51-.13-.7-.22-.19-.09-.4-.2-.64-.34-.24-.14-.52-.33-.84-.56-.32-.23-.66-.52-1.02-.86s-.72-.73-1.08-1.15c-.36-.42-.66-.88-.9-1.37s-.42-.98-.52-1.48c-.1-.5.04-1.03.11-1.2.07-.17.15-.3.25-.38.1-.08.2-.13.3-.15.1-.02.2-.02.3 0 .1.02.2.04.28.08.08.04.15.1.2.15s.1.1.13.15.04.1.04.14c0 .05-.01.1-.04.16s-.05.1-.08.14c-.03.04-.05.06-.08.08-.03.02-.05.04-.08.06l-.3.2c-.1.06-.15.13-.15.22s.01.16.04.24c.03.08.06.14.1.2.04.06.1.13.16.2.06.07.14.14.22.22.08.08.18.16.28.24.1.08.2.14.3.2.1.06.2.1.3.14.1.04.18.06.26.08.08.02.16.03.24.03.1 0 .18-.01.25-.04.07-.03.14-.06.2-.1.06-.04.1-.08.15-.13.05-.05.1-.1.14-.14s.08-.08.1-.1c.02-.02.04-.04.06-.05.02-.01.04-.02.06-.02h.1c.05 0 .1.01.14.02.04.01.08.04.1.06.02.02.05.05.06.08.01.03.02.06.02.1z M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                    Hubungi Penjual
                                </a>
                            @else
                                <button class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded cursor-not-allowed">
                                    Kontak Penjual Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
>>>>>>> Stashed changes
