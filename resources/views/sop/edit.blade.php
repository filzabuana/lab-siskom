@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4 text-dark">Edit SOP: {{ $sop->judul }}</h3>
            
            <form action="{{ route('sop.update', $sop->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Judul SOP</label>
                        <input type="text" name="judul" class="form-control" value="{{ $sop->judul }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategori</label>
                        <input type="text" name="kategori" class="form-control" value="{{ $sop->kategori }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ $sop->deskripsi }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-primary">Update File PDF (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="file" name="file_pdf" class="form-control">
                    <small class="text-muted">File saat ini: {{ $sop->file_pdf }}</small>
                </div>

                <hr>
                <h5 class="fw-bold mb-3">Edit Alur Visual (Mermaid.js)</h5>
                
                <div id="wrapper-alur">
                    @foreach($listAlur as $index => $alur)
                    <div class="alur-item border p-3 rounded-3 mb-3 bg-light">
                        <div class="mb-2">
                            <label class="small fw-bold">Judul Alur</label>
                            <input type="text" name="alur_judul[]" class="form-control form-control-sm" value="{{ $alur['judul'] }}" required>
                        </div>
                        <div>
                            <label class="small fw-bold">Kode Mermaid.js</label>
                            <textarea name="alur_kode[]" class="form-control font-monospace small" rows="5" required>{{ $alur['kode'] }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Simpan Perubahan</button>
                    <a href="{{ route('sop.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection