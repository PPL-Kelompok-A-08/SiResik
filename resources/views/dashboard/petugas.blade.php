@extends('layouts.petugas')

@section('title', 'Tugas Saya - SiResik')

@section('content')
<div style="background:#f8fafc;min-height:100vh;">

    {{-- ══ TOP BAR ══ --}}
    <div style="background:#fff;border-bottom:1px solid #e2e8f0;padding:14px 28px;display:flex;align-items:center;justify-content:space-between;">
        <h1 style="font-size:16px;font-weight:800;color:#0f172a;margin:0;">Pusat Tugas Petugas</h1>
    </div>

    <div style="padding:24px 28px;">

        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#15803d;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- ══ STATUS KETERSEDIAAN ══ --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <p style="font-size:13px;font-weight:700;color:#0f172a;margin:0 0 2px;">Status Ketersediaan</p>
                <p style="font-size:12px;color:#94a3b8;margin:0;">Status aktif ini menentukan distribusi tugas penjemputan.</p>
            </div>
            <div style="display:flex;gap:8px;">
                <button style="display:flex;align-items:center;gap:7px;background:#fff;border:1.5px solid #6ee7b7;border-radius:20px;padding:7px 18px;font-size:12px;font-weight:700;color:#065f46;cursor:pointer;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;flex-shrink:0;"></span>
                    Aktif Bertugas
                </button>
                <button style="background:#f1f5f9;border:1.5px solid #e2e8f0;border-radius:20px;padding:7px 18px;font-size:12px;font-weight:600;color:#64748b;cursor:pointer;">
                    Istirahat
                </button>
            </div>
        </div>

        {{-- ══ FILTER TABS ══ --}}
        @php
            $totalAll       = $permintaan->count();
            $totalPending   = $permintaan->where('status','Menunggu')->count();
            $totalOngoing   = $permintaan->where('status','Diproses')->count();
            $totalCompleted = $permintaan->where('status','Selesai')->count();
        @endphp
        <div style="display:flex;gap:4px;margin-bottom:16px;border-bottom:2px solid #e2e8f0;padding-bottom:0;">
            @php
                $tabs = [
                    ['key'=>'all',       'label'=>'ALL',       'count'=>$totalAll,       'color'=>'#0f172a'],
                    ['key'=>'pending',   'label'=>'PENDING',   'count'=>$totalPending,   'color'=>'#2563eb'],
                    ['key'=>'ongoing',   'label'=>'ONGOING',   'count'=>$totalOngoing,   'color'=>'#d97706'],
                    ['key'=>'completed', 'label'=>'COMPLETED', 'count'=>$totalCompleted, 'color'=>'#059669'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <button
                    type="button"
                    data-filter="{{ $tab['key'] }}"
                    onclick="filterTasks('{{ $tab['key'] }}')"
                    class="tab-btn"
                    style="background:none;border:none;border-bottom:2px solid transparent;margin-bottom:-2px;padding:8px 14px;font-size:12px;font-weight:700;color:#94a3b8;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .15s;"
                    data-active-color="{{ $tab['color'] }}">
                    {{ $tab['label'] }}
                    <span style="background:#f1f5f9;border-radius:20px;padding:1px 7px;font-size:11px;">{{ $tab['count'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- ══ TASK LIST ══ --}}
        <div id="task-list" style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
            @forelse ($permintaan as $item)
                @php
                    $statusKey = $item->status === 'Menunggu' ? 'pending' : ($item->status === 'Diproses' ? 'ongoing' : 'completed');
                    $categories = $item->items->pluck('kategoriSampah.nama')->filter()->values();
                    $isMine = $item->petugas_id === auth()->id();
                    $canTake = $item->status === 'Menunggu' && is_null($item->petugas_id);
                    $canUpload = $item->status === 'Diproses' && $isMine;
                    $isDone = $item->status === 'Selesai';

                    $badgeBg = match($item->status) {
                        'Menunggu' => '#eff6ff', 'Diproses' => '#fff7ed', 'Selesai' => '#f0fdf4', default => '#f1f5f9',
                    };
                    $badgeColor = match($item->status) {
                        'Menunggu' => '#1d4ed8', 'Diproses' => '#c2410c', 'Selesai' => '#15803d', default => '#64748b',
                    };
                    $badgeLabel = match($item->status) {
                        'Menunggu' => 'PICKUP', 'Diproses' => 'URGENT', 'Selesai' => 'COMPLETED', default => $item->status,
                    };
                    $borderColor = match($item->status) {
                        'Menunggu' => '#3b82f6', 'Diproses' => '#f59e0b', 'Selesai' => '#10b981', default => '#e2e8f0',
                    };

                    $totalBerat = $item->items->sum('estimasi_berat');
                    $categoryStr = $categories->isEmpty() ? 'Sampah Umum' : $categories->join(', ');
                @endphp

                <article
                    data-status="{{ $statusKey }}"
                    style="background:#fff;border:1px solid #e8edf2;border-left:4px solid {{ $borderColor }};border-radius:12px;overflow:hidden;">

                    {{-- Main row --}}
                    <div style="padding:14px 18px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">

                        {{-- LEFT: Info --}}
                        <div style="flex:1;min-width:0;">
                            {{-- Badge row --}}
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
                                <span style="background:{{ $badgeBg }};color:{{ $badgeColor }};font-size:9px;font-weight:800;padding:3px 8px;border-radius:5px;letter-spacing:.1em;text-transform:uppercase;">
                                    {{ $badgeLabel }}
                                </span>
                                <span style="font-size:11px;color:#94a3b8;font-weight:500;">
                                    {{ optional($item->created_at)->format('d M Y · H:i') }}
                                </span>
                                @if($isMine)
                                    <span style="font-size:10px;color:#2563eb;font-weight:700;background:#eff6ff;border-radius:5px;padding:2px 8px;">
                                        ● Petugas: Anda
                                    </span>
                                @endif
                            </div>

                            {{-- Alamat --}}
                            <p style="font-size:16px;font-weight:800;color:#0f172a;margin:0 0 5px;line-height:1.3;">
                                {{ $item->alamat ?: 'Alamat tidak tersedia' }}
                            </p>

                            {{-- Kategori --}}
                            <div style="display:flex;align-items:center;gap:5px;">
                                <span style="color:#10b981;font-size:12px;">♻</span>
                                <span style="font-size:12px;color:#475569;font-weight:500;">{{ $categoryStr }}</span>
                            </div>
                        </div>

                        {{-- RIGHT: Action buttons --}}
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0;">
                            @if($canTake)
                                <form action="{{ route('petugas.terima', $item) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit"
                                            style="background:#0f172a;color:#fff;border:none;border-radius:8px;padding:9px 20px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap;">
                                        Terima Tugas
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </form>

                            @elseif($canUpload)
                                <a href="{{ route('petugas.bukti.show', $item) }}"
                                   style="background:#f59e0b;color:#fff;border-radius:8px;padding:9px 18px;font-size:12px;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:6px;white-space:nowrap;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Ambil Foto
                                </a>
                                <a href="{{ route('petugas.bukti.show', $item) }}"
                                   style="background:#059669;color:#fff;border-radius:8px;padding:9px 18px;font-size:12px;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:6px;white-space:nowrap;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Selesaikan Tugas
                                </a>

                            @elseif($isDone)
                                <div style="text-align:right;">
                                    <span style="background:#f0fdf4;color:#15803d;border-radius:8px;padding:7px 14px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Selesai
                                    </span>
                                    <p style="font-size:10px;color:#94a3b8;margin:4px 0 0;text-align:right;">
                                        {{ optional($item->updated_at)->format('d M · H:i') }}
                                    </p>
                                </div>

                            @else
                                <span style="background:#f1f5f9;color:#94a3b8;border-radius:8px;padding:8px 14px;font-size:11px;font-weight:600;white-space:nowrap;">
                                    Menunggu penugasan
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Footer bar --}}
                    <div style="border-top:1px solid #f1f5f9;padding:8px 18px;background:#fafafa;display:flex;align-items:center;gap:16px;">
                        <span style="font-size:11px;color:#94a3b8;">
                            📦 Perkiraan:
                            <strong style="color:#64748b;">{{ $totalBerat > 0 ? number_format($totalBerat, 1).' Kg' : 'Tidak diketahui' }}</strong>
                        </span>
                        @if($item->jadwal)
                        <span style="font-size:11px;color:#94a3b8;">
                            🕐 {{ $item->jadwal }}
                        </span>
                        @endif
                        <span style="font-size:11px;color:#94a3b8;margin-left:auto;">
                            JOB-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </article>
            @empty
                <div style="background:#fff;border:2px dashed #e2e8f0;border-radius:12px;padding:40px;text-align:center;color:#94a3b8;">
                    <div style="font-size:40px;margin-bottom:12px;">📋</div>
                    <p style="font-weight:600;margin:0 0 4px;">Belum ada permintaan tersedia.</p>
                    <p style="font-size:13px;margin:0;">Tunggu tugas penjemputan baru dari warga.</p>
                </div>
            @endforelse
        </div>

        {{-- ══ RUTE HARI INI (Sampah Liar) ══ --}}
        @if(!empty($laporanSampahLiar) && $laporanSampahLiar->isNotEmpty())
        <div style="margin-bottom:12px;">
            <p style="font-size:11px;font-weight:800;letter-spacing:.15em;text-transform:uppercase;color:#94a3b8;margin:0 0 10px;">
                RUTE SAMPAH LIAR HARI INI
            </p>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px;margin-bottom:10px;">
                @foreach($laporanSampahLiar as $lap)
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <div style="min-width:0;">
                        <p style="font-size:11px;color:#94a3b8;margin:0 0 2px;font-weight:600;">SAMPAH LIAR · SL-{{ str_pad($lap->id, 3, '0', STR_PAD_LEFT) }}</p>
                        <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $lap->lokasi ?? 'Lokasi tidak diketahui' }}</p>
                        <p style="font-size:12px;color:#64748b;margin:2px 0 0;">{{ $lap->pengguna?->name ?? 'Pelapor anonim' }}</p>
                    </div>
                    <div style="flex-shrink:0;">
                        @if(is_null($lap->petugas_id))
                            <form action="{{ route('petugas.terima.sampah_liar', $lap) }}" method="POST" style="margin:0;">
                                @csrf
                                <button style="background:#059669;color:#fff;border:none;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;cursor:pointer;">Terima</button>
                            </form>
                        @elseif($lap->petugas_id === auth()->id() && $lap->status !== 'ditangani')
                            <a href="{{ route('petugas.bukti.sampah_liar.show', $lap) }}"
                               style="background:#f59e0b;color:#fff;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;text-decoration:none;display:inline-block;">
                                Bukti
                            </a>
                        @else
                            <span style="background:#f0fdf4;color:#059669;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;">Ditangani</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══ SOP NOTICE ══ --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 16px;display:flex;align-items:flex-start;gap:10px;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
            <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">
                <strong style="color:#475569;">SOP Lapangan:</strong>
                Pastikan foto bukti diambil saat proses penjemputan, tidak lebih dari 30 menit setelah selesai. Foto yang tidak jelas atau tidak sesuai bisa membatalkan konfirmasi tugas dan berdampak pada penilaian petugas.
            </p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterTasks(status) {
        // Update cards
        document.querySelectorAll('[data-status]').forEach(card => {
            card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
        });

        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            const isActive = btn.dataset.filter === status;
            const activeColor = btn.dataset.activeColor || '#0f172a';
            btn.style.color       = isActive ? activeColor : '#94a3b8';
            btn.style.borderBottomColor = isActive ? activeColor : 'transparent';
        });
    }

    document.addEventListener('DOMContentLoaded', () => filterTasks('all'));
</script>
@endpush
