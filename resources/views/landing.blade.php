<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRESIK - Bojongsoang Bersih</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-image-card {
            background: linear-gradient(135deg, #0d4f3c 0%, #1a6b52 50%, #0d4f3c 100%);
        }
        .stat-bar {
            background: linear-gradient(90deg, #065f46, #047857);
        }
        .service-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
        .waste-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
        .solution-card:hover .solution-overlay { opacity: 1; }
        .solution-overlay { opacity: 0; transition: opacity 0.3s; }
        html { scroll-behavior: smooth; }
    </style>
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
    </div>
</section>

{{-- ===== LAYANAN SECTION ===== --}}
<section id="layanan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-black text-gray-900">Layanan</h2>
        <p class="text-gray-400 mt-2">Revolusi daur ulang dari SiResik untuk semua orang.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
            {{-- Pick Up --}}
            <div class="service-card border border-gray-100 rounded-2xl p-7 transition-all duration-300 cursor-pointer">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                    <span class="text-2xl">🚛</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Pick Up</h3>
                <p class="text-gray-400 text-sm mt-3 leading-relaxed">
                    Foto sampah daur ulangmu, upload ke Aplikasi SiResik, kolektor terdekat akan
                    menjemput, menimbang dan membayar sampahmu.
                </p>
            </div>

            {{-- Drop Off --}}
            <div class="service-card border border-gray-100 rounded-2xl p-7 transition-all duration-300 cursor-pointer">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mb-5">
                    <span class="text-2xl">📍</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Drop Off</h3>
                <p class="text-gray-400 text-sm mt-3 leading-relaxed">
                    Antar langsung sampahmu ke Recycling Centre terdekat, kamu bisa mendaur ulang
                    dengan ukuran kecil seperti satu botol plastik.
                </p>
            </div>

            {{-- Company --}}
            <div class="service-card border border-gray-100 rounded-2xl p-7 transition-all duration-300 cursor-pointer">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5">
                    <span class="text-2xl">🏢</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Company</h3>
                <p class="text-gray-400 text-sm mt-3 leading-relaxed">
                    Daur ulang berlangganan untuk bisnis dan kantor, menciptakan bisnis ramah
                    lingkungan bukan sesuatu yang mahal lagi.
                </p>
            </div>

            {{-- Event --}}
            <div class="service-card border border-gray-100 rounded-2xl p-7 transition-all duration-300 cursor-pointer">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-5">
                    <span class="text-2xl">📅</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Event</h3>
                <p class="text-gray-400 text-sm mt-3 leading-relaxed">
                    Daftarkan eventmu di fitur ini untuk mengakses layanan daur ulang yang
                    didesain khusus untuk event atau layanan satu waktu.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ===== JENIS SAMPAH SECTION ===== --}}
<section id="jenis-sampah" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-gray-900">Jenis Sampah</h2>
            <p class="text-gray-400 mt-2">Lihat semua jenis sampah yang kami daur ulang.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-0 border border-gray-200 rounded-2xl overflow-hidden">
            {{-- Kertas --}}
            <div class="waste-card border-r border-b border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">📄</span>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-700">KERTAS</span>
            </div>

            {{-- Plastik --}}
            <div class="waste-card border-r border-b border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">🧴</span>
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">PLASTIK</span>
            </div>

            {{-- Aluminium --}}
            <div class="waste-card border-r border-b border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">🔩</span>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-700">ALUMINIUM</span>
            </div>

            {{-- Besi & Logam --}}
            <div class="waste-card border-b border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">🔨</span>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-700">BESI & LOGAM</span>
            </div>

            {{-- Elektronik --}}
            <div class="waste-card border-r border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">⚡</span>
                <span class="text-xs font-bold uppercase tracking-widest text-yellow-500">ELEKTRONIK</span>
            </div>

            {{-- Botol Kaca --}}
            <div class="waste-card border-r border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">🍷</span>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-700">BOTOL KACA</span>
            </div>

            {{-- Merek --}}
            <div class="waste-card border-r border-gray-200 p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <div class="w-10 h-10 border-4 border-pink-400 rounded-full mb-4"></div>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-700">MEREK</span>
            </div>

            {{-- Khusus --}}
            <div class="waste-card p-10 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer bg-white hover:bg-gray-50">
                <span class="text-4xl mb-4">🎨</span>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-700">KHUSUS</span>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI SECTION ===== --}}
<section id="solusi" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-black text-gray-900">Solusi Kami</h2>
        <p class="text-gray-400 mt-2">Sebuah teknologi untuk mengakhiri sampah.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
            {{-- For Everyone --}}
            <div class="solution-card rounded-2xl overflow-hidden border border-gray-100 relative group">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=400&q=80"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="For Everyone">
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-gray-900">For Everyone</h3>
                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">Daur ulang sampah yang Kamu hasilkan melalui aplikasi SiResik.</p>
                </div>
            </div>

            {{-- For Business --}}
            <div class="solution-card rounded-2xl overflow-hidden border border-gray-100 relative group">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&q=80"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="For Business">
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-gray-900">For Business</h3>
                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">Ciptakan bisnis dan kantor ramah lingkungan dengan mendaur ulang sampah yang Anda hasilkan.</p>
                </div>
            </div>

            {{-- For Corporate & Brand --}}
            <div class="solution-card rounded-2xl overflow-hidden border border-gray-100 relative group">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=400&q=80"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="For Corporate">
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-gray-900">For Corporate & Brand</h3>
                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">Teknologi Kami membantu perusahaan/brand untuk mengumpulkan dan memulihkan produk pasca konsumsi mereka.</p>
                </div>
            </div>

            {{-- For Government --}}
            <div class="solution-card rounded-2xl overflow-hidden border border-gray-100 relative group">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1448375240586-882707db888b?w=400&q=80"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="For Government">
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-gray-900">For Government</h3>
                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">SiResik menyediakan solusi teknologi pengelolaan sampah dan daur ulang bagi pemerintah kota dan desa.</p>
                </div>
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
