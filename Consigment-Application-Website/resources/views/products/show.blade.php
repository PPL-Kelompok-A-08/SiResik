@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No image available</span>
                        </div>
                    @endif
                </div>
                <div class="md:w-1/2 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-blue-600 mb-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </h2>
                        <p class="text-gray-600">
                            Stock: {{ $product->stock }} units
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $product->description }}</p>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('products.edit', $product) }}" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            Edit Product
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('products.index') }}" class="text-blue-500 hover:text-blue-600">
                ← Back to Products
            </a>
        </div>
    </div>
</div>
@endsection 