@extends('layouts.app') {{-- Pastikan layout ini sesuai dengan project Anda --}}

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Daftar Favorit</h1>

    <div class="bg-white shadow-md rounded p-4">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Ket</th>
                    <th>Harga</th>
                    <th>Warna</th>
                    <th>Stok Awal</th>
                    <th>Stok Akhir</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlists as $index => $item)
                <tr class="border-b">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Image" class="w-16 h-20 object-cover">
                    </td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ $item->warna }}</td>
                    <td>{{ $item->stok_awal }}</td>
                    <td>{{ $item->stok_akhir }}</td>
                    <td class="flex space-x-2 justify-center">
                        <form method="POST" action="{{ route('wishlist.destroy', $item->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">🗑️</button>
                        </form>
                        <form method="POST" action="{{ route('wishlist.toggle', $item->id) }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-blue-500">🤍</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
