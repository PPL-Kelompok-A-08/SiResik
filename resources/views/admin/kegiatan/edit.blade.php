@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light btn-sm text-secondary">← Batal</a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm bg-white rounded-lg">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="font-weight-bold mb-0">Sunting Rincian Kegiatan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.kegiatan.update', $kegiatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted">JUDUL AGENDA</label>
                            <input type="text" class="form-control" name="judul" value="{{ old('judul', $kegiatan->judul) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted">LOKASI</label>
                            <input type="text" class="form-control" name="lokasi" value="{{ old('lokasi', $kegiatan->lokasi) }}" required>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-muted">TANGGAL</label>
                                <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-muted">KUOTA MAKSIMAL</label>
                                <input type="number" class="form-control" name="kuota" value="{{ old('kuota', $kegiatan->kuota) }}" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted">ISI DESKRIPSI</label>
                            <textarea class="form-control" name="deskripsi" rows="5" required>{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2.5">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection