@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0 text-dark">Manajemen Blog</h2>
            <p class="text-muted mb-0">Kelola artikel dan warta laboratorium melalui antarmuka terpadu.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>Tulis Artikel Baru
            </a>
        </div>
    </div>

    {{-- Stats Cards (Opsional tapi keren) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 me-3">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Artikel</small>
                        <h4 class="fw-bold mb-0">{{ $posts->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kamu bisa tambah stats lain seperti 'Draft' atau 'Published' di sini --}}
    </div>

    {{-- Main Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase">Konten</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Info Publikasi</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Status</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/120x80?text=No+Image' }}" 
                                     class="rounded-3 shadow-sm me-3" 
                                     style="width: 100px; height: 60px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $post->title }}</div>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="bi bi-link-45deg me-1"></i>/blog/{{ $post->slug }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-dark"><i class="bi bi-calendar-event me-2"></i>{{ $post->created_at->format('d M Y') }}</div>
                                <div class="text-muted"><i class="bi bi-clock me-2"></i>{{ $post->created_at->format('H:i') }} WIB</div>
                            </div>
                        </td>
                        <td>
                            @if($post->is_published)
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Published
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">
                                    <i class="bi bi-pause-circle me-1"></i> Draft
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group shadow-sm rounded-pill">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-white btn-sm px-3" title="Lihat">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-white btn-sm px-3" title="Edit">
                                    <i class="bi bi-pencil-square text-info"></i>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-white btn-sm px-3" title="Hapus" onclick="return confirm('Hapus artikel ini?')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                Belum ada artikel yang dibuat.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($posts->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .btn-white {
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
    .btn-white:hover {
        background-color: #f8f9fa;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.02);
    }
    .badge {
        font-size: 0.75rem;
        letter-spacing: 0.3px;
    }
</style>
@endsection