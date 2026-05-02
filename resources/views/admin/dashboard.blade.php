<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold mb-0">Panel Kendali Admin</h2>
                    <p class="text-body-secondary">Laboratorium Pemrograman & Komputasi - Untan</p>
                </div>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill border border-primary-subtle">
                    <i class="bi bi-shield-lock-fill me-1"></i> Admin Access
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Shortcut Kelola SOP -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block mb-3">
                        <i class="bi bi-journal-plus text-primary fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Manajemen SOP</h5>
                    <p class="text-body-secondary small">Update prosedur kerja dan modul praktikum mahasiswa.</p>
                    <a href="{{ route('sop.index') }}" class="btn btn-primary w-100 rounded-pill fw-bold">Kelola Sekarang</a>
                </div>
            </div>
        </div>

        <!-- Shortcut Kelola Inventaris -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-4 d-inline-block mb-3">
                        <i class="bi bi-boxes text-info fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Inventaris Alat</h5>
                    <p class="text-body-secondary small">Pantau stok, kondisi barang, dan lokasi penyimpanan aset lab.</p>
                    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-info w-100 rounded-pill fw-bold text-white">Buka Inventaris</a>
                </div>
            </div>
        </div>

        <!-- Shortcut Verifikasi Bebas Lab -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4 d-inline-block mb-3 position-relative">
                        <i class="bi bi-person-check-fill text-success fs-2"></i>
                        
                        @if(($notifPengajuan ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm border border-white" style="font-size: 0.85rem; padding: 0.4em 0.6em;">
                                {{ $notifPengajuan }}
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold">Bebas Lab</h5>
                    <p class="text-body-secondary small">
                        @if(($notifPengajuan ?? 0) > 0)
                            Ada <strong>{{ $notifPengajuan }}</strong> pengajuan menunggu validasi Anda.
                        @else
                            Belum ada pengajuan baru yang masuk.
                        @endif
                    </p>
                    
                    <a href="{{ route('admin.bebas-lab.index') }}" class="btn btn-success w-100 rounded-pill fw-bold text-white d-flex align-items-center justify-content-center">
                        <span>Cek Pengajuan</span>
                        @if(($notifPengajuan ?? 0) > 0)
                            <span class="badge bg-white text-success ms-2 rounded-pill">{{ $notifPengajuan }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: var(--bs-tertiary-bg); }
    .card { background-color: var(--bs-card-bg); transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
</style>