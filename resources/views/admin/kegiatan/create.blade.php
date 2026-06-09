@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light btn-sm text-secondary">← Kembali ke List</a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm bg-white rounded-lg">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="font-weight-bold mb-0">Tambah Kegiatan Lingkungan Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.kegiatan.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted">NAMA / JUDUL AGENDA *</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Gotong Royong Akbar Kelurahan">
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted">LOKASI TITIK KUMPUL *</label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Lapangan Utama Balai Desa">
                            @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-muted">TANGGAL PELAKSANAAN *</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal') }}">
                                @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-muted">LIMIT KUOTA PESERTA *</label>
                                <input type="number" class="form-control @error('kuota') is-invalid @enderror" name="kuota" value="{{ old('kuota', 20) }}" min="1">
                                @error('kuota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted">DESKRIPSI DETAIL & MANFAAT *</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="5" placeholder="Petunjuk khusus atau logistik perlengkapan daur ulang yang disediakan..."></textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2.5">Simpan & Rilis Kegiatan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection