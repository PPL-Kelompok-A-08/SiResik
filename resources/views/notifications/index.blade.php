<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900">Notifikasi</h1>
                        <p class="mt-1 text-slate-600">Kelola dan lihat semua notifikasi Anda</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            @if(auth()->user()->unreadNotifications->count() > 0)
                <div class="mb-6">
                    <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                            </svg>
                            Tandai Semua Sebagai Dibaca
                        </button>
                    </form>
                </div>
            @endif

            <!-- Notifications List -->
            <div class="space-y-4">
                @if($notifications->count() > 0)
                    @foreach($notifications as $notification)
                        @if($notification->type === 'App\Notifications\ServiceStatusChanged')
                            <div class="bg-white rounded-lg shadow-sm border-l-4 {{ is_null($notification->read_at) ? 'border-blue-500 bg-blue-50' : 'border-slate-300' }} p-6">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-full {{ is_null($notification->read_at) ? 'bg-blue-100' : 'bg-slate-100' }}">
                                            <svg class="w-6 h-6 {{ is_null($notification->read_at) ? 'text-blue-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900">
                                                    Status Perubahan Permintaan Penjemputan
                                                </h3>
                                                <p class="mt-2 text-slate-700">
                                                    <strong>Alamat:</strong> {{ $notification->data['alamat'] ?? 'N/A' }}
                                                </p>
                                                <p class="mt-2 text-slate-700">
                                                    <strong>Tanggal:</strong> {{ $notification->data['tanggal'] ?? 'N/A' }}<br>
                                                    <strong>Jadwal:</strong> {{ $notification->data['jadwal'] ?? 'N/A' }}
                                                </p>
                                                <div class="mt-3 inline-flex items-center gap-2">
                                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 text-sm font-semibold rounded-full">
                                                        {{ $notification->data['old_status'] ?? 'N/A' }}
                                                    </span>
                                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                                        {{ $notification->data['new_status'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <p class="mt-3 text-sm text-slate-500">
                                                    {{ $notification->created_at->format('d F Y H:i') }} ({{ $notification->created_at->diffForHumans() }})
                                                </p>
                                            </div>
                                            @if(is_null($notification->read_at))
                                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="ml-4 px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 transition">
                                                        Tandai Dibaca
                                                    </button>
                                                </form>
                                            @else
                                                <span class="ml-4 px-3 py-1 bg-slate-100 text-slate-600 text-sm font-medium rounded">
                                                    ✓ Dibaca
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-slate-600 mb-2">Tidak ada notifikasi</h3>
                        <p class="text-slate-500">Anda belum memiliki notifikasi apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
