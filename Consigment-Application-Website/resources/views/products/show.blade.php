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