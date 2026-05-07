@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5 justify-content-center text-center">
        <div class="col-lg-8">
            <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-2 fw-semibold">Warta & Riset</span>
            <h1 class="display-5 fw-bold text-body">Blog Laboratorium</h1>
            <p class="lead text-body-secondary">Eksplorasi teknologi, tutorial pemrograman, dan pembaruan kegiatan terbaru dari kami.</p>
            <hr class="w-25 mx-auto opacity-25">
        </div>
    </div>

    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden v-card-hover">
                    <div class="position-relative">
                        <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400?text=Lab+Article' }}" 
                             class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $post->title }}">
                        <div class="position-absolute bottom-0 start-0 p-3">
                            <span class="badge bg-dark bg-opacity-75 backdrop-blur px-3 py-2">
                                {{ $post->created_at->format('d M, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-body stretched-link">
                                {{ $post->title }}
                            </a>
                        </h4>
                        <p class="text-body-secondary small mb-4">
                            @php
                                // 1. Hapus simbol Markdown menggunakan Regex
                                $cleanMarkdown = preg_replace('/[*#_~`>\[\]\(\)]/', '', $post->content);
                                // 2. Bersihkan spasi berlebih dan potong teks
                                $preview = Str::limit(strip_tags($cleanMarkdown), 120);
                            @endphp
                            {{ $preview }}
                        </p>
                        <div class="d-flex align-items-center text-primary fw-bold small">
                            BACA SELENGKAPNYA <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-journal-x display-1 text-secondary opacity-25"></i>
                <p class="text-secondary mt-3">Belum ada artikel yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $posts->links() }}
    </div>
</div>

<style>
    .backdrop-blur { backdrop-filter: blur(5px); }
    .v-card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .v-card-hover:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
</style>
@endsection