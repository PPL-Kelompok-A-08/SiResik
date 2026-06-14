@extends('layouts.petugas')

@section('title', 'Riwayat Tugas - SiResik')

@section('content')
<div style="background:#f8fafc;min-height:100vh;">

    {{-- ══ TOP BAR ══ --}}
    <div style="background:#fff;border-bottom:1px solid #e2e8f0;padding:14px 28px;display:flex;align-items:center;justify-content:space-between;">
        <h1 style="font-size:16px;font-weight:800;color:#0f172a;margin:0;">Riwayat Penjemputan</h1>
        <a href="{{ route('petugas.riwayat') }}"
           style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:7px 14px;font-size:12px;font-weight:600;color:#64748b;text-decoration:none;display:flex;align-items:center;gap:6px;">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Unduh Laporan
        </a>
    </div>

    <div style="padding:24px 28px;">

        {{-- ══ MAIN CARD ══ --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">

            {{-- Card Header --}}
            <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <h2 style="font-size:16px;font-weight:800;color:#0f172a;margin:0 0 4px;">Riwayat Layanan</h2>
                    <p style="font-size:12px;color:#94a3b8;margin:0;">Daftar lengkap riwayat dan partisipasi kebersihan Anda.</p>
                </div>

                {{-- Filter Tabs --}}
                <div style="display:flex;gap:4px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:3px;">
                    <a href="{{ route('petugas.riwayat') }}"
                       style="padding:5px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;
                              {{ !request('status') ? 'background:#0f172a;color:#fff;' : 'color:#64748b;' }}">
                        Semua
                    </a>
                    <a href="{{ route('petugas.riwayat', ['status'=>'Diproses']) }}"
                       style="padding:5px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;
                              {{ request('status')==='Diproses' ? 'background:#0f172a;color:#fff;' : 'color:#64748b;' }}">
                        Dipenjemputan
                    </a>
                    <a href="{{ route('petugas.riwayat', ['status'=>'Selesai']) }}"
                       style="padding:5px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;
                              {{ request('status')==='Selesai' ? 'background:#0f172a;color:#fff;' : 'color:#64748b;' }}">
                        Terselesaikan
                    </a>
                </div>
            </div>

            {{-- Filter Tambahan --}}
            <div style="padding:12px 24px;border-bottom:1px solid #f1f5f9;background:#fafafa;">
                <form method="GET" action="{{ route('petugas.riwayat') }}" style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="date" name="dari" value="{{ request('dari') }}"
                           style="border:1px solid #e2e8f0;border-radius:8px;padding:5px 10px;font-size:12px;color:#475569;background:#fff;outline:none;">
                    <span style="font-size:12px;color:#94a3b8;">s/d</span>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                           style="border:1px solid #e2e8f0;border-radius:8px;padding:5px 10px;font-size:12px;color:#475569;background:#fff;outline:none;">
                    <button type="submit"
                            style="background:#0f172a;color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:12px;font-weight:700;cursor:pointer;">
                        Cari
                    </button>
                    @if(request()->anyFilled(['dari','sampai']))
                        <a href="{{ route('petugas.riwayat', request('status') ? ['status'=>request('status')] : []) }}"
                           style="color:#64748b;font-size:12px;text-decoration:none;font-weight:600;border:1px solid #e2e8f0;border-radius:8px;padding:6px 12px;background:#fff;">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABLE --}}
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                            <th style="text-align:left;padding:10px 16px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;white-space:nowrap;">ID Transaksi</th>
                            <th style="text-align:left;padding:10px 12px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Jenis</th>
                            <th style="text-align:left;padding:10px 12px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Tanggal</th>
                            <th style="text-align:left;padding:10px 12px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Lokasi</th>
                            <th style="text-align:left;padding:10px 12px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Detail Item</th>
                            <th style="text-align:right;padding:10px 12px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Poin</th>
                            <th style="text-align:center;padding:10px 16px;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permintaan as $item)
                            @php
                                $categories = $item->items->pluck('kategoriSampah.nama')->filter()->values();
                                $totalPoin  = $item->items->sum('estimasi_poin');

                                $jenis = $item->status === 'Selesai' ? 'Cleaning' : 'Pickup';
                                $jenisIcon = $jenis === 'Pickup'
                                    ? '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 11a2 2 0 002 2h8a2 2 0 002-2l1-11"/></svg>'
                                    : '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>';

                                $statusBg = match($item->status) {
                                    'Selesai'  => '#f0fdf4',
                                    'Diproses' => '#fefce8',
                                    default    => '#f0f9ff',
                                };
                                $statusColor = match($item->status) {
                                    'Selesai'  => '#15803d',
                                    'Diproses' => '#92400e',
                                    default    => '#0369a1',
                                };
                                $statusLabel = match($item->status) {
                                    'Selesai'  => 'Selesai',
                                    'Diproses' => 'Proses',
                                    default    => 'Menunggu',
                                };

                                $detailItems = $categories->isEmpty()
                                    ? 'Sampah umum'
                                    : ($categories->count() > 2 ? $categories->take(2)->join(', ') . ' +' . ($categories->count()-2) : $categories->join(', '));
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;transition:background .12s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">

                                {{-- ID Transaksi --}}
                                <td style="padding:12px 16px;white-space:nowrap;">
                                    <span style="font-size:12px;font-weight:700;color:#64748b;font-family:monospace;">
                                        TRX-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                {{-- Jenis --}}
                                <td style="padding:12px 12px;white-space:nowrap;">
                                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#475569;">
                                        {!! $jenisIcon !!}
                                        {{ $jenis }}
                                    </span>
                                </td>

                                {{-- Tanggal --}}
                                <td style="padding:12px 12px;white-space:nowrap;">
                                    <span style="font-size:12px;color:#475569;">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </span>
                                </td>

                                {{-- Lokasi --}}
                                <td style="padding:12px 12px;max-width:180px;">
                                    <span style="font-size:12px;color:#0f172a;font-weight:600;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;" title="{{ $item->alamat }}">
                                        {{ $item->alamat ?: '-' }}
                                    </span>
                                </td>

                                {{-- Detail Item --}}
                                <td style="padding:12px 12px;max-width:180px;">
                                    <span style="font-size:12px;color:#64748b;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;" title="{{ $categories->join(', ') }}">
                                        {{ $detailItems }}
                                    </span>
                                </td>

                                {{-- Poin --}}
                                <td style="padding:12px 12px;text-align:right;white-space:nowrap;">
                                    @if($item->status === 'Selesai')
                                        <span style="font-size:13px;font-weight:800;color:#059669;">
                                            +{{ number_format($totalPoin/1000, 1) }}k Poin
                                        </span>
                                    @elseif($item->status === 'Diproses')
                                        <span style="font-size:13px;font-weight:700;color:#d97706;">
                                            ~{{ number_format($totalPoin/1000, 1) }}k Poin
                                        </span>
                                    @else
                                        <span style="font-size:12px;color:#94a3b8;">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td style="padding:12px 16px;text-align:center;">
                                    <span style="display:inline-block;background:{{ $statusBg }};color:{{ $statusColor }};font-size:10px;font-weight:800;padding:3px 10px;border-radius:6px;letter-spacing:.05em;text-transform:uppercase;">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding:48px;text-align:center;color:#94a3b8;">
                                    <div style="font-size:36px;margin-bottom:10px;">📋</div>
                                    <p style="font-weight:600;margin:0 0 4px;color:#64748b;">Belum ada riwayat tugas.</p>
                                    <p style="font-size:12px;margin:0;">
                                        @if(request()->anyFilled(['status','dari','sampai']))
                                            Coba ubah filter pencarian.
                                        @else
                                            Riwayat akan muncul setelah tugas diselesaikan.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($permintaan->hasPages())
                <div style="padding:14px 24px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
                    {{ $permintaan->links() }}
                </div>
            @endif

        </div>{{-- end card --}}

        {{-- ══ STATISTIK KONTRIBUSI BANNER ══ --}}
        @php
            $totalSelesai  = $permintaan->where('status','Selesai')->count();
            $totalPoinAll  = $permintaan->where('status','Selesai')->sum(fn($p) => $p->items->sum('estimasi_poin'));
            $totalBeratAll = $permintaan->where('status','Selesai')->sum(fn($p) => $p->items->sum('berat_kg'));
        @endphp
        <div style="margin-top:16px;background:linear-gradient(120deg,#0f172a 0%,#1e293b 100%);border-radius:16px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#475569;margin:0 0 6px;">STATISTIK KONTRIBUSI</p>
                <p style="font-size:24px;font-weight:900;color:#fff;margin:0;line-height:1.2;">
                    Total <span style="color:#34d399;">{{ number_format($totalBeratAll, 0) }} kg</span>
                    <span style="font-weight:500;font-size:18px;color:#94a3b8;"> Sampah Terolah</span>
                </p>
            </div>
            <div style="display:flex;gap:32px;">
                <div style="text-align:center;">
                    <p style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#475569;margin:0 0 4px;">Total Selesai</p>
                    <p style="font-size:22px;font-weight:900;color:#fff;margin:0;">{{ $totalSelesai }}<span style="font-size:13px;font-weight:600;color:#64748b;"> kat</span></p>
                </div>
                <div style="text-align:center;">
                    <p style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#475569;margin:0 0 4px;">Total Poin</p>
                    <p style="font-size:22px;font-weight:900;color:#34d399;margin:0;">{{ number_format($totalPoinAll/1000, 0) }}<span style="font-size:13px;font-weight:600;color:#059669;"> k Poin</span></p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
