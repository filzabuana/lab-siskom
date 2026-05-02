@extends('layouts.app')

@section('content')
<div class="container py-3">
    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-0 text-body">Panel Kendali Admin</h4>
                    <p class="text-body-secondary small mb-0">Lab. Pemrograman & Komputasi - Untan</p>
                </div>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill border border-primary-subtle small">
                    <i class="bi bi-shield-lock-fill"></i> Admin Access
                </span>
            </div>
        </div>
    </div>

    {{-- Row 1: Statistik Ringkas --}}
    <div class="row g-2 mb-4">
        <!-- Statistik SOP -->
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 custom-card-bg">
                <div class="card-body p-2 p-md-3 text-center text-md-start d-md-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 p-md-3 mb-1 mb-md-0 me-md-3 d-inline-block">
                        <i class="bi bi-file-earmark-text-fill fs-5 fs-md-4"></i>
                    </div>
                    <div class="text-truncate w-100">
                        <div class="text-secondary d-none d-md-block" style="font-size: 0.8rem;">Total SOP</div>
                        <div class="text-secondary d-md-none" style="font-size: 0.65rem;">SOP</div>
                        <h5 class="fw-bold mb-0 mt-md-1 fs-6 fs-md-4 text-body">{{ \App\Models\Sop::count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistik Surat Bebas Lab -->
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 custom-card-bg">
                <div class="card-body p-2 p-md-3 text-center text-md-start d-md-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 p-md-3 mb-1 mb-md-0 me-md-3 d-inline-block">
                        <i class="bi bi-patch-check-fill fs-5 fs-md-4"></i>
                    </div>
                    <div class="text-truncate w-100">
                        <div class="text-secondary d-none d-md-block" style="font-size: 0.8rem;">Surat Selesai</div>
                        <div class="text-secondary d-md-none" style="font-size: 0.65rem;">Surat</div>
                        <h5 class="fw-bold mb-0 mt-md-1 fs-6 fs-md-4 text-body">{{ \App\Models\SuratBebasLab::where('status', 'selesai')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistik Peminjaman -->
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 custom-card-bg">
                <div class="card-body p-2 p-md-3 text-center text-md-start d-md-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 p-md-3 mb-1 mb-md-0 me-md-3 d-inline-block">
                        <i class="bi bi-arrow-repeat fs-5 fs-md-4"></i>
                    </div>
                    <div class="text-truncate w-100">
                        <div class="text-secondary d-none d-md-block" style="font-size: 0.8rem;">Total Pinjam</div>
                        <div class="text-secondary d-md-none" style="font-size: 0.65rem;">Pinjam</div>
                        <h5 class="fw-bold mb-0 mt-md-1 fs-6 fs-md-4 text-body">{{ \App\Models\Peminjaman::count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Shortcut Cards --}}
    <div class="row g-3">
        <!-- 1. Bebas Lab (Paling Kiri) -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center custom-card-bg">
                <div class="card-body p-3">
                    <div class="bg-success bg-opacity-10 p-2 p-md-3 rounded-4 d-inline-block mb-2 position-relative">
                        <i class="bi bi-person-check-fill text-success fs-3"></i>
                        @php $notifBebasLab = \App\Models\SuratBebasLab::where('status', 'pending')->count(); @endphp
                        @if($notifBebasLab > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.7rem;">
                                {{ $notifBebasLab }}
                            </span>
                        @endif
                    </div>
                    <h6 class="fw-bold mb-2 text-body">Bebas Lab</h6>
                    <a href="{{ route('admin.bebas-lab.index') }}" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">Cek</a>
                </div>
            </div>
        </div>

        <!-- 2. Peminjaman -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center custom-card-bg">
                <div class="card-body p-3">
                    <div class="bg-warning bg-opacity-10 p-2 p-md-3 rounded-4 d-inline-block mb-2 position-relative">
                        <i class="bi bi-cart-check-fill text-warning fs-3"></i>
                        @php $notifPeminjaman = \App\Models\Peminjaman::where('status', 'pending')->count(); @endphp
                        @if($notifPeminjaman > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.7rem;">
                                {{ $notifPeminjaman }}
                            </span>
                        @endif
                    </div>
                    <h6 class="fw-bold mb-2 text-body">Peminjaman</h6>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-warning btn-sm w-100 rounded-pill fw-bold text-white">Kelola</a>
                </div>
            </div>
        </div>

        <!-- 3. Inventaris -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center custom-card-bg">
                <div class="card-body p-3">
                    <div class="bg-info bg-opacity-10 p-2 p-md-3 rounded-4 d-inline-block mb-2">
                        <i class="bi bi-boxes text-info fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-2 text-body">Inventaris</h6>
                    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-info btn-sm w-100 rounded-pill fw-bold text-white">Buka</a>
                </div>
            </div>
        </div>

        <!-- 4. SOP -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center custom-card-bg">
                <div class="card-body p-3">
                    <div class="bg-primary bg-opacity-10 p-2 p-md-3 rounded-4 d-inline-block mb-2">
                        <i class="bi bi-journal-plus text-primary fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-2 text-body">Kelola SOP</h6>
                    <a href="{{ route('sop.index') }}" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">Atur</a>
                </div>
            </div>
        </div>

        <!-- 5. Manajemen User (Tambahan Baru) -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center custom-card-bg">
                <div class="card-body p-3">
                    <div class="bg-danger bg-opacity-10 p-2 p-md-3 rounded-4 d-inline-block mb-2">
                        <i class="bi bi-people-fill text-danger fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-2 text-body">User & Akun</h6>
                    {{-- Ganti ke route user index milik Bapak --}}
                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold">Kelola</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .custom-card-bg { background-color: #ffffff; }

    /* Dark Mode Handling */
    [data-bs-theme="dark"] body { background-color: #121212; }
    [data-bs-theme="dark"] .custom-card-bg { background-color: #1e1e1e; border: 1px solid #2d2d2d !important; }
    [data-bs-theme="dark"] .text-secondary { color: #a0a0a0 !important; }

    .card { transition: all 0.2s; border-radius: 16px !important; }
    .card:hover { transform: translateY(-3px); }
    
    @media (max-width: 576px) {
        .container { padding-left: 12px; padding-right: 12px; }
        h4 { font-size: 1.15rem; }
        .card-body { padding: 0.75rem !important; }
    }
</style>
@endsection