@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Judul Halaman -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Tentang Kami</h2>
        <p class="text-muted">Mengenal lebih dekat tim di balik Laboratorium Pemrograman & Komputasi</p>
        <div class="mx-auto" style="width: 60px; height: 4px; background-color: #0d6efd; border-radius: 2px;"></div>
    </div>

    <!-- Profil Tim (Struktur Kolom Responsif) -->
    <div class="row g-4 justify-content-center">
        
        <!-- Kepala Laboratorium -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="text-center pt-5 pb-4 bg-light">
                    <div class="mx-auto rounded-circle shadow-sm border border-4 border-white" style="width: 140px; height: 140px; overflow: hidden; background-color: #e9ecef;">
                        <img src="{{ asset('images/suhardi.png') }}" alt="Suhardi" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                </div>
                <div class="card-body text-center p-4">
                    <h5 class="fw-bold mb-0">Suhardi, S.T., M.Eng.</h5>
                    <p class="text-muted small mb-2">NIP198606182020121006</p> <!-- Silakan isi NIP di sini -->
                    <p class="text-primary small fw-bold mb-3">Kepala Laboratorium</p>
                    <p class="card-text text-secondary small mb-4">Dosen Program Studi Rekayasa Sistem Komputer dengan fokus pada Automation & Embedded System dan IoT.</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" target="_blank" class="btn btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" target="_blank" class="btn btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PLP (Profil Bapak Filza) -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="text-center pt-5 pb-4 bg-light">
                    <div class="mx-auto rounded-circle shadow-sm border border-4 border-white" style="width: 140px; height: 140px; overflow: hidden; background-color: #e9ecef;">
                        <img src="{{ asset('images/filza.jpg') }}" alt="Filza Buana Putra" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                </div>
                <div class="card-body text-center p-4">
                    <h5 class="fw-bold mb-0">Filza Buana Putra, S.Mat.</h5>
                    <p class="text-muted small mb-2">NIP199611192025061007</p> <!-- Silakan isi NIP di sini -->
                    <p class="text-success small fw-bold mb-3">Pranata Laboratorium Pendidikan (PLP)</p>
                    <p class="card-text text-secondary small mb-4">Mengelola operasional harian, perawatan fasilitas, dan pendampingan teknis praktikum mahasiswa.</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="https://www.instagram.com/filzabuana" target="_blank" class="btn btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/filza-buana-putra-45a5a41a3" target="_blank" class="btn btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Lokasi & Jam Operasional -->
    <div class="mt-5 p-4 rounded-4 bg-white shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-3 mb-lg-0">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi Laboratorium</h5>
                <p class="text-secondary mb-0">Gedung Sistem Komputer, FMIPA Untan</p>
                <p class="text-secondary small">Jl. Prof. Dr. H. Hadari Nawawi, Bansir Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat.</p>
            </div>
            <div class="col-lg-5">
                <div class="p-3 rounded-3 bg-light border-start border-4 border-primary">
                    <h6 class="fw-bold mb-2"><i class="bi bi-clock-fill text-primary me-2"></i>Jam Operasional</h6>
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>Senin - Kamis:</span>
                        <span class="fw-semibold">07:30 - 16:00 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>Jumat:</span>
                        <span class="fw-semibold">07:30 - 16:30 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mt-1 border-top pt-1">
                        <span class="text-danger italic">Sabtu - Minggu:</span>
                        <span class="badge bg-danger-subtle text-danger">Tutup</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection