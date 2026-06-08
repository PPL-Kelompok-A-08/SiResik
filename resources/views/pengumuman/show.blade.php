@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('pengumuman.index') }}" class="btn btn-light btn-sm text-secondary">← Kembali ke Mading</a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm p-4 p-md-5 bg-white rounded-lg">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 small text-muted">
                    <span class="badge badge-success px-2 py-1">OFFICIAL ANNOUNCEMENT</span>
                    <span>Terbit: {{ $pengumuman->tanggal_publish->format('l, d F Y') }}</span>
                </div>
                <h2 class="font-weight-bold text-dark mb-4">{{ $pengumuman->judul }}</h2>
                <div class="text-muted leading-relaxed" style="white-space: pre-line; font-size: 1.05rem;">
                    {{ $pengumuman->isi }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection