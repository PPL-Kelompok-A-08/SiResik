<aside class="bg-[#0c5b49] flex flex-col px-5 py-7 text-white min-h-screen">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-2 mb-2">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-xl">♻</div>
        <div>
            <p class="text-3xl font-black tracking-tight leading-none">SiResik</p>
            <p class="mt-0.5 text-[10px] uppercase tracking-[0.2em] text-emerald-200">Sistem Informasi Resik</p>
        </div>
    </div>

    {{-- Nav Menu Masyarakat --}}
    @if(auth()->user()->role === 'masyarakat')
        @php
            $navItems = [
                ['label' => 'Dashboard',          'icon' => '⊞', 'route' => 'dashboard.masyarakat'],
                ['label' => 'Penjemputan',         'icon' => '⊕', 'route' => 'permintaan-penjemputan.index'],
                ['label' => 'Status Layanan',      'icon' => '◎', 'route' => 'status-layanan.index'],
                ['label' => 'Riwayat Layanan',     'icon' => '◉', 'route' => 'riwayat-layanan.index'],
                ['label' => 'Poin & Reward',       'icon' => '◈', 'route' => 'poin.index'],
                ['label' => 'Sampah Liar',         'icon' => '⊗', 'route' => 'sampah-liar.index'],
                ['label' => 'Peta & Lokasi',       'icon' => '⊙', 'route' => 'peta.lokasi'],
                ['label' => 'Usulkan Titik',       'icon' => '⊕', 'route' => 'peta.usulan-titik'],
                ['label' => 'Edukasi Lingkungan',  'icon' => '◧', 'route' => 'edukasi-lingkungan.index'],
                ['label' => 'Kegiatan Lingkungan', 'icon' => '◨', 'route' => 'kegiatan-lingkungan.index'],
                ['label' => 'Notifikasi',          'icon' => '◇', 'route' => 'notifications.index'],
            ];
        @endphp
        <nav class="mt-8 flex-1 space-y-0.5">
            @foreach ($navItems as $nav)
                <a href="{{ route($nav['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition-all
                          {{ request()->routeIs($nav['route'])
                              ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/30'
                              : 'text-emerald-50/80 hover:bg-white/10 hover:text-white' }}">
                    <span class="w-5 text-center text-base opacity-80">{{ $nav['icon'] }}</span>
                    <span>{{ $nav['label'] }}</span>
                </a>
            @endforeach
        </nav>

    {{-- Nav Menu Admin --}}
    @elseif(auth()->user()->role === 'admin')
        @php
            $navItems = [
                ['label' => 'Dashboard',         'icon' => '⊞', 'route' => 'dashboard.admin'],
                ['label' => 'Notifikasi',         'icon' => '◇', 'route' => 'notifications.index'],
            ];
        @endphp
        <nav class="mt-8 flex-1 space-y-0.5">
            @foreach ($navItems as $nav)
                <a href="{{ route($nav['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition-all
                          {{ request()->routeIs($nav['route'])
                              ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/30'
                              : 'text-emerald-50/80 hover:bg-white/10 hover:text-white' }}">
                    <span class="w-5 text-center text-base opacity-80">{{ $nav['icon'] }}</span>
                    <span>{{ $nav['label'] }}</span>
                </a>
            @endforeach
        </nav>

    {{-- Nav Menu Petugas --}}
    @elseif(auth()->user()->role === 'petugas')
        @php
            $navItems = [
                ['label' => 'Dashboard',        'icon' => '⊞', 'route' => 'dashboard.petugas'],
                ['label' => 'Daftar Tugas',     'icon' => '⊕', 'route' => 'permintaan-penjemputan.index'],
                ['label' => 'Riwayat Tugas',    'icon' => '◉', 'route' => 'petugas.riwayat'],
                ['label' => 'Notifikasi',       'icon' => '◇', 'route' => 'notifications.index'],
            ];
        @endphp
        <nav class="mt-8 flex-1 space-y-0.5">
            @foreach ($navItems as $nav)
                <a href="{{ route($nav['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition-all
                          {{ request()->routeIs($nav['route'])
                              ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/30'
                              : 'text-emerald-50/80 hover:bg-white/10 hover:text-white' }}">
                    <span class="w-5 text-center text-base opacity-80">{{ $nav['icon'] }}</span>
                    <span>{{ $nav['label'] }}</span>
                </a>
            @endforeach
        </nav>
    @endif

    {{-- Logout --}}
    <form action="{{ route('logout') }}" method="POST" class="mt-2">
        @csrf
        <button type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium text-emerald-50/80 transition hover:bg-white/10 hover:text-white">
            <span class="w-5 text-center">↪</span>
            <span>Keluar (Log Out)</span>
        </button>
    </form>

    {{-- User Card --}}
    <div class="mt-4 rounded-2xl bg-white/10 px-4 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-base font-black uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-[15px] font-bold">{{ auth()->user()->name }}</p>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-200">Warga Terverifikasi</p>
            </div>
        </div>
    </div>

</aside>
