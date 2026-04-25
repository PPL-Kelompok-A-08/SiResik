<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_#d9f99d,_#ecfccb_25%,_#f8fafc_70%)] text-slate-900">
    <main class="mx-auto grid min-h-screen max-w-6xl items-center gap-10 px-6 py-10 lg:grid-cols-[1.05fr,0.95fr]">
        <section>
            <a href="/" class="inline-flex rounded-full border border-emerald-200 bg-white/70 px-4 py-2 text-sm font-semibold text-emerald-700 backdrop-blur">Kembali ke beranda</a>
            <p class="mt-8 text-sm font-semibold uppercase tracking-[0.3em] text-emerald-700">Portal Login</p>
            <h1 class="mt-4 max-w-xl text-5xl font-black leading-tight text-slate-950">Masuk ke dashboard SiResik sesuai peran Anda.</h1>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-600">
                Sistem ini mendukung tiga peran: Masyarakat, Petugas, dan Admin. Admin dapat masuk ke area admin sekaligus meninjau dashboard peran lain.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl bg-white/80 p-4 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-sm font-semibold text-slate-900">Masyarakat</p>
                    <p class="mt-2 text-sm text-slate-500">Ajukan penjemputan dan lihat riwayat layanan.</p>
                </div>
                <div class="rounded-3xl bg-white/80 p-4 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-sm font-semibold text-slate-900">Petugas</p>
                    <p class="mt-2 text-sm text-slate-500">Pantau antrean penjemputan dan prioritas operasional.</p>
                </div>
                <div class="rounded-3xl bg-white/80 p-4 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-sm font-semibold text-slate-900">Admin</p>
                    <p class="mt-2 text-sm text-slate-500">Kelola keseluruhan sistem dan akses semua area.</p>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                <p class="font-semibold text-white">Akun demo</p>
                <p class="mt-2">Admin: `admin@siresik.local`</p>
                <p>Petugas: `petugas@siresik.local`</p>
                <p>Masyarakat: `masyarakat@siresik.local`</p>
                <p class="mt-2">Password semua akun: `password`</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="mt-5 rounded-2xl border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('login.attempt')); ?>" method="POST" class="mt-6 space-y-5">
                <?php echo csrf_field(); ?>

                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-200">Email</span>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white outline-none placeholder:text-slate-400 focus:border-emerald-400" placeholder="nama@kampus.ac.id" required>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-semibold text-slate-200">Password</span>
                    <input type="password" name="password" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white outline-none placeholder:text-slate-400 focus:border-emerald-400" placeholder="Masukkan password" required>
                </label>

                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-white/20 bg-white/10 text-emerald-500">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-emerald-400">
                    Masuk ke Dashboard
                </button>
            </form>
        </section>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Dhydo Aryo Jayanata\Documents\GitHub\TUBES\SiResik\resources\views/auth/login.blade.php ENDPATH**/ ?>