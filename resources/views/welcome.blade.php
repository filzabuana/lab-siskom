@extends('layouts.app')

@section('content')
<style>
    /* --- 1. Komponen Carousel & Layout --- */
    .hero-carousel-img {
        height: 300px; 
        object-fit: cover; 
    }

    @media (min-width: 992px) {
        .hero-carousel-img {
            height: 400px; 
        }
    }

    /* --- 2. Efek Hover & Interaksi --- */
    .card-alat:hover, .v-card-hover:hover {
        transform: translateY(-5px);
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* --- 3. Komponen Futuristik & Code Style --- */
    .code-wrapper {
        display: inline-block;
        font-family: 'Fira Code', 'Cascadia Code', Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
        background: rgba(13, 202, 240, 0.05);
        padding: 2px 12px;
        border-radius: 4px;
        border-left: 3px solid #0dcaf0;
        line-height: 1.8;
    }

    .code-syntax {
        color: #0dcaf0;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(13, 202, 240, 0.5);
    }

    .typed-cursor {
        color: #0dcaf0;
        font-weight: normal;
        margin-left: 2px;
    }

    /* --- 4. Dark Mode Support --- */
    [data-bs-theme="dark"] .bg-light { background-color: #2b3035 !important; }
    [data-bs-theme="dark"] .card { border: 1px solid #373b3e !important; }

    .bg-highlight {
    background-color: rgba(var(--bs-primary-rgb), 0.03); /* Warna biru sangat tipis */
    border-top: 1px solid rgba(0,0,0,0.05);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
[data-bs-theme="dark"] .bg-highlight {
    background-color: rgba(255, 255, 255, 0.02); /* Abu-abu tipis di dark mode */
}

/* Container utama dengan warna adaptif */
.section-compact {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    border-radius: 2rem;
    padding: 2.5rem 1.5rem;
    margin-bottom: 3rem;
    border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
}

/* Card yang beradaptasi dengan tema */
.card-service-sm {
    background-color: var(--bs-body-bg) !important;
    border: 1px solid rgba(0,0,0,0.08) !important;
    transition: all 0.3s ease;
}

/* Penyesuaian khusus Dark Mode */
[data-bs-theme="dark"] .card-service-sm {
    background-color: rgba(255, 255, 255, 0.03) !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
}

.card-service-sm:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.icon-box-sm {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; /* Mencegah ikon gepeng di mobile */
}

/* Bayangan ikon agar lebih menyala */
.shadow-icon {
    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));
}
</style>

<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

<div class="row align-items-center py-0 py-lg-5 mb-4 mb-lg-5 rounded-4 bg-body-tertiary shadow-sm px-4 mt-0 mt-lg-2">
    <div class="col-12 d-lg-none text-center pt-4 mb-3">
        {{-- "Pusat Layanan Digital" dihapus agar lebih clean --}}
        <h1 class="h3 fw-bold text-body mb-0">Laboratorium Pemrograman & Komputasi</h1>
        <div class="mx-auto mt-2 bg-primary rounded-pill" style="width: 50px; height: 4px;"></div>
    </div>

    <div class="col-lg-6 px-lg-5 order-2 order-lg-1 mt-2 mt-lg-0 pb-4 pb-lg-0">
        {{-- Bagian Desktop Header --}}
        <div class="d-none d-lg-block">
            {{-- Mengganti teks badge di desktop menjadi lebih fungsional --}}
            <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-2 fw-semibold">Rekayasa Sistem Komputer</span>
            <h1 class="display-4 fw-bold text-body mb-3">Laboratorium Pemrograman & Komputasi</h1>
        </div>
        
        <p class="lead text-body-secondary mb-4 text-center text-lg-start" style="min-height: 4.5em; line-height: 1.8;">
            Solusi terintegrasi untuk <br>
            <span class="code-wrapper">
                <span id="typed-text" class="code-syntax"></span>
            </span>
            <br>
            {{-- Class text-body dan fw-medium dihapus agar mengikuti parent-nya (lead & secondary) --}}
            <span class="d-block mt-2">yang efisien.</span>
        </p>

        <div class="d-grid gap-2 d-sm-flex justify-content-center justify-content-lg-start">
            <a href="#layanan" class="btn btn-primary btn-lg px-4 gap-3 rounded-pill">
                <i class="bi bi-lightning-charge me-2"></i>Akses Pelayanan
            </a>
            <a href="/sop" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">   
                <i class="bi bi-file-earmark-text me-2"></i>SOP Laboratorium
            </a>
        </div>
    </div>
    <div class="col-lg-6 text-center order-1 order-lg-2">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner rounded-4 shadow">
                <div class="carousel-item active" data-bs-interval="5000"> 
                    <img src="{{ asset('images/hero-lab.jpeg') }}" class="d-block w-100 hero-carousel-img" alt="Laboratorium">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="{{ asset('images/bioloid.jpeg') }}" class="d-block w-100 hero-carousel-img" alt="Robot Bioloid">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="{{ asset('images/expo.jpeg') }}" class="d-block w-100 hero-carousel-img" alt="Expo">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HIGHLIGHT SECTION LAYANAN -->
<div id="layanan" class="section-compact shadow-sm mt-2">
    <div class="text-center mb-4">
        <h2 class="fw-bold h4 mb-1 text-body">Layanan Utama</h2>
        <div class="mx-auto bg-primary rounded-pill" style="width: 40px; height: 3px;"></div>
    </div>

    <div class="row g-3">
        <!-- Peminjaman Alat -->
        <div class="col-md-4">
            <div class="card h-100 rounded-4 card-service-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start align-items-md-center">
                        <div class="icon-box-sm bg-primary text-white shadow-icon me-3">
                            <i class="bi bi-cpu fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-0" style="font-size: 0.95rem;">Peminjaman Alat</h5>
                            <p class="text-body-secondary mb-1 mt-1" style="font-size: 0.8rem; line-height: 1.2;">Katalog hardware & kit robotika.</p>
                            <a href="#katalog" class="btn btn-sm btn-link p-0 fw-bold text-decoration-none" style="font-size: 0.8rem;">Cek Alat →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bebas Lab -->
        <div class="col-md-4">
            <div class="card h-100 rounded-4 card-service-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start align-items-md-center">
                        <div class="icon-box-sm bg-info text-white shadow-icon me-3">
                            <i class="bi bi-file-earmark-check fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-0" style="font-size: 0.95rem;">Surat Bebas Lab</h5>
                            <p class="text-body-secondary mb-1 mt-1" style="font-size: 0.8rem; line-height: 1.2;">Syarat yudisium & wisuda.</p>
                            <a href="{{ route('bebas-lab.form') }}" class="btn btn-sm btn-link p-0 fw-bold text-decoration-none text-info" style="font-size: 0.8rem;">Ajukan →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualisasi SOP -->
        <div class="col-md-4">
            <div class="card h-100 rounded-4 card-service-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start align-items-md-center">
                        <div class="icon-box-sm bg-success text-white shadow-icon me-3">
                            <i class="bi bi-diagram-3 fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-0" style="font-size: 0.95rem;">Visualisasi Alur</h5>
                            <p class="text-body-secondary mb-1 mt-1" style="font-size: 0.8rem; line-height: 1.2;">Prosedur kerja laboratorium.</p>
                            <a href="/sop" class="btn btn-sm btn-link p-0 fw-bold text-decoration-none text-success" style="font-size: 0.8rem;">Lihat SOP →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END HIGHLIGHT SECTION -->


<!-- BAGIAN KATALOG ALAT (UKURAN LEBIH KECIL + 2 KOLOM MOBILE) -->
<div id="katalog" class="row mb-5 mt-5">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-end align-items-md-center">
            <div style="max-width: 70%;">
                <h2 class="fw-bold text-body mb-0" style="font-size: 1.5rem;">Katalog Alat</h2>
                <p class="text-secondary mb-0 small d-none d-md-block">Daftar aset tersedia di Lab Pemrograman & Komputasi - Untan</p>
                <p class="text-secondary mb-0 d-md-none" style="font-size: 0.75rem;">Aset tersedia di Lab</p>
            </div>
            <a href="{{ url('/katalog') }}" class="btn btn-sm btn-link text-primary fw-bold text-decoration-none p-0 mb-1 mb-md-0">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="row g-2"> <!-- Gutter lebih rapat -->
            @forelse($inventaris->take(4) as $item)
                <div class="col-6 col-md-4 col-lg-3"> <!-- 2 Kolom di Mobile -->
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden v-card-hover">
                        <a href="{{ route('katalog.show', $item->id) }}" class="text-decoration-none text-dark card-link-wrapper">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 110px; overflow: hidden;">
                                @if($item->foto_barang)
                                    <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                                         class="img-fluid w-100 h-100" 
                                         style="object-fit: cover;"
                                         onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=File+Tidak+Ditemukan';">
                                @else
                                    <div class="text-center">
                                        <i class="bi bi-image text-secondary opacity-50" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body pb-0 pt-2 px-2">
                                <h6 class="fw-bold mb-1 text-truncate text-body" style="font-size: 0.85rem;">{{ $item->nama_aset }}</h6>
                                <small class="text-muted d-block mb-2 small" style="font-size: 0.7rem;">{{ $item->kode_barang }}</small>
                            </div>
                        </a>
                        <div class="card-body pt-2 px-2">
                            <div class="mb-3">
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle small" style="font-size: 0.65rem;">
                                        <i class="bi bi-check2-circle"></i> Bisa Dipinjam
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle small" style="font-size: 0.65rem;">
                                        <i class="bi bi-door-open"></i> Gunakan di Lab
                                    </span>
                                @endif
                            </div>
                            @auth
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-secondary fw-bold text-body">Stok: {{ $item->jumlah_stok }}</small>
                                    </div>
                                    <button class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalPinjam{{ $item->id }}"
                                            {{ $item->jumlah_stok <= 0 ? 'disabled' : '' }}>
                                        Pinjam Alat
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100 rounded-pill fw-bold disabled btn-sm" style="cursor: not-allowed;">
                                        Hanya di Lab
                                    </button>
                                @endif
                            @else
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <div class="alert alert-light py-2 px-3 small rounded-3 mb-0 border shadow-sm text-center">
                                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login</a> untuk pinjam.
                                    </div>
                                @else
                                    <div class="alert alert-secondary py-1 px-3 small rounded-3 mb-0 border-0 text-center opacity-75">
                                        Tersedia di Lab
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
                @auth
                    @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                        @include('peminjaman.partials.modal_pinjam')
                    @endif
                @endauth
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-secondary">Maaf, saat ini tidak ada alat tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- BAGIAN ARTIKEL -->
<div id="blog" class="row mb-5 mt-5">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-end align-items-md-center">
            <div style="max-width: 70%;">
                <h2 class="fw-bold text-body mb-0" style="font-size: 1.5rem;">Artikel & Berita</h2>
                <p class="text-secondary mb-0 small d-none d-md-block">Update terbaru dari Lab Pemrograman & Komputasi</p>
                <p class="text-secondary mb-0 d-md-none" style="font-size: 0.75rem;">Update terbaru Lab</p>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-link text-primary fw-bold text-decoration-none p-0 mb-1 mb-md-0">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="row g-3">
            @forelse($latestPosts->take(4) as $post)
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden v-card-hover">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark h-100 d-flex flex-column">
                            <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400?text=Blog+Lab' }}" 
                                 class="card-img-top" style="height: 130px; object-fit: cover;">
                            <div class="card-body flex-grow-1 p-3">
                                <small class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">
                                    {{ $post->created_at->format('d M Y') }}
                                </small>
                                <h6 class="fw-bold mt-2 text-body" style="font-size: 0.9rem;">{{ $post->title }}</h6>
                                <p class="text-secondary small" style="font-size: 0.75rem;">
                                    {{ Str::limit(strip_tags(Str::markdown($post->content ?? '')), 70) }}
                                </p>
                                <span class="btn btn-link text-primary p-0 fw-bold text-decoration-none small" style="font-size: 0.75rem;">
                                    Baca Selengkapnya →
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-secondary">Belum ada artikel yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="row mt-5 pt-5 border-top">
    <div class="col-md-8 mx-auto text-center">
        <h3 class="fw-bold text-body mb-3">Tentang Laboratorium</h3>
        <p class="text-body-secondary">
            Laboratorium Pemrograman & Komputasi Program Studi <strong>Rekayasa Sistem Komputer</strong> Universitas Tanjungpura berfokus pada pengembangan teknologi berbasis 
            <strong>Automation & Embedded System (AES)</strong> dan <strong>Network Intelligent Control (NIC)</strong>. 
        </p>
        <a href="/about" class="btn btn-link text-decoration-none fw-bold p-0">Baca profil selengkapnya <i class="bi bi-arrow-right"></i></a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Typed !== 'undefined') {
            new Typed('#typed-text', {
                strings: [
                    'peminjaman_alat',
                    'riset_penelitian',
                    'layanan_administrasi'
                ],
                typeSpeed: 25,
                backSpeed: 10,
                backDelay: 2000,
                loop: true,
                smartBackspace: true,
                cursorChar: '█',
            });
        }
    });
</script>
@endsection