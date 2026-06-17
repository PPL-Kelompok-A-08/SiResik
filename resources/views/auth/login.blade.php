<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiResik</title>
    <meta name="description" content="Masuk ke platform SiResik — Pintu Masuk Ecosystem.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-10"
      style="background: linear-gradient(160deg, #053d2e 0%, #065f46 40%, #047857 100%);">

    <div class="w-full max-w-md">

        <!-- ═══ LOGIN CARD ═══ -->
        <div class="bg-white rounded-[2rem] shadow-2xl px-8 py-10 relative overflow-hidden">

            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-200">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">SIRESIK</h1>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-slate-400 mt-1">Pintu Masuk Ecosystem</p>
            </div>

            <!-- Role Tabs -->
            <div class="flex bg-gray-100 rounded-full p-1 mb-8">
                <button type="button" onclick="switchTab('masyarakat')" id="tab-masyarakat"
                        class="role-tab flex-1 py-2.5 text-xs font-black uppercase tracking-wider rounded-full transition-all duration-200 bg-white text-slate-900 shadow-sm">
                    Masyarakat
                </button>
                <button type="button" onclick="switchTab('admin')" id="tab-admin"
                        class="role-tab flex-1 py-2.5 text-xs font-black uppercase tracking-wider rounded-full transition-all duration-200 text-slate-400">
                    Admin
                </button>
                <button type="button" onclick="switchTab('petugas')" id="tab-petugas"
                        class="role-tab flex-1 py-2.5 text-xs font-black uppercase tracking-wider rounded-full transition-all duration-200 text-slate-400">
                    Petugas
                </button>
            </div>

            <!-- Error -->
            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600 font-medium">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                        Email / ID Pengguna
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">
                            <i class="far fa-user text-sm"></i>
                        </div>
                        <input type="email" name="email" id="email-input" value="{{ old('email') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 py-3.5 text-sm text-slate-700 outline-none placeholder:text-slate-300 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition"
                               placeholder="name@example.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Kata Sandi
                        </label>
                        <a href="#" class="text-xs font-semibold text-emerald-500 hover:text-emerald-600 italic transition">Lupa Sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password-input"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 py-3.5 text-sm text-slate-700 outline-none placeholder:text-slate-300 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full bg-[#0f3d2e] text-white py-4 rounded-xl text-sm font-black uppercase tracking-wider hover:bg-[#0a2e22] transition-all shadow-lg flex items-center justify-center gap-2">
                    Masuk Sekarang <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Daftar link -->
            <p class="text-center text-sm text-slate-400 mt-6">
                Belum punya akun?
                <a href="#" class="font-bold text-emerald-500 hover:text-emerald-600 italic underline underline-offset-2 transition">Daftar Masyarakat</a>
            </p>

        </div>

        <!-- ═══ CREDENTIAL HINTS ═══ -->
        <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300 mb-3 text-center">
                <i class="fas fa-info-circle mr-1"></i> Akun Demo — Panduan Login
            </p>

            <div class="space-y-2.5">
                <!-- Masyarakat -->
                <div id="hint-masyarakat" class="credential-hint flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 border border-white/10 transition">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users text-emerald-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-wider text-emerald-300">Masyarakat</p>
                        <p class="text-xs text-white/80 mt-0.5 truncate">masyarakat@siresik.local</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] text-white/50">Password</p>
                        <p class="text-xs font-bold text-white/80">password</p>
                    </div>
                </div>

                <!-- Admin -->
                <div id="hint-admin" class="credential-hint flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 border border-white/10 transition">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-halved text-blue-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-wider text-blue-300">Admin</p>
                        <p class="text-xs text-white/80 mt-0.5 truncate">admin@siresik.local</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] text-white/50">Password</p>
                        <p class="text-xs font-bold text-white/80">password</p>
                    </div>
                </div>

                <!-- Petugas -->
                <div id="hint-petugas" class="credential-hint flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 border border-white/10 transition">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hard-hat text-amber-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-wider text-amber-300">Petugas</p>
                        <p class="text-xs text-white/80 mt-0.5 truncate">petugas@siresik.local</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] text-white/50">Password</p>
                        <p class="text-xs font-bold text-white/80">password</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ═══ SCRIPT ═══ -->
    <script>
        const credentials = {
            masyarakat: { email: 'masyarakat@siresik.local', password: 'password' },
            admin:      { email: 'admin@siresik.local',      password: 'password' },
            petugas:    { email: 'petugas@siresik.local',     password: 'password' },
        };

        function switchTab(role) {
            // Update tab styles
            document.querySelectorAll('.role-tab').forEach(tab => {
                tab.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                tab.classList.add('text-slate-400');
            });
            const active = document.getElementById('tab-' + role);
            active.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            active.classList.remove('text-slate-400');

            // Auto-fill email
            document.getElementById('email-input').value = credentials[role].email;
            document.getElementById('password-input').value = credentials[role].password;

            // Highlight corresponding hint
            document.querySelectorAll('.credential-hint').forEach(h => {
                h.style.background = 'rgba(255,255,255,0.1)';
                h.style.borderColor = 'rgba(255,255,255,0.1)';
            });
            const hint = document.getElementById('hint-' + role);
            hint.style.background = 'rgba(255,255,255,0.18)';
            hint.style.borderColor = 'rgba(255,255,255,0.25)';
        }
    </script>

</body>
</html>
