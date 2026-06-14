<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiResik') – Petugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ── Sidebar fixed ── */
        #petugas-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            background: linear-gradient(160deg, #064e3b 0%, #065f46 60%, #047857 100%);
            box-shadow: 4px 0 32px rgba(0,0,0,.25);
            overflow: hidden;
        }

        /* Decorative circles */
        #petugas-sidebar::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            pointer-events: none;
        }
        #petugas-sidebar::after {
            content: '';
            position: absolute;
            bottom: 80px; left: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            pointer-events: none;
        }

        /* ── Content area pushed right ── */
        #petugas-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ── Nav link ── */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(209,250,229,.75);
            transition: background .18s, color .18s, transform .15s;
            text-decoration: none;
            position: relative;
        }
        .nav-link:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
            transform: translateX(3px);
        }
        .nav-link.active {
            background: rgba(255,255,255,.18);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.15);
        }
        .nav-link.active .nav-icon {
            background: rgba(255,255,255,.2);
        }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }

        .nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px; height: 34px;
            border-radius: 10px;
            background: rgba(255,255,255,.08);
            transition: background .18s;
        }

        /* ── Active indicator dot ── */
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -24px;
            width: 4px; height: 28px;
            border-radius: 0 4px 4px 0;
            background: #6ee7b7;
        }

        /* ── Logout button ── */
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 11px 16px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(252,165,165,.85);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: background .18s, color .18s;
        }
        .logout-btn:hover {
            background: rgba(239,68,68,.15);
            color: #fca5a5;
        }
        .logout-btn .nav-icon { background: rgba(239,68,68,.15); }

        /* ── Mobile overlay toggle ── */
        @media (max-width: 1023px) {
            #petugas-sidebar { transform: translateX(-100%); transition: transform .25s; }
            #petugas-sidebar.open { transform: translateX(0); }
            #petugas-content { margin-left: 0; }
        }

        /* Scrollbar thin */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 999px; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-900">

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<aside id="petugas-sidebar">

    {{-- Logo --}}
    <div class="px-6 pt-7 pb-5 flex items-center gap-3 relative z-10">
        <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">♻</div>
        <div>
            <p style="font-size:20px;font-weight:900;letter-spacing:-0.5px;color:#fff;line-height:1.1">SiResik</p>
            <p style="font-size:9px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(167,243,208,.7);margin-top:2px">Sistem Informasi Resik</p>
        </div>
    </div>

    {{-- Divider --}}
    <div style="height:1px;background:rgba(255,255,255,.08);margin:0 20px 20px"></div>

    {{-- Label menu --}}
    <p style="font-size:9px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(167,243,208,.5);padding:0 22px;margin-bottom:8px">Menu</p>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1 relative z-10" style="overflow-y:auto;">

        {{-- Tugas Saya --}}
        <a href="{{ route('dashboard.petugas') }}"
           class="nav-link {{ request()->routeIs('dashboard.petugas') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </span>
            Tugas Saya
        </a>

        {{-- Riwayat Tugas --}}
        <a href="{{ route('petugas.riwayat') }}"
           class="nav-link {{ request()->routeIs('petugas.riwayat') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            Riwayat Tugas
        </a>

    </nav>

    {{-- Bottom section --}}
    <div class="px-4 pb-6 relative z-10 space-y-1">

        {{-- Divider --}}
        <div style="height:1px;background:rgba(255,255,255,.08);margin-bottom:12px"></div>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </span>
                Keluar (Log Out)
            </button>
        </form>

        {{-- User card --}}
        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:12px 14px;display:flex;align-items:center;gap:10px;margin-top:8px;">
            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#34d399,#059669);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <p style="font-size:13px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
                <p style="font-size:9px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(167,243,208,.7);margin-top:2px;">Field Operator</p>
            </div>
        </div>

    </div>
</aside>

{{-- ═══════════════ MOBILE TOP BAR ═══════════════ --}}
<div class="lg:hidden fixed top-0 left-0 right-0 z-40 flex items-center gap-4 px-4 py-3"
     style="background:#065f46;box-shadow:0 2px 12px rgba(0,0,0,.2);">
    <button onclick="toggleSidebar()" style="color:#fff;background:rgba(255,255,255,.15);border:none;border-radius:10px;padding:8px;cursor:pointer;display:flex;">
        <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <span style="font-size:17px;font-weight:900;color:#fff;">SiResik</span>
</div>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" onclick="toggleSidebar()"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

{{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
<div id="petugas-content">
    <div class="pt-0 lg:pt-0 max-lg:pt-14">
        @yield('content')
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar  = document.getElementById('petugas-sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        const isOpen   = sidebar.classList.toggle('open');
        overlay.classList.toggle('hidden', !isOpen);
    }
</script>

@stack('scripts')
</body>
</html>
