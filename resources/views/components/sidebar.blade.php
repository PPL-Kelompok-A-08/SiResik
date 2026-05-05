<aside class="bg-[#0c5b49] px-6 py-8 text-white">
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/20 text-2xl">♻</div>
        <div>
            <p class="text-4xl font-black tracking-tight">SiResik</p>
            <p class="mt-1 text-xs uppercase tracking-[0.2em] text-emerald-100">Sistem Informasi Resik</p>
        </div>
    </div>

    <div class="mt-12">
        <p class="text-sm font-black uppercase tracking-[0.2em] text-emerald-300">Mode Akses</p>
        <div class="mt-4 flex items-center justify-between rounded-2xl bg-emerald-600/70 px-4 py-3">
            <span class="text-sm font-bold">
                @if(auth()->user()->role === 'admin')
                    Administrator
                @elseif(auth()->user()->role === 'petugas')
                    Petugas
                @else
                    Masyarakat
                @endif
            </span>
            <span class="text-lg">⌄</span>
        </div>
    </div>

    <!-- Nav -->
    <nav class="mt-10 space-y-2">
        @if(auth()->user()->role === 'admin')
            <!-- Menu Admin -->
            <a href="{{ route('dashboard.admin') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ request()->routeIs('dashboard.admin') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }} cursor-pointer">
                <span class="text-xl">{{ request()->routeIs('dashboard.admin') ? '▣' : '◦' }}</span>
                <span>Admin Dashboard</span>
            </a>
            <a onclick="showPage('jadwal')"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                <span class="text-xl">◦</span>
                <span>Kelola Jadwal</span>
            </a>
            <a onclick="showPage('verifikasi')"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                <span class="text-xl">◦</span>
                <span>Verifikasi Laporan</span>
            </a>
            <a onclick="showPage('kategori')"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                <span class="text-xl">◦</span>
                <span>Kategori & Reward</span>
            </a>
            <a onclick="showPage('area')"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                <span class="text-xl">◦</span>
                <span>Area Layanan</span>
            </a>
            <a onclick="showPage('petugas')"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                <span class="text-xl">◦</span>
                <span>Pantau Petugas</span>
            </a>
            <a onclick="showPage('riwayat')"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                <span class="text-xl">◦</span>
                <span>Riwayat Layanan</span>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Notifikasi</span>
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unread }}</span>
                @endif
            </a>
        @elseif(auth()->user()->role === 'petugas')
            <!-- Menu Petugas -->
            <a href="{{ route('dashboard.petugas') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ request()->routeIs('dashboard.petugas') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }} cursor-pointer">
                <span class="text-xl">{{ request()->routeIs('dashboard.petugas') ? '▣' : '◦' }}</span>
                <span>Dashboard Petugas</span>
            </a>
            <a href="{{ route('permintaan-penjemputan.index') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Daftar Penjemputan</span>
            </a>
            <a href="{{ route('petugas.riwayat') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Riwayat Tugas</span>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Notifikasi</span>
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unread }}</span>
                @endif
            </a>
        @else
            <!-- Menu Masyarakat -->
            <a href="{{ route('dashboard.masyarakat') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ request()->routeIs('dashboard.masyarakat') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }} cursor-pointer">
                <span class="text-xl">{{ request()->routeIs('dashboard.masyarakat') ? '▣' : '◦' }}</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('permintaan-penjemputan.index') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Penjemputan</span>
            </a>
            <a href="{{ route('poin.index') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Poin & Reward</span>
            </a>
            <a href="{{ route('peta.lokasi') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Peta & Lokasi</span>
            </a>
            <a href="{{ route('peta.usulan-titik') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Usulkan Titik</span>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5">
                <span class="text-xl">◦</span>
                <span>Notifikasi</span>
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unread }}</span>
                @endif
            </a>
        @endif
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
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div>
                <p class="text-xl font-bold">{{ auth()->user()->name }}</p>
                <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">
                    @if(auth()->user()->role === 'admin')
                        Administrator
                    @elseif(auth()->user()->role === 'petugas')
                        Petugas
                    @else
                        Warga
                    @endif
                </p>
            </div>
        </div>
    </div>
</aside>
