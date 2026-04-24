<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Penjemputan - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">
        <aside class="bg-[#0c5b49] px-6 py-8 text-white">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/20 text-2xl">♻</div>
                <div>
                    <p class="text-4xl font-black tracking-tight">SiResik</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.2em] text-emerald-100">Sistem Informasi Resik</p>
                </div>
            </div>

            <?php
                $menuItems = [
                    ['label' => 'Dashboard', 'active' => false],
                    ['label' => 'Penjemputan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Status Layanan', 'active' => true],
                    ['label' => 'Riwayat Layanan', 'active' => false],
                    ['label' => 'Poin & Reward', 'active' => false],
                    ['label' => 'Sampah Liar', 'active' => false],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => false],
                    ['label' => 'Edukasi Lingkungan', 'active' => false],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false],
                    ['label' => 'Notifikasi', 'active' => false],
                ];
            ?>

            <nav class="mt-14 space-y-2">
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['href'] ?? route('dashboard.masyarakat')); ?>"
                        class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition <?php echo e($item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5'); ?>">
                        <span class="text-xl"><?php echo e($item['active'] ? '◉' : '◦'); ?></span>
                        <span><?php echo e($item['label']); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-8">
                <?php echo csrf_field(); ?>
                <button type="submit" class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                    <span class="text-xl">↪</span>
                    <span>Keluar (Log Out)</span>
                </button>
            </form>

            <div class="mt-10 rounded-3xl bg-white/5 px-4 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">R</div>
                    <div>
                        <p class="text-xl font-bold"><?php echo e($user->name); ?></p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Status Penjemputan</h1>
                    <p class="mt-2 text-lg text-slate-500">Dashboard Masyarakat</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700">Unduh Laporan</button>
                    <a href="<?php echo e(route('permintaan-penjemputan.index')); ?>" class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white">+ Ajukan Penjemputan</a>
                </div>
            </header>

            <section class="mt-12 grid gap-8 xl:grid-cols-[1.55fr,0.75fr]">
                <div>
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-6xl font-black tracking-tight text-slate-800">Status Layanan</h2>
                            <p class="mt-3 text-2xl text-slate-500">Pantau perkembangan penjemputan sampah Anda secara real-time.</p>
                        </div>

                        <?php if($upcomingRequest): ?>
                            <div class="rounded-[2rem] bg-[#0c5b49] px-7 py-5 text-white shadow-xl shadow-emerald-900/10">
                                <p class="text-sm font-black uppercase tracking-[0.18em] text-emerald-200">Jadwal Reguler Area</p>
                                <p class="mt-2 text-3xl font-black"><?php echo e(\Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->translatedFormat('l, d M Y')); ?></p>
                                <p class="mt-1 text-lg text-emerald-100"><?php echo e(\Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->format('H:i')); ?> WIB bersama <?php echo e($upcomingRequest->petugas?->name ?? 'Petugas'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-12">
                        <p class="text-base font-black uppercase tracking-[0.2em] text-slate-400">Riwayat Permintaan</p>

                        <div class="mt-6 space-y-5">
                            <?php $__empty_1 = true; $__currentLoopData = $trackingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $statusMeta = match ($item->status) {
                                        'Selesai' => [
                                            'badge' => 'text-emerald-600 bg-emerald-100',
                                            'iconBg' => 'bg-emerald-100',
                                            'iconText' => 'text-emerald-700',
                                            'label' => 'SELESAI',
                                            'icon' => '✓',
                                        ],
                                        'Diproses' => [
                                            'badge' => 'text-blue-600 bg-blue-100',
                                            'iconBg' => 'bg-blue-100',
                                            'iconText' => 'text-blue-700',
                                            'label' => 'DIJADWALKAN',
                                            'icon' => '◔',
                                        ],
                                        default => [
                                            'badge' => 'text-amber-600 bg-amber-100',
                                            'iconBg' => 'bg-amber-100',
                                            'iconText' => 'text-amber-700',
                                            'label' => 'MENUNGGU',
                                            'icon' => '◷',
                                        ],
                                    };
                                    $kategoriText = $item->items->pluck('kategoriSampah.nama')->filter()->take(2)->implode(', ');
                                    $beratText = $item->items->sum('berat_kg');
                                ?>

                                <article class="rounded-[2rem] bg-white px-6 py-6 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex items-start gap-5">
                                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-[1.5rem] <?php echo e($statusMeta['iconBg']); ?> text-4xl font-black <?php echo e($statusMeta['iconText']); ?>">
                                                <?php echo e($statusMeta['icon']); ?>

                                            </div>

                                            <div>
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <span class="rounded-xl bg-slate-100 px-3 py-1 text-sm font-black text-slate-500">PK-<?php echo e(str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)); ?></span>
                                                    <span class="rounded-xl px-3 py-1 text-sm font-black <?php echo e($statusMeta['badge']); ?>"><?php echo e($statusMeta['label']); ?></span>
                                                </div>
                                                <h3 class="mt-4 text-5xl font-black tracking-tight text-slate-800"><?php echo e($kategoriText ?: 'Permintaan Penjemputan'); ?></h3>
                                                <p class="mt-3 text-xl text-slate-400">
                                                    Diajukan pada <?php echo e(optional($item->created_at)->translatedFormat('d M Y')); ?> • Estimasi berat <?php echo e(rtrim(rtrim(number_format($beratText, 2, '.', ''), '0'), '.') ?: '0'); ?> kg
                                                </p>
                                                <?php if($item->status === 'Diproses' && $item->scheduled_at): ?>
                                                    <p class="mt-2 text-base font-semibold text-blue-600">
                                                        Dijadwalkan <?php echo e(\Illuminate\Support\Carbon::parse($item->scheduled_at)->translatedFormat('d M Y, H:i')); ?> WIB<?php echo e($item->petugas ? ' • Petugas: '.$item->petugas->name : ''); ?>

                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="lg:pr-3">
                                            <a href="<?php echo e(route('permintaan-penjemputan.index')); ?>" class="inline-flex rounded-2xl border border-slate-200 bg-white px-8 py-4 text-2xl font-bold text-slate-600 shadow-sm">Detail</a>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-lg text-slate-500">
                                    Belum ada permintaan penjemputan yang bisa dilacak.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-base font-black uppercase tracking-[0.2em] text-slate-400">Kalender Mingguan</p>

                    <section class="mt-6 rounded-[2.5rem] bg-white p-6 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                        <div class="space-y-5">
                            <?php $__currentLoopData = $weeklySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <article class="rounded-[2rem] border border-slate-200 bg-white px-5 py-5 shadow-sm">
                                    <div class="flex items-start justify-between gap-4">
                                        <p class="text-2xl font-black uppercase tracking-[0.12em] text-[#0c5b49]"><?php echo e($schedule['hari']); ?></p>
                                        <span class="rounded-xl bg-slate-100 px-3 py-1 text-sm font-black text-slate-400"><?php echo e($schedule['jam']); ?></span>
                                    </div>
                                    <p class="mt-4 text-2xl font-black text-slate-800"><?php echo e($schedule['kategori']); ?></p>
                                    <p class="mt-4 text-base italic text-slate-400">Berlaku untuk <?php echo e($schedule['zona']); ?></p>
                                </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mt-5 rounded-[2rem] border border-emerald-100 bg-emerald-50 px-5 py-5 text-base leading-8 text-emerald-800">
                            Untuk sampah rutin mingguan (Organik/Anorganik), Anda tidak perlu mengajukan permintaan. Petugas akan datang sesuai jadwal di atas.
                        </div>
                    </section>
                </div>
            </section>

            <section class="mt-10 rounded-[2.5rem] bg-white px-7 py-7 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-6">
                        <div class="flex h-20 w-20 items-center justify-center rounded-[1.5rem] border border-orange-200 bg-orange-50 text-4xl font-black text-orange-500">!</div>
                        <div>
                            <h3 class="text-4xl font-black tracking-tight text-slate-800">Butuh Bantuan Penjemputan?</h3>
                            <p class="mt-3 text-xl text-slate-500">Jika status penjemputan Anda tidak berubah dalam 2x24 jam, silakan hubungi pusat bantuan kami melalui WhatsApp.</p>
                        </div>
                    </div>

                    <button type="button" class="rounded-2xl bg-emerald-500 px-10 py-5 text-3xl font-black text-white shadow-xl shadow-emerald-500/20">
                        Chat Bantuan
                    </button>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH /Users/luckygirlsyndrome/Documents/College/SEM 6/PPL PROJECTS/SiResik/resources/views/dashboard/masyarakat.blade.php ENDPATH**/ ?>