@extends('layouts.app')

@section('content')
<style>
    /* Mengunci tinggi carousel agar seragam */
    .hero-carousel-img {
        height: 300px; 
        object-fit: cover; 
    }

    @media (min-width: 992px) {
        .hero-carousel-img {
            height: 400px; 
        }
    }

    /* Hover effect untuk card alat */
    .card-alat:hover {
        transform: translateY(-5px);
        transition: 0.3s;
    }
</style>

<!-- Hero Section -->
<div class="row align-items-center py-3 py-lg-5 mb-5 rounded-4 bg-body-tertiary shadow-sm px-4">
    
    <div class="col-12 d-lg-none text-center mb-4">
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-2 fw-semibold">Pusat Layanan Digital</span>
        <h1 class="h2 fw-bold text-body">Laboratorium Pemrograman & Komputasi</h1>
    </div>

    <div class="col-lg-6 px-lg-5 order-2 order-lg-1 mt-2 mt-lg-0">
        <div class="d-none d-lg-block">
            <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-2 fw-semibold">Pusat Layanan Digital</span>
            <h1 class="display-4 fw-bold text-body mb-3">Laboratorium Pemrograman & Komputasi</h1>
        </div>
        
        <p class="lead text-body-secondary mb-4 text-center text-lg-start">Solusi terintegrasi untuk peminjaman alat praktikum, penelitian, dan administrasi laboratorium yang efisien.</p>
        
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

<!-- Layanan Section -->
<div id="layanan" class="row text-center mb-5">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold text-body">Layanan Digital</h2>
        <p class="text-body-secondary">Pilih jenis pelayanan laboratorium yang Anda butuhkan.</p>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4 text-body">
                <div class="feature-icon bg-primary text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-cpu"></i>
                </div>
                <h4 class="fw-bold">Peminjaman Alat</h4>
                <p class="text-body-secondary small">Hardware praktikum, kit robotika, dan perangkat penelitian Rekayasa Sistem Komputer.</p>
                <a href="#katalog" class="btn btn-link text-primary p-0 fw-semibold text-decoration-none">Lihat Katalog Alat →</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4 text-body">
                <div class="feature-icon bg-info text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <h4 class="fw-bold">Surat Bebas Lab</h4>
                <p class="text-body-secondary small">Pengurusan surat keterangan bebas pinjaman syarat yudisium/wisuda.</p>
                <a href="{{ route('bebas-lab.form') }}" class="btn btn-link text-info p-0 fw-semibold text-decoration-none">Ajukan Sekarang →</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4 text-body">
                <div class="feature-icon bg-success text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <h4 class="fw-bold">Visualisasi Alur</h4>
                <p class="text-body-secondary small">Informasi alur prosedur kerja (SOP) laboratorium secara visual.</p>
                <a href="/sop" class="btn btn-link text-success p-0 fw-semibold text-decoration-none">Lihat Alur →</a>
            </div>
        </div>
    </div>
</div>

<!-- Katalog Alat Section -->
<div id="katalog" class="row mb-5 mt-5">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-body mb-0">Katalog Alat</h2>
                <p class="text-secondary mb-0">Daftar aset tersedia di Lab Pemrograman & Komputasi - Untan</p>
            </div>
            <a href="{{ url('/katalog') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="row g-4">
            @forelse($inventaris->take(4) as $item)
                <div class="col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden v-card-hover">
                        <a href="{{ route('katalog.show', $item->id) }}" class="text-decoration-none text-dark card-link-wrapper">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px; overflow: hidden;">
                                @if($item->foto_barang)
                                    <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                                         alt="{{ $item->nama_aset }}" 
                                         class="img-fluid w-100 h-100" 
                                         style="object-fit: cover;"
                                         onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=File+Tidak+Ditemukan';">
                                @else
                                    <div class="text-center">
                                        <i class="bi bi-image text-secondary opacity-50" style="font-size: 3rem;"></i>
                                        <small class="d-block text-muted opacity-75 mt-1" style="font-size: 0.6rem;">No Image Available</small>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body pb-0">
                                <h6 class="fw-bold mb-1 text-truncate text-body">{{ $item->nama_aset }}</h6>
                                <small class="text-muted d-block mb-2 small">{{ $item->kode_barang }}</small>
                            </div>
                        </a>

                        <div class="card-body pt-2">
                            <div class="mb-3">
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle small">
                                        <i class="bi bi-check2-circle"></i> Bisa Dipinjam
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle small">
                                        <i class="bi bi-door-open"></i> Gunakan di Lab
                                    </span>
                                @endif
                            </div>
                            
                            @auth
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-secondary fw-bold text-body">Stok: {{ $item->jumlah_stok }}</small>
                                    </div>
                                    <button class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalPinjam{{ $item->id }}"
                                            {{ $item->jumlah_stok <= 0 ? 'disabled' : '' }}>
                                        Pinjam Alat
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100 rounded-pill fw-bold disabled" style="cursor: not-allowed;">
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

<!-- Section About Ringkas -->
<div class="row mt-5 pt-5 border-top">
    <div class="col-md-8 mx-auto text-center">
        <h3 class="fw-bold text-body mb-3">Tentang Laboratorium</h3>
        <p class="text-body-secondary">
            Laboratorium Pemrograman & Komputasi Program Studi <strong>Rekayasa Sistem Komputer</strong> Universitas Tanjungpura berfokus pada pengembangan teknologi berbasis 
            <strong>Automation & Embedded System (AES)</strong> dan <strong>Network Intelligent Control (NIC)</strong>. 
            Kami menjadi pusat riset dan inovasi dalam mengeksplorasi kontrol cerdas, sistem tertanam, dan otomasi.
        </p>
        <a href="/about" class="btn btn-link text-decoration-none fw-bold p-0">Baca profil selengkapnya <i class="bi bi-arrow-right"></i></a>
    </div>
</div>

<style>
    .card-link-wrapper { display: block; cursor: pointer; }
    .v-card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .v-card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    [data-bs-theme="dark"] .bg-light { background-color: #2b3035 !important; }
    [data-bs-theme="dark"] .card { border: 1px solid #373b3e !important; }
</style>
@endsection