@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light btn-sm text-secondary">← Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <h5 class="font-weight-bold text-success border-bottom pb-2 mb-3">Informasi Acara</h5>
                <h4 class="font-weight-bold text-dark mb-3">{{ $kegiatan->judul }}</h4>
                <p class="small text-muted">📍 <b>Tempat:</b> {{ $kegiatan->lokasi }}</p>
                <p class="small text-muted">📅 <b>Hari H:</b> {{ $kegiatan->tanggal->format('d M Y') }}</p>
                <p class="small text-muted">📊 <b>Kapasitas:</b> {{ $kegiatan->peserta->count() }} / {{ $kegiatan->kuota }} Terisi</p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm bg-white rounded-lg overflow-hidden">
                <div class="card-header bg-dark text-white font-weight-bold">Warga Terdaftar Bertugas</div>
                <table class="table table-striped mb-0 small">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Waktu Registrasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan->peserta as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><b>{{ $user->name }}</b></td>
                                <td>{{ $user->email }}</td>
                                <td class="font-mono text-secondary">{{ $user->pivot->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada warga yang melakukan registrasi kupon.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection