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
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- HERO SECTION -->
    <header class="bg-emerald-600 text-white text-center py-20 px-4">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">SIRESIK Bojongsoang</h1>
        <p class="text-xl md:text-2xl max-w-2xl mx-auto mb-8">
            Sistem Informasi Real-time Edukasi & Solusi Sampah Liar berbasis digital untuk mewujudkan kawasan Bojongsoang yang bersih, sehat, dan asri.
        </p>
        <div class="space-x-4">
            <a href="#tentang" class="bg-white text-emerald-700 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 transition">Pelajari Layanan</a>
            <a href="#laporkan" class="bg-emerald-800 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-emerald-900 transition">Laporkan Sampah</a>
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

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 py-8 text-center text-sm border-t border-gray-800">
        <p>&copy; 2026 SIRESIK Kabupaten Bandung. Dikembangkan secara lokal untuk kenyamanan warga Kecamatan Bojongsoang.</p>
    </footer>

</body>
</html>