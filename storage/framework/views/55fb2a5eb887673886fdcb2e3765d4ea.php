<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Reward - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    ['label' => 'Dashboard', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Penjemputan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Status Layanan', 'active' => false],
                    ['label' => 'Riwayat Layanan', 'active' => false],
                    ['label' => 'Poin & Reward', 'active' => true, 'href' => route('poin.index')],
                    ['label' => 'Sampah Liar', 'active' => false],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => false, 'href' => route('peta.usulan-titik')],
                    ['label' => 'Edukasi Lingkungan', 'active' => false],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false],
                    ['label' => 'Notifikasi', 'active' => false],
                ];
            ?>

            <nav class="mt-14 space-y-2">
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['href'] ?? '#'); ?>"
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black"><?php echo e(substr($user->name, 0, 1)); ?></div>
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
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Penukaran Poin Reward</h1>
                    <p class="mt-2 text-lg text-slate-500">Tukarkan poin Anda dengan berbagai hadiah menarik!</p>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Poin Anda Saat Ini</p>
                    <p class="text-4xl font-black text-emerald-500"><?php echo e(number_format($totalPoin, 0, ',', '.')); ?> <span class="text-xl text-slate-500">pts</span></p>
                    <a href="<?php echo e(route('poin.index')); ?>" class="text-sm text-emerald-600 hover:underline mt-1">Lihat riwayat poin →</a>
                </div>
            </header>

            <?php if(session('success')): ?>
                <div class="mt-8 rounded-2xl bg-emerald-100 px-6 py-4 text-emerald-800 flex items-center gap-4 shadow-sm">
                    <span class="text-2xl">🎉</span>
                    <p class="font-semibold"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mt-8 rounded-2xl bg-red-100 px-6 py-4 text-red-800 flex items-center gap-4 shadow-sm">
                    <span class="text-2xl">⚠️</span>
                    <p class="font-semibold"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>

            <section class="mt-10 rounded-xl bg-[#e6f4ea] border border-emerald-900/20 p-8 shadow-sm" x-data="{ modalOpen: false, selectedReward: null }">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php $__empty_1 = true; $__currentLoopData = $rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reward): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $canAfford = $totalPoin >= $reward->poin_diperlukan;
                            
                            // Simple logic to pick an icon based on name
                            $icon = '🎁';
                            $nameLower = strtolower($reward->nama);
                            if (str_contains($nameLower, 'keychain') || str_contains($nameLower, 'gantungan')) $icon = '🔑';
                            if (str_contains($nameLower, 'mug') || str_contains($nameLower, 'gelas')) $icon = '☕';
                            if (str_contains($nameLower, 'shirt') || str_contains($nameLower, 'kaos') || str_contains($nameLower, 'baju')) $icon = '👕';
                        ?>
                        <article class="bg-[#f0f9f3] rounded-xl overflow-hidden border border-slate-800 flex flex-col group hover:shadow-lg transition">
                            <!-- Image Placeholder (Top Half) -->
                            <div class="h-44 bg-[#7db993] flex items-center justify-center relative border-b border-slate-800">
                                <div class="text-7xl drop-shadow-md"><?php echo e($icon); ?></div>
                                <?php if($reward->stok < 10): ?>
                                    <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-black uppercase px-2 py-1 rounded-md border border-slate-800 shadow-sm">Sisa <?php echo e($reward->stok); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Bottom Half -->
                            <div class="p-5 flex flex-col flex-1 items-center text-center">
                                <h3 class="text-xl font-black text-slate-900"><?php echo e($reward->nama); ?></h3>
                                <p class="mt-1 text-base font-medium text-slate-700"><?php echo e(number_format($reward->poin_diperlukan, 0, ',', '.')); ?> pts</p>
                                
                                <div class="mt-5 w-full">
                                    <?php if($canAfford): ?>
                                        <button 
                                            @click="selectedReward = { id: <?php echo e($reward->id); ?>, nama: '<?php echo e(addslashes($reward->nama)); ?>', poin: <?php echo e($reward->poin_diperlukan); ?> }; modalOpen = true"
                                            class="w-full rounded-lg border border-slate-800 bg-white px-4 py-2 text-sm font-bold text-slate-800 shadow-[2px_2px_0px_0px_rgba(30,41,59,1)] transition hover:translate-y-[2px] hover:shadow-[0px_0px_0px_0px_rgba(30,41,59,1)] active:bg-slate-50">
                                            Tukarkan
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="w-full rounded-lg border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-bold text-slate-400 cursor-not-allowed">
                                            Poin Kurang
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full py-16 text-center bg-white rounded-xl border border-dashed border-slate-400">
                            <span class="text-5xl">🎁</span>
                            <h3 class="mt-4 text-xl font-bold text-slate-700">Belum Ada Reward Tersedia</h3>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Confirmation Modal -->
                <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
                    <div x-show="modalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
                    
                    <div x-show="modalOpen" x-transition.scale.origin.bottom class="relative bg-white border-2 border-slate-800 rounded-2xl p-8 max-w-sm w-full mx-4 shadow-[8px_8px_0px_0px_rgba(30,41,59,1)]">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-[#e6f4ea] text-emerald-600 border border-slate-800 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🎁</div>
                            <h3 class="text-2xl font-black text-slate-900">Konfirmasi</h3>
                            <p class="mt-3 text-base text-slate-600">Tukar <strong x-text="selectedReward?.poin"></strong> poin untuk <strong x-text="selectedReward?.nama"></strong>?</p>
                        </div>

                        <form x-bind:action="`/reward/${selectedReward?.id}/redeem`" method="POST" class="mt-8 flex gap-3">
                            <?php echo csrf_field(); ?>
                            <button type="button" @click="modalOpen = false" class="flex-1 rounded-xl border border-slate-800 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-[2px_2px_0px_0px_rgba(30,41,59,1)] transition hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 rounded-xl border border-slate-800 bg-emerald-500 px-4 py-3 text-sm font-bold text-white shadow-[2px_2px_0px_0px_rgba(30,41,59,1)] transition hover:bg-emerald-600">
                                Ya, Tukar
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dhydo Aryo Jayanata\Documents\GitHub\TUBES\SiResik\resources\views/reward/index.blade.php ENDPATH**/ ?>