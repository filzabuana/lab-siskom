@extends('layouts.app')

@section('content')
<div class="row align-items-center py-3 py-lg-5 mb-5 rounded-4 bg-white shadow-sm px-4 d-flex flex-column flex-lg-row">
    
    <div class="col-12 d-lg-none text-center mb-3">
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-2 fw-semibold">Pusat Layanan Digital</span>
        <h1 class="h2 fw-bold text-dark">Laboratorium Pemrograman & Komputasi</h1>
    </div>

    <div class="col-lg-6 px-lg-5 order-2 order-lg-1 mt-4 mt-lg-0">
        <div class="d-none d-lg-block">
            <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-2 fw-semibold">Pusat Layanan Digital</span>
            <h1 class="display-4 fw-bold text-dark mb-3">Laboratorium Pemrograman & Komputasi</h1>
        </div>
        
        <p class="lead text-secondary mb-4 text-center text-lg-start">Solusi terintegrasi untuk peminjaman alat praktikum, penelitian, dan administrasi laboratorium yang efisien.</p>
        
        <div class="d-grid gap-2 d-sm-flex justify-content-center justify-content-lg-start">
            <a href="/sop" class="btn btn-primary btn-lg px-4 gap-3 rounded-pill">
                <i class="bi bi-folder2-open me-2"></i>Akses Repository SOP
            </a>
            <a href="{{ url('/about') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">   
        <i class="bi bi-info-circle me-2"></i>Tentang Kami
    </a>
        </div>
    </div>

    <div class="col-lg-6 text-center order-1 order-lg-2">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner rounded-4 shadow">
                <div class="carousel-item active" data-bs-interval="5000"> 
                    <img src="{{ asset('images/hero-lab.jpeg') }}" class="d-block w-100" alt="Robot Komputasi">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="{{ asset('images/bioloid.jpeg') }}" class="d-block w-100" alt="Robot Bioloid">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="{{ asset('images/expo.jpeg') }}" class="d-block w-100" alt="Expo">
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</div>
<div class="row text-center">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold text-dark">Layanan</h2>
        <p class="text-muted">Akses <a href="https://google.com">cepat</a> berbagai kebutuhan administratif dan fasilitas laboratorium.</p>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="feature-icon bg-primary text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-cpu"></i>
                </div>
                <h4 class="fw-bold">Peminjaman Alat</h4>
                <p class="text-secondary small">Prosedur mudah untuk meminjam hardware praktikum, kit robotika, dan perangkat penelitian.</p>
                <span class="text-muted small fw-italic fst-italic">Dalam pengembangan</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="feature-icon bg-info text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <h4 class="fw-bold">Surat Bebas Lab</h4>
                <p class="text-secondary small">Pelayanan online untuk mengurus surat keterangan bebas pinjaman sebagai syarat yudisium/wisuda.</p>
                <span class="text-muted small fw-italic fst-italic">Dalam pengembangan</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="feature-icon bg-success text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <h4 class="fw-bold">Visualisasi Alur</h4>
                <p class="text-secondary small">Lihat alur prosedur kerja (SOP) laboratorium yang informatif menggunakan diagram visual.</p>
                <a href="/sop" class="btn btn-link text-success p-0 fw-semibold text-decoration-none">Lihat Alur →</a>
            </div>
        </div>
    </div>
</div>
@endsection