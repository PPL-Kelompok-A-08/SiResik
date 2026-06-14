<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .sidebar-gorgeous {
        display: flex;
        flex-direction: column;
        background: linear-gradient(170deg, #053d2e 0%, #065f46 55%, #047857 100%);
        color: #fff;
        min-height: 100vh;
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    .sidebar-gorgeous::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }
    .sidebar-gorgeous::after {
        content: '';
        position: absolute;
        bottom: 100px; left: -40px;
        width: 140px; height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,.03);
        pointer-events: none;
    }
    .mas-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: rgba(209,250,229,.75);
        text-decoration: none;
        cursor: pointer;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        transition: background .15s, color .15s, transform .12s;
        position: relative;
    }
    .mas-nav-link:hover {
        background: rgba(255,255,255,.1);
        color: #fff;
        transform: translateX(2px);
    }
    .mas-nav-link.active {
        background: rgba(255,255,255,.16);
        color: #fff;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.12);
    }
    .mas-nav-link.active::before {
        content: '';
        position: absolute;
        left: -10px;
        width: 3px; height: 22px;
        border-radius: 0 3px 3px 0;
        background: #6ee7b7;
    }
    .mas-nav-link .nav-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: rgba(255,255,255,.08);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px;
        transition: background .15s;
        flex-shrink: 0;
    }
    .mas-nav-link.active .nav-icon,
    .mas-nav-link:hover .nav-icon {
        background: rgba(255,255,255,.18);
    }
    .mas-logout-nav { color: rgba(252,165,165,.85) !important; }
    .mas-logout-nav:hover { background: rgba(239,68,68,.15) !important; color: #fca5a5 !important; }
    .mas-logout-nav .nav-icon { background: rgba(239,68,68,.12) !important; }
    #mas-nav-scroll { overflow-y: auto; flex: 1; padding: 0 16px; }
    #mas-nav-scroll::-webkit-scrollbar { width: 4px; }
    #mas-nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 999px; }
</style>

<aside id="masyarakat-sidebar" class="sidebar-gorgeous">
    {{-- Logo --}}
    <div style="padding:20px 20px 14px;flex-shrink:0;position:relative;z-index:1;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">♻</div>
            <div>
                <p style="font-size:18px;font-weight:900;letter-spacing:-0.5px;color:#fff;line-height:1.1;margin:0;">SiResik</p>
                <p style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:rgba(167,243,208,.65);margin:2px 0 0;">Sistem Informasi Resik</p>
            </div>
        </div>
        {{-- Badge Mode --}}
        <div style="margin-top:14px;">
            <p style="font-size:8px;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:rgba(167,243,208,.55);margin:0 0 6px;">Mode Akses</p>
            <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:8px 12px;">
                <span style="font-size:12px;font-weight:700;color:#fff;">
                    Akses: 
                    @if(auth()->user()->role === 'admin')
                        Administrator
                    @elseif(auth()->user()->role === 'petugas')
                        Petugas
                    @else
                        Masyarakat
                    @endif
                </span>
                <i class="fas fa-chevron-down" style="font-size:10px;color:rgba(167,243,208,.7);"></i>
            </div>
        </div>
    </div>
    <div style="height:1px;background:rgba(255,255,255,.07);margin:0 16px 10px;flex-shrink:0;"></div>

    {{-- Nav Menu --}}
    <div id="mas-nav-scroll" style="position:relative;z-index:1;">
        <nav style="display:flex;flex-direction:column;gap:2px;">
            @php
                $role = auth()->user()->role;
                if ($role === 'admin') {
                    $navItems = [
                        ['label' => 'Dashboard',         'icon' => 'fa-chart-pie', 'route' => 'dashboard.admin'],
                        ['label' => 'Notifikasi',        'icon' => 'fa-bell',      'route' => 'notifications.index'],
                    ];
                } elseif ($role === 'petugas') {
                    $navItems = [
                        ['label' => 'Dashboard',        'icon' => 'fa-chart-pie',         'route' => 'dashboard.petugas'],
                        ['label' => 'Daftar Tugas',     'icon' => 'fa-list-check',        'route' => 'permintaan-penjemputan.index'],
                        ['label' => 'Riwayat Tugas',    'icon' => 'fa-clock-rotate-left', 'route' => 'petugas.riwayat'],
                        ['label' => 'Notifikasi',       'icon' => 'fa-bell',              'route' => 'notifications.index'],
                    ];
                } else {
                    $navItems = [
                        ['label' => 'Dashboard',          'icon' => 'fa-chart-pie',           'route' => 'dashboard.masyarakat'],
                        ['label' => 'Penjemputan',         'icon' => 'fa-truck',               'route' => 'permintaan-penjemputan.index'],
                        ['label' => 'Status Layanan',      'icon' => 'fa-circle-check',        'route' => 'status-layanan.index'],
                        ['label' => 'Riwayat Layanan',     'icon' => 'fa-clock-rotate-left',   'route' => 'riwayat-layanan.index'],
                        ['label' => 'Poin & Reward',       'icon' => 'fa-star',                'route' => 'poin.index'],
                        ['label' => 'Sampah Liar',         'icon' => 'fa-triangle-exclamation','route' => 'sampah-liar.index'],
                        ['label' => 'Peta & Lokasi',       'icon' => 'fa-map-location-dot',    'route' => 'peta.lokasi'],
                        ['label' => 'Usulkan Titik',       'icon' => 'fa-map-pin',             'route' => 'peta.usulan-titik'],
                        ['label' => 'Edukasi Lingkungan',  'icon' => 'fa-book-open',           'route' => 'edukasi-lingkungan.index'],
                        ['label' => 'Kegiatan Lingkungan', 'icon' => 'fa-calendar-days',       'route' => 'kegiatan-lingkungan.index'],
                        ['label' => 'Notifikasi',          'icon' => 'fa-bell',                'route' => 'notifications.index'],
                    ];
                }
            @endphp

            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}" class="mas-nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas {{ $item['icon'] }}"></i></span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Bottom --}}
    <div style="padding:12px 16px 16px;flex-shrink:0;position:relative;z-index:1;">
        <div style="height:1px;background:rgba(255,255,255,.07);margin-bottom:10px;"></div>
        <form action="{{ route('logout') }}" method="POST" style="margin-bottom:10px;">
            @csrf
            <button type="submit" class="mas-nav-link mas-logout-nav">
                <span class="nav-icon"><i class="fas fa-right-from-bracket"></i></span>
                <span>Keluar (Log Out)</span>
            </button>
        </form>
        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:10px 12px;display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#34d399,#059669);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:900;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <p style="font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0;">{{ auth()->user()->name }}</p>
                <p style="font-size:10px;color:rgba(167,243,208,.65);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:2px 0 0;">{{ auth()->user()->email }}</p>
                <p style="font-size:8px;letter-spacing:.15em;text-transform:uppercase;color:rgba(167,243,208,.65);margin:2px 0 0;">
                    @if($role === 'admin')
                        Super Admin
                    @elseif($role === 'petugas')
                        Petugas Lapangan
                    @else
                        Warga Terverifikasi
                    @endif
                </p>
            </div>
        </div>
    </div>
</aside>
