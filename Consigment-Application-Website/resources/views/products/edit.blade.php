<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Edit Informasi Produk</h3>
                        <p class="text-sm text-gray-500 mb-6">Perbarui detail untuk produk "{{ $product->name }}"</p>

                        {{-- Menampilkan error validasi --}}
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg" role="alert">
                                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            
                            {{-- Kolom Kiri --}}
                            <div class="space-y-6">
                                {{-- Nama Produk --}}
                                <div>
                                    <x-input-label for="name" :value="__('Nama Produk')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required />
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <x-input-label for="category_id" :value="__('Kategori')" />
                                    <select name="category_id" id="category_id" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="" disabled>Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <x-input-label for="description" :value="__('Deskripsi')" />
                                    <textarea name="description" id="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="space-y-6">
                                {{-- Harga --}}
                                <div>
                                    <x-input-label for="price" :value="__('Harga')" />
                                    <div class="relative mt-1">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <x-text-input type="number" name="price" id="price" class="block w-full pl-8" :value="old('price', $product->price)" required step="1" />
                                    </div>
                                </div>

                                {{-- Stok --}}
                                <div>
                                    <x-input-label for="stock" :value="__('Stok')" />
                                    <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', $product->stock)" required />
                                </div>

                                {{-- Gambar --}}
                                <div>
                                    <x-input-label for="image" :value="__('Gambar Produk')" />
                                    <div class="mt-2 flex items-center space-x-4">
                                        <img id="image_preview" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/128x128/e2e8f0/e2e8f0?text=No+Image' }}" alt="Gambar saat ini" class="w-24 h-24 object-cover rounded-md bg-gray-100">
                                        <label for="image" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <span>Ubah Gambar</span>
                                            <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(event)">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer dengan Tombol Aksi --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center gap-4">
                        <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Batal
                        </a>
                        <x-primary-button type="submit">
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('image_preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>
