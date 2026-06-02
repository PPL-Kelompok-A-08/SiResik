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

            @php
                $menuItems = [
                    ['label' => 'Dashboard', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Penjemputan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Status Layanan', 'active' => true, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Riwayat Layanan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Poin & Reward', 'active' => false, 'href' => route('poin.index')],
                    ['label' => 'Sampah Liar', 'active' => false, 'disabled' => true],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => false, 'href' => route('peta.usulan-titik')],
                    ['label' => 'Edukasi Lingkungan', 'active' => false, 'disabled' => true],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false, 'disabled' => true],
                    ['label' => 'Notifikasi', 'active' => false, 'disabled' => true],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($menuItems as $item)
                    @if (!empty($item['href']))
                        <a href="{{ $item['href'] }}"
                            class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                            <span class="text-xl">{{ $item['active'] ? '◉' : '◦' }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-200 opacity-70 cursor-not-allowed">
                            <span class="text-xl">◦</span>
                            <span>{{ $item['label'] }}</span>
                        </div>
                    @endif
                @endforeach
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="mt-8">
                @csrf
                <button type="submit" class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                    <span class="text-xl">↪</span>
                    <span>Keluar (Log Out)</span>
                </button>
            </form>

            <div class="mt-10 rounded-3xl bg-white/5 px-4 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">R</div>
                    <div>
                        <p class="text-xl font-bold">{{ $user->name }}</p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-end">
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700">Unduh Laporan</button>
                    <a href="{{ route('permintaan-penjemputan.index') }}" class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white">+ Ajukan Penjemputan</a>
                </div>
            </header>

            <div class="mt-12 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-6xl font-black tracking-tight text-slate-800">Status Layanan</h2>
                    <p class="mt-3 text-2xl text-slate-500">Pantau perkembangan penjemputan sampah Anda secara real-time.</p>
                </div>

                @if ($upcomingRequest)
                    <div class="rounded-[2rem] bg-[#0c5b49] px-7 py-5 text-white shadow-xl shadow-emerald-900/10 shrink-0">
                        <p class="text-sm font-black uppercase tracking-[0.18em] text-emerald-200">Jadwal Reguler Area</p>
                        <p class="mt-2 text-3xl font-black">{{ \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->translatedFormat('l, d M Y') }}</p>
                        <p class="mt-1 text-lg text-emerald-100">{{ \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->format('H:i') }} WIB bersama {{ $upcomingRequest->petugas?->name ?? 'Petugas' }}</p>
                    </div>
                @endif
            </div>

            <section class="mt-12 grid gap-8 xl:grid-cols-[1.55fr,0.75fr]">
                <div>
                    <p class="text-base font-black uppercase tracking-[0.2em] text-slate-400">Riwayat Permintaan</p>

                        <div class="mt-6 space-y-5">
                            @forelse ($trackingRequests as $index => $item)
                                @php
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
                                @endphp

                                <article class="rounded-[2rem] bg-white px-6 py-6 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex items-start gap-5">
                                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-[1.5rem] {{ $statusMeta['iconBg'] }} text-4xl font-black {{ $statusMeta['iconText'] }}">
                                                {{ $statusMeta['icon'] }}
                                            </div>

                                            <div>
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <span class="rounded-xl bg-slate-100 px-3 py-1 text-sm font-black text-slate-500">PK-{{ str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT) }}</span>
                                                    <span class="rounded-xl px-3 py-1 text-sm font-black {{ $statusMeta['badge'] }}">{{ $statusMeta['label'] }}</span>
                                                </div>
                                                <h3 class="mt-4 text-5xl font-black tracking-tight text-slate-800">{{ $kategoriText ?: 'Permintaan Penjemputan' }}</h3>
                                                <p class="mt-3 text-xl text-slate-400">
                                                    Diajukan pada {{ optional($item->created_at)->translatedFormat('d M Y') }} • Estimasi berat {{ rtrim(rtrim(number_format($beratText, 2, '.', ''), '0'), '.') ?: '0' }} kg
                                                </p>
                                                @if ($item->status === 'Diproses' && $item->scheduled_at)
                                                    <p class="mt-2 text-base font-semibold text-blue-600">
                                                        Dijadwalkan {{ \Illuminate\Support\Carbon::parse($item->scheduled_at)->translatedFormat('d M Y, H:i') }} WIB{{ $item->petugas ? ' • Petugas: '.$item->petugas->name : '' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="lg:pr-3">
                                            <button type="button" 
                                                class="detail-btn inline-flex rounded-2xl border border-slate-200 bg-white px-8 py-4 text-2xl font-bold text-slate-600 shadow-sm hover:bg-slate-50 transition cursor-pointer"
                                                data-code="PK-{{ str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT) }}"
                                                data-status="{{ $statusMeta['label'] }}"
                                                data-date="{{ optional($item->created_at)->translatedFormat('d M Y') }}"
                                                data-categories="{{ $kategoriText ?: 'Permintaan Penjemputan' }}"
                                                data-address="{{ $item->alamat }}"
                                                data-officer="{{ $item->petugas?->name ?? 'Belum ditugaskan' }}"
                                                data-weight="{{ rtrim(rtrim(number_format($beratText, 2, '.', ''), '0'), '.') ?: '0' }}"
                                                data-points="{{ $item->total_estimasi_poin ?: 0 }}"
                                                data-notes="{{ $item->catatan_penyelesaian ?? 'Tidak ada catatan dari petugas.' }}">
                                                Detail
                                            </button>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-lg text-slate-500">
                                    Belum ada permintaan penjemputan yang bisa dilacak.
                                </div>
                            @endforelse
                        </div>
                </div>

                <div>
                    <p class="text-base font-black uppercase tracking-[0.2em] text-slate-400">Kalender Mingguan</p>

                    <section class="mt-6 rounded-[2.5rem] bg-white p-6 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                        <div class="space-y-5">
                            @foreach ($weeklySchedules as $schedule)
                                <article class="rounded-[2rem] border border-slate-200 bg-white px-5 py-5 shadow-sm">
                                    <div class="flex items-start justify-between gap-4">
                                        <p class="text-2xl font-black uppercase tracking-[0.12em] text-[#0c5b49]">{{ $schedule['hari'] }}</p>
                                        <span class="rounded-xl bg-slate-100 px-3 py-1 text-sm font-black text-slate-400">{{ $schedule['jam'] }}</span>
                                    </div>
                                    <p class="mt-4 text-2xl font-black text-slate-800">{{ $schedule['kategori'] }}</p>
                                    <p class="mt-4 text-base italic text-slate-400">Berlaku untuk {{ $schedule['zona'] }}</p>
                                </article>
                            @endforeach
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

    <!-- Modal Detail Permintaan Penjemputan -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <!-- Modal Card Container -->
        <div class="relative w-full max-w-[640px] transform scale-95 bg-white rounded-[2.5rem] shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-[#00c48c] px-10 py-8 text-white relative">
                <!-- Close Button -->
                <button onclick="closeDetailModal()" class="absolute top-6 right-6 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white font-black text-xl transition-all cursor-pointer">
                    &times;
                </button>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-white/80">Detail Permintaan Penjemputan</p>
                <h2 id="modalCode" class="text-6xl font-black italic tracking-tight mt-2">PK-001</h2>
                
                <div class="flex flex-wrap gap-3 mt-4">
                    <span id="modalStatusBadge" class="rounded-2xl bg-white/20 px-4 py-1.5 text-sm font-black text-white">SELESAI</span>
                    <span id="modalDateBadge" class="rounded-2xl bg-white/20 px-4 py-1.5 text-sm font-black text-white">15 MAR 2024</span>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-10 flex-1">
                <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori Sampah</p>
                        <p id="modalCategories" class="text-slate-800 font-extrabold text-xl mt-1 leading-normal">Plastik, Kertas</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Lengkap</p>
                        <p id="modalAddress" class="text-slate-800 font-extrabold text-xl mt-1 leading-normal">Jl. Merpati No. 12, Sukamaju</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Petugas Lapangan</p>
                        <p id="modalOfficer" class="text-slate-800 font-extrabold text-xl mt-1 flex items-center gap-2">👤 Budi Santoso</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Estimasi Berat / Poin</p>
                        <p id="modalWeightPoints" class="text-emerald-500 font-black text-xl mt-1">3.5 Kg &bull; 350 Pts</p>
                    </div>
                </div>

                <!-- Catatan Petugas -->
                <div class="mt-8 bg-slate-50 border border-slate-100 rounded-3xl p-6 relative overflow-hidden">
                    <!-- SVG Watermark Clock Icon -->
                    <div class="absolute right-4 bottom-2 opacity-5 text-slate-900 pointer-events-none">
                        <svg class="h-20 w-20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Catatan Petugas</p>
                    <p id="modalNotes" class="text-slate-600 font-semibold italic mt-2 text-base leading-relaxed relative z-10">
                        "Sampah dipilah dengan sangat baik."
                    </p>
                </div>

                <!-- Modal Footer Buttons -->
                <div class="flex justify-between items-center gap-4 mt-10">
                    <a id="modalHubungiBtn" href="#" target="_blank" class="flex-1 bg-[#0f172a] hover:bg-slate-800 text-white font-bold text-center py-4 px-6 rounded-2xl text-lg transition-all shadow-lg hover:shadow-slate-300/50">
                        HUBUNGI PETUGAS
                    </a>
                    <button onclick="closeDetailModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-4 px-6 rounded-2xl text-lg transition-all cursor-pointer">
                        TUTUP DETAIL
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(btn) {
            const code = btn.getAttribute('data-code');
            const status = btn.getAttribute('data-status');
            const date = btn.getAttribute('data-date');
            const categories = btn.getAttribute('data-categories');
            const address = btn.getAttribute('data-address');
            const officer = btn.getAttribute('data-officer');
            const weight = btn.getAttribute('data-weight');
            const points = btn.getAttribute('data-points');
            const notes = btn.getAttribute('data-notes');

            // Populate text
            document.getElementById('modalCode').innerText = code;
            document.getElementById('modalStatusBadge').innerText = status;
            document.getElementById('modalDateBadge').innerText = date;
            document.getElementById('modalCategories').innerText = categories;
            document.getElementById('modalAddress').innerText = address;
            document.getElementById('modalOfficer').innerText = '👤 ' + officer;
            document.getElementById('modalWeightPoints').innerHTML = weight + ' Kg &bull; ' + points + ' Pts';
            document.getElementById('modalNotes').innerText = '"' + notes + '"';

            // Set WhatsApp link for officer contact (using general support WA if officer name is "Belum ditugaskan")
            let waText = '';
            if (officer === 'Belum ditugaskan') {
                waText = `Halo admin SiResik, saya ingin menanyakan tentang status penjemputan saya dengan kode *${code}*.`;
            } else {
                waText = `Halo petugas ${officer}, saya ingin bertanya mengenai jadwal penjemputan *${code}* saya.`;
            }
            document.getElementById('modalHubungiBtn').href = 'https://wa.me/6281234567890?text=' + encodeURIComponent(waText);

            // Handle modal show transition
            const modal = document.getElementById('detailModal');
            const card = modal.querySelector('.transform');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const card = modal.querySelector('.transform');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
            }
        });

        // Close on clicking outside modal card
        document.getElementById('detailModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDetailModal();
            }
        });

        // Add event listeners to detail buttons
        document.addEventListener('DOMContentLoaded', function() {
            const detailButtons = document.querySelectorAll('.detail-btn');
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    openDetailModal(this);
                });
            });
        });
    </script>
</body>
</html>
