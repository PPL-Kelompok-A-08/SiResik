@extends('layouts.dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Produk</h2>
        <a href="{{ route('products.create') }}" class="bg-white border border-blue-500 text-blue-600 font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition flex items-center md:ml-auto">
            <span class="text-xl mr-2">+</span> Tambah Produk
        </a>
    </div>
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-4">
        <div class="flex items-center gap-2">
            <form method="GET" action="" class="flex items-center gap-2">
                <label for="perPage" class="text-gray-700 text-sm">Show</label>
                <select name="perPage" id="perPage" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span class="text-gray-700 text-sm ml-1">entries</span>
            </form>
        </div>
        <div class="flex-1 flex justify-center md:justify-end">
            <form method="GET" action="" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search produk..." class="border rounded px-3 py-1 text-sm w-full md:w-64">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Search</button>
            </form>
        </div>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Foto</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Kategori</th>
                    <th class="px-4 py-2 border">Deskripsi</th>
                    <th class="px-4 py-2 border">Harga</th>
                    <th class="px-4 py-2 border">Stok</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                <tr class="text-center">
                    <td class="px-4 py-2 border">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td class="px-4 py-2 border">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded mx-auto">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded mx-auto text-gray-400">Foto</div>
                        @endif
                    </td>
                    <td class="px-4 py-2 border font-semibold">{{ $product->name }}</td>
                    <td class="px-4 py-2 border">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-2 border text-left">{{ Str::limit($product->description, 60) }}</td>
                    <td class="px-4 py-2 border font-semibold text-blue-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">{{ $product->stock }}</td>
                    <td class="px-4 py-2 border">
                        <div class="flex flex-col space-y-1 items-center">
                            <a href="{{ route('products.show', $product) }}" class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200 text-sm mb-1">Detail</a>
                            <a href="{{ route('products.edit', $product) }}" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 text-sm mb-1">Ubah</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 text-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection 