<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiResik - Solusi Sampah Kampus</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans antialiased">

<!-- NAVBAR -->
<nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-emerald-100">
    <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">

        <!-- LOGO -->
        <div class="flex items-center gap-2">
            <div class="bg-emerald-600 p-2 rounded-lg">
                <i class="fas fa-recycle text-white"></i>
            </div>
            <span class="text-xl font-bold text-emerald-950">SiResik</span>
        </div>

        <!-- MENU -->
        <div class="hidden md:flex gap-8 text-sm font-semibold text-emerald-900 items-center">

            <a href="#" class="hover:text-emerald-600">Tentang</a>

            <!-- DROPDOWN FITUR -->
            <div class="relative">
                <button onclick="toggleDropdown()" class="hover:text-emerald-600 flex items-center gap-1">
                    Fitur
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div id="dropdownMenu" class="hidden absolute mt-3 w-52 bg-white rounded-xl shadow-lg border border-emerald-100 overflow-hidden">

                    <a href="/kategori" class="block px-4 py-3 text-sm hover:bg-emerald-50">
                        ♻️ Kategori Sampah
                    </a>

                    <a href="/permintaan-penjemputan" class="block px-4 py-3 text-sm hover:bg-emerald-50">
                        Penjemputan Sampah
                    </a>

                    <a href="/maps" class="block px-4 py-3 text-sm hover:bg-emerald-50">
                        📍 Maps Lokasi
                    </a>

                    <a href="/reward" class="block px-4 py-3 text-sm hover:bg-emerald-50">
                        🎁 Reward
                    </a>

                </div>
            </div>

            <a href="#" class="hover:text-emerald-600">Konten Edukasi</a>
        </div>

        <!-- BUTTON -->
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('dashboard')); ?>" class="bg-emerald-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-emerald-700 inline-flex items-center">
                Dashboard
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="bg-emerald-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-emerald-700 inline-flex items-center">
                Login
            </a>
        <?php endif; ?>

    </div>
</nav>

<!-- HERO -->
<section class="pt-32 pb-20 px-6">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">

        <div>
            <span class="bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase">
                Inovasi Lingkungan
            </span>

            <h1 class="text-5xl lg:text-7xl font-extrabold text-emerald-950 mt-6 leading-tight">
                Wujudkan <span class="text-emerald-600">Kampus Hijau</span> Tanpa Sampah.
            </h1>

            <p class="text-emerald-800/70 mt-6 text-lg">
                Kelola sampah Anda, dapatkan poin, dan tukarkan dengan hadiah menarik.
            </p>

            <div class="mt-8 flex gap-4">
                <a href="<?php echo e(auth()->check() ? route('permintaan-penjemputan.index') : route('login')); ?>" class="bg-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-emerald-700 shadow-xl inline-flex items-center justify-center">
                    Ajukan Penjemputan
                </a>

                <button class="border-2 border-emerald-100 bg-white text-emerald-900 px-8 py-4 rounded-2xl font-bold hover:bg-emerald-50">
                    Lapor Sampah
                </button>
            </div>
        </div>

        <div class="relative">
            <img src="https://picsum.photos/seed/nature/800/600" class="rounded-[2.5rem] shadow-2xl">

            <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-3xl shadow-xl">
                <p class="text-3xl font-bold text-emerald-600">98%</p>
                <p class="text-xs text-gray-400">Kepuasan User</p>
            </div>
        </div>

    </div>
</section>

<!-- STATISTICS -->
<section class="py-16 bg-white border-y border-emerald-50">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">

        <div>
            <p class="text-4xl font-bold text-emerald-950"><?php echo e($stats['total_user'] ?? 0); ?>+</p>
            <p class="text-sm text-gray-400">Total Mahasiswa</p>
        </div>

        <div>
            <p class="text-4xl font-bold text-emerald-950"><?php echo e($stats['total_sampah'] ?? 0); ?></p>
            <p class="text-sm text-gray-400">Daur Ulang</p>
        </div>

        <div>
            <p class="text-4xl font-bold text-emerald-950"><?php echo e($stats['poin_terbagi'] ?? 0); ?></p>
            <p class="text-sm text-gray-400">Poin</p>
        </div>

        <div>
            <p class="text-4xl font-bold text-emerald-950"><?php echo e($stats['petugas_aktif'] ?? 0); ?></p>
            <p class="text-sm text-gray-400">Petugas</p>
        </div>

    </div>
</section>

<!-- FOOTER -->
<footer class="py-10 text-center text-gray-400">
    © 2024 SiResik
</footer>

<!-- SCRIPT -->
<script>
function toggleDropdown() {
    document.getElementById('dropdownMenu').classList.toggle('hidden');
}

// klik luar untuk close
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdownMenu');
    if (!e.target.closest('.relative')) {
        dropdown.classList.add('hidden');
    }
});
</script>

</body>
</html>
<?php /**PATH D:\File Kuliah\Semester 6\Proyek Perangkat Lunak\SiResik\resources\views/landing.blade.php ENDPATH**/ ?>