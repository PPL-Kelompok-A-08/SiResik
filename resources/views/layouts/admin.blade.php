<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') – SiResik</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; }

        /* ════ FIXED SIDEBAR ════ */
        #admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 240px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            background: linear-gradient(170deg, #053d2e 0%, #065f46 55%, #047857 100%);
            overflow: hidden;
        }

        /* Decorative blobs */
        #admin-sidebar::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            pointer-events: none;
        }
        #admin-sidebar::after {
            content: '';
            position: absolute;
            bottom: 100px; left: -40px;
            width: 140px; height: 140px;
            border-radius: 50%;
            background: rgba(255,255,255,.03);
            pointer-events: none;
        }

        /* ════ CONTENT AREA ════ */
        #admin-content {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* ════ NAV LINKS ════ */
        .admin-nav-link {
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
        .admin-nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
            transform: translateX(2px);
        }
        .admin-nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.12);
        }
        .admin-nav-link.active::before {
            content: '';
            position: absolute;
            left: -20px;
            width: 3px; height: 22px;
            border-radius: 0 3px 3px 0;
            background: #6ee7b7;
        }
        .admin-nav-link .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 13px;
            transition: background .15s;
        }
        .admin-nav-link.active .nav-icon,
        .admin-nav-link:hover .nav-icon {
            background: rgba(255,255,255,.18);
        }

        /* Logout style */
        .logout-nav {
            color: rgba(252,165,165,.85) !important;
        }
        .logout-nav:hover {
            background: rgba(239,68,68,.15) !important;
            color: #fca5a5 !important;
        }
        .logout-nav .nav-icon {
            background: rgba(239,68,68,.12) !important;
        }

        /* Nav scroll */
        #admin-nav-scroll {
            overflow-y: auto;
            flex: 1;
            padding: 0 16px;
        }
        #admin-nav-scroll::-webkit-scrollbar { width: 4px; }
        #admin-nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 999px; }

        /* Mobile */
        @media (max-width: 1023px) {
            #admin-sidebar { transform: translateX(-100%); transition: transform .25s; }
            #admin-sidebar.open { transform: translateX(0); }
            #admin-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body style="background:#f8fafc;color:#0f172a;">

{{-- ════ FIXED SIDEBAR ════ --}}
<aside id="admin-sidebar">

    {{-- Logo --}}
    <div style="padding:20px 20px 14px;flex-shrink:0;position:relative;z-index:1;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">♻</div>
            <div>
                <p style="font-size:18px;font-weight:900;letter-spacing:-0.5px;color:#fff;line-height:1.1;margin:0;">SiResik</p>
                <p style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:rgba(167,243,208,.65);margin:2px 0 0;">Sistem Informasi Resik</p>
            </div>
        </div>

        {{-- Mode Supervisi --}}
        <div style="margin-top:14px;">
            <p style="font-size:8px;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:rgba(167,243,208,.55);margin:0 0 6px;">Mode Supervisi</p>
            <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:8px 12px;">
                <span style="font-size:12px;font-weight:700;color:#fff;">Akses: Administrator</span>
                <i class="fas fa-chevron-down" style="font-size:10px;color:rgba(167,243,208,.7);"></i>
            </div>
        </div>
    </div>

    {{-- Divider --}}
    <div style="height:1px;background:rgba(255,255,255,.07);margin:0 16px 10px;flex-shrink:0;"></div>

    {{-- Navigation (scrollable) --}}
    <div id="admin-nav-scroll" style="position:relative;z-index:1;">
        <nav style="display:flex;flex-direction:column;gap:2px;">

            <a onclick="showPage('dashboard')" data-page="dashboard"
               class="admin-nav-link active">
                <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                Admin Dashboard
            </a>

            <a onclick="showPage('jadwal')" data-page="jadwal"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                Kelola Jadwal
            </a>

            <a onclick="showPage('verifikasi')" data-page="verifikasi"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span>
                Verifikasi Laporan
            </a>

            <a onclick="showPage('kategori')" data-page="kategori"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-tags"></i></span>
                Kategori &amp; Reward
            </a>

            <a onclick="showPage('reward')" data-page="reward"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-gift"></i></span>
                Kelola Reward
            </a>

            <a onclick="showPage('area')" data-page="area"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-map-location-dot"></i></span>
                Area Layanan
            </a>

            <a onclick="showPage('petugas')" data-page="petugas"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
                Pantau Petugas
            </a>

            <a onclick="showPage('riwayat')" data-page="riwayat"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>
                Riwayat Layanan
            </a>

            <a onclick="showPage('laporan')" data-page="laporan"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-file-lines"></i></span>
                Laporan Periodik
            </a>

            <a onclick="showPage('edukasi')" data-page="edukasi"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-book-open"></i></span>
                Konten Edukasi
            </a>

            <a onclick="showPage('broadcast')" data-page="broadcast"
               class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-bullhorn"></i></span>
                Broadcast
            </a>

        </nav>
    </div>

    {{-- Bottom: Logout + User --}}
    <div style="padding:12px 16px 16px;flex-shrink:0;position:relative;z-index:1;">
        <div style="height:1px;background:rgba(255,255,255,.07);margin-bottom:10px;"></div>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin-bottom:10px;">
            @csrf
            <button type="submit" class="admin-nav-link logout-nav">
                <span class="nav-icon"><i class="fas fa-right-from-bracket"></i></span>
                Keluar (Log Out)
            </button>
        </form>

        {{-- User Card --}}
        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:10px 12px;display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#34d399,#059669);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:900;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <p style="font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0;">{{ auth()->user()->name }}</p>
                <p style="font-size:8px;letter-spacing:.15em;text-transform:uppercase;color:rgba(167,243,208,.65);margin:2px 0 0;">Super Admin</p>
            </div>
        </div>
    </div>
</aside>

{{-- Mobile top bar --}}
<div class="lg:hidden" style="position:fixed;top:0;left:0;right:0;z-index:40;display:flex;align-items:center;gap:12px;padding:10px 16px;background:#065f46;box-shadow:0 2px 12px rgba(0,0,0,.2);">
    <button onclick="toggleAdminSidebar()" style="color:#fff;background:rgba(255,255,255,.15);border:none;border-radius:8px;padding:7px;cursor:pointer;">
        <i class="fas fa-bars" style="font-size:16px;"></i>
    </button>
    <span style="font-size:16px;font-weight:900;color:#fff;">SiResik Admin</span>
</div>

{{-- Mobile overlay --}}
<div id="admin-sidebar-overlay" onclick="toggleAdminSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:39;"></div>

{{-- ════ MAIN CONTENT ════ --}}
<div id="admin-content">
    <div class="max-lg:pt-14">
        @yield('content')
    </div>
</div>

<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');
        const isOpen  = sidebar.classList.toggle('open');
        overlay.style.display = isOpen ? 'block' : 'none';
    }

    // Sync active nav with showPage()
    function setActiveNav(page) {
        document.querySelectorAll('.admin-nav-link[data-page]').forEach(link => {
            link.classList.toggle('active', link.dataset.page === page);
        });
    }
</script>

@stack('scripts')
</body>
</html>
