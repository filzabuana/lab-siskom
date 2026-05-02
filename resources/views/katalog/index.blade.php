@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Katalog Alat Laboratorium</h2>
            <p class="text-secondary">Daftar aset tersedia di Lab Pemrograman & Komputasi - Untan</p>
            <hr class="short-hr mx-auto" style="width: 50px; border: 2px solid #0d6efd; border-radius: 5px;">
        </div>
    </div>

    {{-- Grid Katalog --}}
    <div class="row g-4">
        @forelse($inventaris as $item)
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden v-card-hover">
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px; overflow: hidden;">
                        @if($item->foto_barang)
                            {{-- Tambahkan 'storage/' sebelum 'inventaris/' --}}
                            <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                                alt="{{ $item->nama_aset }}" 
                                class="img-fluid w-100 h-100" 
                                style="object-fit: cover;"
                                onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=File+Tidak+Ditemukan';">
                        @else
                            <div class="text-center">
                                <i class="bi bi-image text-secondary opacity-25" style="font-size: 3rem;"></i>
                                <small class="d-block text-muted" style="font-size: 0.6rem;">No Image</small>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <h6 class="fw-bold mb-1 text-truncate">{{ $item->nama_aset }}</h6>
                        <small class="text-muted d-block mb-3 small">{{ $item->kode_barang }}</small>

                        {{-- Badge Status Akses --}}
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
                            {{-- Jika Mahasiswa Login --}}
                            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-secondary fw-bold">Stok: {{ $item->jumlah_stok }}</small>
                                </div>
                                <button class="btn btn-primary w-100 rounded-pill fw-bold" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPinjam{{ $item->id }}"
                                        {{ $item->jumlah_stok <= 0 ? 'disabled' : '' }}>
                                    Pinjam Alat
                                </button>
                            @else
                                <button class="btn btn-outline-secondary w-100 rounded-pill fw-bold disabled" style="cursor: not-allowed;">
                                    Hanya Gunakan di Lab
                                </button>
                                <small class="text-muted d-block text-center mt-1" style="font-size: 0.7rem;">
                                    Hubungi PLP untuk izin penggunaan
                                </small>
                            @endif
                        @else
                            {{-- Jika Belum Login (Publik) --}}
                            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                {{-- Hanya tampilkan ajakan login untuk alat yang memang BOLEH dipinjam --}}
                                <div class="alert alert-light py-2 px-3 small rounded-3 mb-0 border shadow-sm text-center">
                                    <i class="bi bi-info-circle me-1 text-primary"></i> 
                                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login</a> untuk meminjam.
                                </div>
                            @else
                                {{-- Untuk alat 'Hanya di Lab', cukup beri keterangan tanpa link login --}}
                                <div class="alert alert-secondary py-2 px-3 small rounded-3 mb-0 border-0 text-center opacity-75">
                                    <i class="bi bi-door-open me-1"></i> 
                                    Tersedia untuk penggunaan di Lab
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Load Modal (Hanya jika user login dan alat bisa dipinjam) --}}
            @auth
                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                    @include('peminjaman.partials.modal_pinjam')
                @endif
            @endauth

        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-box-seam text-secondary opacity-25" style="font-size: 4rem;"></i>
                <p class="text-secondary mt-3">Maaf, saat ini tidak ada alat yang tersedia di katalog.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Sedikit sentuhan biar card terasa interaktif */
    .v-card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .v-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection