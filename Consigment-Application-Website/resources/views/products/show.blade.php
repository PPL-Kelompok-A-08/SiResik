<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
            Detail Produk
        </h2>
    </x-slot>

    {{-- Inisialisasi Alpine.js untuk mengontrol modal --}}
    <div x-data="{ reviewModalOpen: false }" class="py-12 bg-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Menampilkan pesan sukses/error dari session --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Kartu Detail Produk --}}
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
                        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                        
                        {{-- Tampilan Bintang Rating Rata-rata --}}
                        <div class="flex items-center my-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $product->averageRating() ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                            <span class="text-gray-600 ml-2 text-sm">({{ $product->reviews->count() }} ulasan)</span>
                        </div>
                        
                        <p class="text-3xl font-bold text-gray-800">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                        
                        <div class="mt-auto pt-6 space-y-3">
                             {{-- Tombol Hubungi Penjual & Pemicu Pop-up --}}
                            @if($product->user && $product->user->phone_number)
                                <button @click="window.open('https://wa.me/{{ $product->user->phone_number }}?text={{ urlencode('Halo, saya tertarik dengan produk ' . $product->name) }}', '_blank'); reviewModalOpen = true" 
                                   class="w-full flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded text-center transition-colors duration-300">
                                    Hubungi Penjual via WhatsApp
                                </button>
                            @else
                                <button class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded cursor-not-allowed">
                                    Kontak Penjual Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Daftar Ulasan --}}
            <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4">Ulasan Produk</h3>
                <div class="space-y-6">
                    @forelse ($product->reviews()->latest()->get() as $review)
                        <div class="flex space-x-4">
                            <div class="flex-shrink-0">
                                <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                    {{-- Placeholder untuk foto profil user --}}
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </span>
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <p class="font-semibold text-sm">{{ $review->user->name ?? 'Pengguna' }}</p>
                                    <div class="flex items-center ml-4">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600 mt-1">{{ $review->comment }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pop-up Modal untuk Form Review -->
        <div x-show="reviewModalOpen" @keydown.escape.window="reviewModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="reviewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="reviewModalOpen = false" aria-hidden="true"></div>
                <!-- Modal panel -->
                <div x-show="reviewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                        @csrf
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Berikan Ulasan Anda</h3>
                        <div class="mt-4">
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                            <select id="rating" name="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="5">5 Bintang</option>
                                <option value="4">4 Bintang</option>
                                <option value="3">3 Bintang</option>
                                <option value="2">2 Bintang</option>
                                <option value="1">1 Bintang</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">Komentar</label>
                            <textarea id="comment" name="comment" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Tulis ulasan Anda di sini..."></textarea>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                Kirim Ulasan
                            </button>
                            <button type="button" @click="reviewModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                Nanti Saja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
