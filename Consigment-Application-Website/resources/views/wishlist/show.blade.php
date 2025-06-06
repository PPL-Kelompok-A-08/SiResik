@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Wishlist Saya</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse ($wishlists as $wishlist)
        <div class="border p-2 mb-2 d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $wishlist->product->name }}</strong><br>
                Rp{{ number_format($wishlist->product->price, 0, ',', '.') }}
            </div>
            <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
        </div>
    @empty
        <p>Belum ada produk di wishlist.</p>
    @endforelse
</div>
@endsection
