<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
            Detail Produk
        </h2>
    </x-slot>

    <div x-data="{ reviewModalOpen: false }" class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
             @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Kolom Kiri: Gambar & Info Penjual -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-8">
                        <div class="w-full aspect-square bg-gray-200">
                             @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                     <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail & Aksi -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            @if($product->category)
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full">{{ $product->category->name }}</span>
                            @endif
                            <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $product->name }}</h1>
                            <p class="text-3xl font-extrabold text-gray-800 my-4">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            
                            <div class="text-sm text-gray-500">
                                Dijual oleh <span class="font-medium text-gray-700">{{ $product->user->name ?? 'Penjual' }}</span>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <h3 class="font-bold text-lg mb-2">Deskripsi</h3>
                            <p class="text-gray-600 whitespace-pre-line text-sm leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                            @if($product->user && $product->user->phone_number)
                                <button @click="window.open('https://wa.me/{{ $product->user->phone_number }}?text={{ urlencode('Halo, saya tertarik dengan produk ' . $product->name) }}', '_blank'); reviewModalOpen = true" 
                                   class="w-full flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg text-center transition-transform hover:scale-105 duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M16.75 13.96c.25.13.41.2.52.34.11.14.15.33.11.52s-.14.34-.3.5c-.16.16-.35.28-.56.34-.21.06-.46.06-.74 0-.28-.06-.51-.13-.7-.22-.19-.09-.4-.2-.64-.34-.24-.14-.52-.33-.84-.56-.32-.23-.66-.52-1.02-.86s-.72-.73-1.08-1.15c-.36-.42-.66-.88-.9-1.37s-.42-.98-.52-1.48c-.1-.5.04-1.03.11-1.2.07-.17.15-.3.25-.38.1-.08.2-.13.3-.15.1-.02.2-.02.3 0 .1.02.2.04.28.08.08.04.15.1.2.15s.1.1.13.15.04.1.04.14c0 .05-.01.1-.04.16s-.05.1-.08.14c-.03.04-.05.06-.08.08-.03.02-.05.04-.08.06l-.3.2c-.1.06-.15.13-.15.22s.01.16.04.24c.03.08.06.14.1.2.04.06.1.13.16.2.06.07.14.14.22.22.08.08.18.16.28.24.1.08.2.14.3.2.1.06.2.1.3.14.1.04.18.06.26.08.08.02.16.03.24.03.1 0 .18-.01.25-.04.07-.03.14-.06.2-.1.06-.04.1-.08.15-.13.05-.05.1-.1.14-.14s.08-.08.1-.1c.02-.02.04-.04.06-.05.02-.01.04-.02.06-.02h.1c.05 0 .1.01.14.02.04.01.08.04.1.06.02.02.05.05.06.08.01.03.02.06.02.1z M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                    Hubungi Penjual
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Ulasan -->
            <div class="mt-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Ulasan & Rating ({{ $product->reviews->count() }})</h3>
                    @forelse ($product->reviews()->latest()->get() as $review)
                        <div class="border-t border-gray-100 py-6">
                            <div class="flex items-start space-x-4">
                                <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </span>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-sm text-gray-800">{{ $review->user->name ?? 'Pengguna' }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-700 mt-2 text-sm">{{ $review->comment }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pop-up Modal untuk Form Review -->
        <div x-show="reviewModalOpen" @keydown.escape.window="reviewModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="reviewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="reviewModalOpen = false" aria-hidden="true"></div>
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