@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb: Adaptif dengan Dark Mode --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none">Manajemen User</a>
            </li>
            <li class="breadcrumb-item active text-body-secondary" aria-current="page">
                {{ $user->name }}
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Kolom Kiri: Profil Mahasiswa --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 custom-card-bg">
                <div class="mb-3">
                    <div class="bg-primary bg-opacity-10 d-inline-block p-4 rounded-circle">
                        <i class="bi bi-person-badge-fill text-primary fs-1"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-1 text-body">{{ $user->name }}</h5>
                <p class="text-body-secondary small mb-3">{{ $user->email }}</p>
                <div class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 mb-4">
                    NIM: {{ $user->nim ?? 'N/A' }}
                </div>
                
                <div class="d-grid gap-2">
                    @php 
                        $isClear = $riwayat->where('status', 'disetujui')->count() === 0;
                    @endphp

                    @if($isClear)
                        <button class="btn btn-success rounded-pill fw-bold btn-sm py-2">
                            <i class="bi bi-patch-check-fill me-1"></i> Proses Bebas Lab
                        </button>
                    @else
                        <button class="btn btn-secondary rounded-pill fw-bold btn-sm py-2" disabled title="Masih ada alat yang dipinjam">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum Bebas Lab
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Tanggungan & Riwayat Alat --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden custom-card-bg">
                <div class="card-header bg-transparent border-0 py-3 ps-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-body">Tanggungan & Riwayat Alat</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                        Total: {{ $riwayat->count() }}
                    </span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr>
                                <th class="ps-4 border-0 text-body-secondary small fw-bold">NAMA ASET</th>
                                <th class="border-0 text-body-secondary small fw-bold">TANGGAL PINJAM</th>
                                <th class="border-0 text-body-secondary small fw-bold text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($riwayat as $item)
                            <tr>
                                <td class="ps-4">
                                    {{-- Menggunakan nama_aset dan kode_barang sesuai database Bapak --}}
                                    <div class="fw-bold text-body">
                                        {{ $item->inventaris->nama_aset ?? 'Aset Tidak Ditemukan' }}
                                    </div>
                                    <div class="text-body-secondary small">
                                        Kode: {{ $item->inventaris->kode_barang ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-body small">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'disetujui')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">
                                            Belum Kembali
                                        </span>
                                    @elseif($item->status == 'selesai')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">
                                            Selesai
                                        </span>
                                    @elseif($item->status == 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 text-dark">
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-body-secondary">
                                    <div class="opacity-25 mb-3">
                                        <i class="bi bi-box-seam fs-1"></i>
                                    </div>
                                    <p class="mb-0">Mahasiswa ini belum memiliki riwayat peminjaman.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 
       Menggunakan CSS Variable bawaan Bootstrap 5 
       Otomatis berubah warna saat beralih Light/Dark Mode
    */
    .custom-card-bg {
        background-color: var(--bs-card-bg);
        border: 1px solid var(--bs-border-color) !important;
    }

    .table-hover tbody tr:hover {
        background-color: var(--bs-tertiary-bg);
    }

    /* Memastikan link breadcrumb tidak 'nyaru' di dark mode */
    .breadcrumb-item a {
        color: var(--bs-link-color);
    }

    .breadcrumb-item.active {
        color: var(--bs-secondary-color);
    }
</style>
@endsection