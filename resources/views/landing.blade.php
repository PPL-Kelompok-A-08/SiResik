<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRESIK - Bojongsoang Bersih Lingkungan</title>
    <!-- Hubungkan dengan file css Anda -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Menggunakan Tailwind untuk kemudahan desain -->
</head>

<body class="bg-white text-gray-900 antialiased">

{{-- ===== NAVBAR ===== --}}
<nav class="fixed top-0 w-full z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <span class="text-2xl font-black text-emerald-900" style="letter-spacing:-0.5px">SiResik</span>
        </div>

        <div class="hidden md:flex gap-8 text-sm font-semibold text-gray-700 items-center">
            <a href="#tentang" class="text-emerald-600 hover:text-emerald-700 transition-colors">TENTANG</a>
            <a href="#layanan" class="hover:text-emerald-600 transition-colors">LAYANAN</a>
            <a href="#jenis-sampah" class="hover:text-emerald-600 transition-colors">JENIS SAMPAH</a>
            <a href="#solusi" class="hover:text-emerald-600 transition-colors">SOLUSI</a>
        </div>

        @auth
            <a href="{{ route('dashboard') }}" class="bg-emerald-500 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-emerald-600 transition-colors">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="bg-emerald-500 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-emerald-600 transition-colors">
                Masuk Sekarang
            </a>
        @endauth
    </div>
</nav>

{{-- ===== HERO SECTION ===== --}}
<section id="tentang" class="pt-16 min-h-screen bg-gray-50 flex items-center">
    <div class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-16 items-center w-full">

        {{-- Left: Image Card --}}
        <div class="relative">
            <div class="hero-image-card rounded-3xl overflow-hidden aspect-[4/5] flex flex-col items-center justify-center relative">
                {{-- Background recycling image --}}
                <div class="absolute inset-0 opacity-30">
                    <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=600&q=80"
                         class="w-full h-full object-cover" alt="Recycling">
                </div>
                <div class="relative z-10 text-center text-white px-8">
                    <p class="text-lg font-light tracking-wide mb-2">We are The</p>
                    <p class="text-3xl font-black italic tracking-tight">Recycling Network</p>
                    <p class="mt-4 text-emerald-300 text-sm tracking-widest">#ubahjadikebaikan</p>
                </div>
            </div>
        </div>

        {{-- Right: Content --}}
        <div>
            <h1 class="text-5xl lg:text-6xl font-black text-gray-900 leading-tight">
                Wujudkan
                <span class="text-emerald-500"> Kabupaten </span>
                Bebas Sampah. 
            </h1>

            <p class="text-gray-500 mt-6 text-base leading-relaxed">
                Teknologi SiResik didesain untuk menangkap limbah langsung dari sumber timbulnya,
                dengan menggunakan jejaring pengepul dan petugas lokal sebagai kunci dari rantai daur
                ulang di Indonesia.
            </p>

            {{-- Stats Bar --}}
            <div class="stat-bar rounded-2xl mt-8 p-6 grid grid-cols-4 gap-4 text-white text-center">
                <div>
                    <p class="text-2xl font-black">{{ $stats['total_sampah'] ?? '1jt' }} Kg+</p>
                    <p class="text-xs text-emerald-200 mt-1 uppercase tracking-wide leading-tight">SAMPAH DI DAUR ULANG</p>
                </div>
                <div>
                    <p class="text-2xl font-black">100+</p>
                    <p class="text-xs text-emerald-200 mt-1 uppercase tracking-wide">GUDANG SORTIR</p>
                </div>
                <div>
                    <p class="text-2xl font-black">500+</p>
                    <p class="text-xs text-emerald-200 mt-1 uppercase tracking-wide">KOLEKTOR LOKAL</p>
                </div>
                <div>
                    <p class="text-2xl font-black">{{ $stats['total_user'] ?? '30rb' }}+</p>
                    <p class="text-xs text-emerald-200 mt-1 uppercase tracking-wide">PENGGUNA</p>
                </div>
            </div>
        </div>
    </header>

    <!-- TENTANG APLIKASI -->
    <section id="tentang" class="max-w-6xl mx-auto py-16 px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-emerald-700">Mengenal SIRESIK</h2>
            <div class="w-24 h-1 bg-emerald-500 mx-auto mt-2"></div>
            <p class="text-gray-600 mt-4 max-w-3xl mx-auto">
                SIRESIK merupakan platform digital yang dirancang khusus untuk mendeteksi, melaporkan, dan mengelola titik penumpukan sampah liar di wilayah Bojongsoang secara cepat, transparan, dan terintegrasi dengan petugas bank sampah setempat.
            </p>
        </div>

        <!-- FITUR UTAMA -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="text-emerald-600 font-bold text-xl mb-2">📍 Peta Titik Layanan</div>
                <p class="text-gray-600 text-sm">Visualisasi lokasi penjemputan, bank sampah aktif, dan area rawan pembuangan sampah liar di Bojongsoang secara akurat.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="text-emerald-600 font-bold text-xl mb-2">📸 Pelaporan Sampah Liar</div>
                <p class="text-gray-600 text-sm">Laporkan penumpukan sampah ilegal secara instan dengan melampirkan foto beserta koordinat lokasi terkini Anda.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="text-emerald-600 font-bold text-xl mb-2">🎁 Tukar Poin & Reward</div>
                <p class="text-gray-600 text-sm">Setiap sampah yang berhasil disetorkan atau diverifikasi akan menghasilkan poin yang dapat ditukar dengan merchandise menarik.</p>
            </div>
        </div>
    </section>

    <!-- KATEGORI SAMPAH -->
    <section class="bg-emerald-50 py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-emerald-700">Panduan Kategori Sampah</h2>
                <p class="text-gray-600 mt-2">Kenali jenis sampahmu sebelum menyetorkannya ke fasilitas pengelolaan terdekat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Organik -->
                <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-amber-500">
                    <span class="text-2xl">🍂</span>
                    <h3 class="font-bold text-lg text-gray-800 my-2">Sampah Organik</h3>
                    <p class="text-gray-600 text-xs leading-relaxed">Sisa makanan, dedaunan, dan bahan mudah membusuk. Diolah menjadi kompos berkualitas oleh tim lingkungan Bojongsoang.</p>
                </div>
                <!-- Anorganik Plastik -->
                <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-blue-500">
                    <span class="text-2xl">🥤</span>
                    <h3 class="font-bold text-lg text-gray-800 my-2">Anorganik (Plastik)</h3>
                    <p class="text-gray-600 text-xs leading-relaxed">Botol mineral, kantong kresek, kemasan plastik, gelas plastik. Memiliki nilai konversi poin tinggi untuk didaur ulang.</p>
                </div>
                <!-- Kertas & Karton -->
                <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-yellow-600">
                    <span class="text-2xl">📦</span>
                    <h3 class="font-bold text-lg text-gray-800 my-2">Kertas / Karton</h3>
                    <p class="text-gray-600 text-xs leading-relaxed">Kardus bekas, koran, majalah, kertas dokumen. Pastikan dalam kondisi kering sebelum diserahkan ke kurir penjemput.</p>
                </div>
                <!-- Residu / B3 -->
                <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-red-500">
                    <span class="text-2xl">🔋</span>
                    <h3 class="font-bold text-lg text-gray-800 my-2">B3 & Residu</h3>
                    <p class="text-gray-600 text-xs leading-relaxed">Baterai, lampu bekas, masker medis, popok sekali pakai. Memerlukan penanganan khusus demi keamanan ekosistem darat dan air.</p>
                </div>
            </div>
        </div>
    </section>

{{-- ===== FOOTER ===== --}}
<footer class="bg-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-white" style="letter-spacing:-0.5px">SiResik</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Membangun ekosistem daur ulang digital yang berkelanjutan di Indonesia.
                    Mengubah limbah menjadi berkah bagi lingkungan dan ekonomi lokal.
                </p>
                <div class="flex gap-3 mt-5">
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                        <i class="fab fa-twitter text-xs"></i>
                    </a>
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                        <i class="fab fa-instagram text-xs"></i>
                    </a>
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                        <i class="fab fa-linkedin text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- Eksplorasi --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5">EKSPLORASI</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#tentang" class="text-emerald-400 hover:text-emerald-300 transition-colors">Beranda</a></li>
                    <li><a href="#layanan" class="text-emerald-400 hover:text-emerald-300 transition-colors">Layanan Utama</a></li>
                    <li><a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">Pusat Informasi</a></li>
                    <li><a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">Karir & Relawan</a></li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5">BANTUAN</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">Pusat Bantuan</a></li>
                    <li><a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">Panduan Daur Ulang</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5">KONTAK KAMI</h4>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-emerald-400 font-bold text-xs uppercase tracking-wide">EMAIL SUPPORT</p>
                        <p class="text-gray-300 mt-1">hi@siresik.id</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-xs uppercase tracking-wide">KANTOR PUSAT</p>
                        <p class="text-gray-300 mt-1">Pintu Masuk Tech Hub, Lt. 3<br>Jakarta Selatan, Indonesia</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
            <p>© 2024 SIRESIK — DIGITAL TRANSFORMATION FOR CIRCULAR ECONOMY</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-gray-300 transition-colors">STATUS SISTEM</a>
                <a href="#" class="hover:text-gray-300 transition-colors">DEVELOPER API</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>