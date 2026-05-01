@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="display-6">Repository SOP</h2>
        @auth
        <a href="{{ route('sop.create') }}" class="btn btn-success">+ Tambah SOP</a>
        @endauth
        <p class="text-muted">Daftar Standar Operasional Prosedur Laboratorium Pemrograman dan Komputasi.</p>
        <hr>
    </div>
</div>

<div class="row">
    @foreach($semuaSop as $sop)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <span class="badge bg-info text-dark mb-2">{{ $sop->kategori }}</span>
                <h5 class="card-title">{{ $sop->judul }}</h5>
                <p class="card-text text-secondary">{{ Str::limit($sop->deskripsi, 100) }}</p>
            </div>
            <div class="card-footer bg-transparent border-top-0 pb-3">
                <a href="{{ route('sop.show', $sop->slug) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                <a href="{{ asset('dokumen-sop/' . $sop->file_pdf) }}" 
                class="btn btn-primary btn-sm" 
                download="{{ $sop->file_pdf }}">
                <i class="bi bi-download"></i> Download PDF
                </a>
                @auth
    <hr>
    <div class="d-flex justify-content-end">
        <a href="{{ route('sop.edit', $sop->id) }}" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <form action="{{ route('sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SOP ini? File di server juga akan terhapus.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash"></i> Hapus SOP
            </button>
        </form>
    </div>
@endauth
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection