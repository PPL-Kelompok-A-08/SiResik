@php
    $unreadCount = auth()->user()->unreadNotifications->count();
@endphp

<div class="relative">
    <button onclick="toggleNotifications()" class="relative inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Notifikasi -->
    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto">
        <div class="p-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">Notifikasi</h3>
        </div>

        @php
            $notifications = auth()->user()->notifications()->latest()->take(10)->get();
        @endphp

        @if($notifications->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($notifications as $notification)
                    @if($notification->type === 'App\Notifications\ServiceStatusChanged')
                        <div class="p-4 hover:bg-slate-50 transition {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900">
                                        Status Perubahan Permintaan Penjemputan
                                    </p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $notification->data['alamat'] ?? 'N/A' }}<br>
                                        <strong>{{ $notification->data['old_status'] ?? '' }}</strong> → <strong>{{ $notification->data['new_status'] ?? '' }}</strong>
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if(is_null($notification->read_at))
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="ml-2">
                                        @csrf
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 text-xs font-medium">
                                            Tandai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="p-3 border-t border-slate-100 text-center">
                <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua Notifikasi
                </a>
            </div>
        @else
            <div class="p-8 text-center text-slate-500">
                <p>Tidak ada notifikasi</p>
            </div>
        @endif
    </div>
</div>

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('hidden');
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', function(event) {
    const button = event.target.closest('button');
    const dropdown = document.getElementById('notificationDropdown');
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
