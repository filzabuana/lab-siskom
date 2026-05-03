@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Judul Halaman -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-body">Tentang Kami</h2>
        <p class="text-body-secondary px-lg-5">
            Laboratorium Pemrograman & Komputasi Program Studi <strong>Rekayasa Sistem Komputer</strong> Universitas Tanjungpura berfokus pada pengembangan teknologi berbasis 
            <strong>Automation & Embedded System (AES)</strong> dan <strong>Network Intelligent Control (NIC)</strong>.
        </p>
        <div class="mx-auto" style="width: 60px; height: 4px; background-color: #0d6efd; border-radius: 2px;"></div>
    </div>

    <!-- Profil Tim -->
    <div class="row g-4 justify-content-center mb-5">
        <!-- Kepala Laboratorium -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-body-tertiary">
                <div class="text-center pt-5 pb-4 bg-body-secondary">
                    <div class="mx-auto rounded-circle shadow-sm border border-4 border-body" style="width: 140px; height: 140px; overflow: hidden; background-color: var(--bs-tertiary-bg);">
                        <img src="{{ asset('images/suhardi.png') }}" alt="Suhardi" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                </div>
                <div class="card-body text-center p-4">
                    <h5 class="fw-bold mb-0 text-body">Suhardi, S.T., M.Eng.</h5>
                    <p class="text-body-secondary small mb-2">NIP 198606182020121006</p>
                    <p class="text-primary small fw-bold mb-3">Kepala Laboratorium</p>
                    <p class="card-text text-body-secondary small mb-4">Dosen Program Studi Rekayasa Sistem Komputer dengan fokus pada Automation & Embedded System dan IoT.</p>
                    
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
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-body-tertiary">
                <div class="text-center pt-5 pb-4 bg-body-secondary">
                    <div class="mx-auto rounded-circle shadow-sm border border-4 border-body" style="width: 140px; height: 140px; overflow: hidden; background-color: var(--bs-tertiary-bg);">
                        <img src="{{ asset('images/filza.jpg') }}" alt="Filza Buana Putra" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                </div>
                <div class="card-body text-center p-4">
                    <h5 class="fw-bold mb-0 text-body">Filza Buana Putra, S.Mat.</h5>
                    <p class="text-body-secondary small mb-2">NIP 199611192025061007</p>
                    <p class="text-success small fw-bold mb-3">Pranata Laboratorium Pendidikan (PLP)</p>
                    <p class="card-text text-body-secondary small mb-4">Mengelola operasional harian, perawatan fasilitas, dan pendampingan teknis praktikum mahasiswa.</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="https://www.instagram.com/filzabuana" target="_blank" class="btn btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/filza-buana-putra-45a5a41a3" target="_blank" class="btn btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="https://github.com/filzabuana" target="_blank" class="btn btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-github"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profil Laboratorium (Fokus Riset) -->
    <div class="row g-4 mb-5">
        <div class="col-md-12">
            <div class="p-4 p-lg-5 rounded-4 bg-body-tertiary shadow-sm border border-secondary-subtle">
                <h3 class="fw-bold text-body mb-4">Profil Laboratorium</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <p class="text-body-secondary">
                            Laboratorium Pemrograman & Komputasi merupakan unit pendukung akademik di lingkungan Program Studi Rekayasa Sistem Komputer, Fakultas Matematika dan Ilmu Pengetahuan Alam, Universitas Tanjungpura. Kami berdedikasi untuk menciptakan ekosistem pembelajaran yang adaptif terhadap kemajuan teknologi global.
                        </p>
                        <p class="text-body-secondary">
                            Visi kami adalah menjadi pusat unggulan dalam pengembangan sistem kontrol cerdas dan otomasi yang memberikan kontribusi nyata bagi masyarakat melalui pendidikan, penelitian, dan pengabdian.
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-primary-subtle border-start border-4 border-primary">
                                    <h6 class="fw-bold text-primary mb-1">Automation & Embedded System (AES)</h6>
                                    <p class="small text-body-secondary mb-0">Fokus pada pengembangan sistem tertanam, robotika, dan otomasi industri berbasis IoT.</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-info-subtle border-start border-4 border-info">
                                    <h6 class="fw-bold text-info mb-1">Network Intelligent Control (NIC)</h6>
                                    <p class="small text-body-secondary mb-0">Fokus pada manajemen jaringan cerdas, keamanan data, dan kontrol sistem jarak jauh.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Lokasi & Jam Operasional -->
    <div class="p-4 rounded-4 bg-body-tertiary shadow-sm border border-secondary-subtle">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-3 mb-lg-0">
                <h5 class="fw-bold mb-3 text-body"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi Laboratorium</h5>
                <p class="text-body mb-0">Gedung Sistem Komputer, FMIPA Universitas Tanjungpura</p>
                <p class="text-body-secondary small">Jl. Prof. Dr. H. Hadari Nawawi, Bansir Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat.</p>
            </div>
            <div class="col-lg-5">
                <div class="p-3 rounded-3 bg-body-secondary border-start border-4 border-primary">
                    <h6 class="fw-bold mb-2 text-body"><i class="bi bi-clock-fill text-primary me-2"></i>Jam Operasional</h6>
                    <div class="d-flex justify-content-between small text-body-secondary">
                        <span>Senin - Kamis:</span>
                        <span class="fw-semibold text-body">07:30 - 16:00 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between small text-body-secondary">
                        <span>Jumat:</span>
                        <span class="fw-semibold text-body">07:30 - 16:30 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between small text-body-secondary mt-1 border-top border-secondary-subtle pt-1">
                        <span class="text-danger italic">Sabtu - Minggu:</span>
                        <span class="badge bg-danger-subtle text-danger">Tutup</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection