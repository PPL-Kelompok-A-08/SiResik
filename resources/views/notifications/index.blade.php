<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - SiResik</title>
    <meta name="description" content="Pusat notifikasi SiResik — pantau jadwal, poin, dan laporan Anda.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    {{-- Sidebar Konsisten --}}
    <x-sidebar />

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <main class="flex flex-col gap-5 px-7 py-6">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-black tracking-tight text-slate-800">Pusat Notifikasi</h1>
            <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Unduh Laporan
            </button>
        </header>

        {{-- Success flash --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- ═══ Notification Card ═══ --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

            {{-- Card Header --}}
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-black text-slate-800">Pusat Notifikasi</h2>
                @if ($user->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-[11px] font-black uppercase tracking-widest text-emerald-600 transition hover:text-emerald-800">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>

            {{-- Notification List --}}
            <div class="divide-y divide-slate-100">
                @forelse ($notifications as $notification)
                    @php
                        $data    = $notification->data ?? [];
                        $isUnread = is_null($notification->read_at);
                        $type    = $notification->type ?? '';

                        // Determine icon color & label based on type / status
                        if (str_contains($type, 'ServiceStatusChanged')) {
                            $newStatus = $data['new_status'] ?? '';
                            if ($newStatus === 'Diproses') {
                                $iconColor = 'text-blue-500';
                                $iconBg    = 'bg-blue-50';
                                $title     = 'Jadwal Penjemputan Ditentukan';
                                $body      = 'Status permintaan Anda berubah dari ' . ($data['old_status'] ?? '-') . ' menjadi ' . $newStatus .
                                             (isset($data['jadwal']) ? '. Jadwal: ' . $data['jadwal'] : '');
                            } elseif ($newStatus === 'Selesai') {
                                $iconColor = 'text-emerald-500';
                                $iconBg    = 'bg-emerald-50';
                                $title     = 'Saldo Poin Bertambah!';
                                $body      = 'Penjemputan Anda telah selesai. Poin estimasi sudah ditambahkan ke akun Anda.';
                            } else {
                                $iconColor = 'text-amber-500';
                                $iconBg    = 'bg-amber-50';
                                $title     = 'Laporan Diterima';
                                $body      = 'Status permintaan Anda diperbarui menjadi ' . $newStatus . '.';
                            }
                        } else {
                            $iconColor = 'text-slate-400';
                            $iconBg    = 'bg-slate-100';
                            $title     = $data['title'] ?? 'Notifikasi';
                            $body      = $data['message'] ?? '';
                        }
                    @endphp

                    <div class="flex items-start gap-4 px-6 py-4 transition
                                {{ $isUnread ? 'bg-emerald-50/40' : '' }}">

                        {{-- Bell Icon --}}
                        <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $iconBg }}">
                            <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-black text-slate-800 {{ $isUnread ? '' : 'text-slate-600' }}">
                                        {{ $title }}
                                    </p>
                                    <p class="mt-0.5 text-xs {{ $isUnread ? 'text-emerald-600 font-semibold' : 'text-slate-500' }}">
                                        {{ $body }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 flex-col items-end gap-2">
                                    <span class="text-[11px] text-slate-400 whitespace-nowrap">
                                        {{ $notification->created_at->diffForHumans(null, true, true) }}
                                    </span>
                                    @if ($isUnread)
                                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="text-[10px] font-bold text-emerald-600 transition hover:text-emerald-800">
                                                Tandai Dibaca
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-semibold text-slate-300">✓ Dibaca</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center px-6 py-20 text-center text-slate-400">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                            <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <p class="font-bold text-slate-500">Belum ada notifikasi</p>
                        <p class="mt-1 text-xs">Anda akan menerima notifikasi saat ada pembaruan layanan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($notifications->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>

    </main>
</div>
</body>
</html>
