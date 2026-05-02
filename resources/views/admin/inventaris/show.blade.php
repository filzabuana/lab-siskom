@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        {{-- Link Kembali yang adaptif --}}
        <a href="{{ $isKatalog ? route('katalog.index') : route('admin.inventaris.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>

        {{-- Button Group: HANYA tampil jika BUKAN katalog (Admin) --}}
        @if(!$isKatalog)
            <div class="btn-group">
                <a href="{{ route('admin.inventaris.edit', $item->id) }}" class="btn btn-outline-warning btn-sm rounded-start-pill px-3">
                    <i class="bi bi-pencil me-1"></i> Edit Data
                </a>
                <button class="btn btn-outline-danger btn-sm rounded-end-pill px-3" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </div>
        @endif
    </div>

    <div class="row">
        {{-- Sisi Kiri: Foto & Informasi Utama --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5">
                        @if($item->foto_barang)
                            <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                                 class="img-fluid h-100 w-100" 
                                 alt="{{ $item->nama_aset }}"
                                 style="object-fit: cover; min-height: 250px;">
                        @else
                            <div class="bg-secondary bg-opacity-10 h-100 w-100 d-flex flex-column align-items-center justify-content-center text-secondary" style="min-height: 250px;">
                                <i class="bi bi-image fs-1 mb-2"></i>
                                <small>Tidak ada foto alat</small>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-subtle text-primary">{{ $item->kategori }}</span>
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                        <i class="bi bi-house-door me-1"></i> Bisa Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">
                                        <i class="bi bi-lock-fill me-1"></i> Hanya di Lab
                                    </span>
                                @endif
                            </div>
                            <h2 class="fw-bold mb-1 text-body">{{ $item->nama_aset }}</h2>
                            {{-- Baris Kode Barang & NUP: NUP disembunyikan jika Katalog --}}
                            <p class="text-secondary mb-4">
                                {{ $item->kode_barang }} @if(!$isKatalog) | NUP: {{ $item->nup ?? '-' }} @endif
                            </p>

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Merk / Brand</label>
                                    <span class="fw-semibold text-body">{{ $item->merk ?? 'Generic' }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Tahun Perolehan</label>
                                    <span class="fw-semibold text-body">{{ $item->tahun_perolehan }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Lokasi</label>
                                    <span class="fw-semibold text-primary"><i class="bi bi-geo-alt me-1"></i>{{ $item->ruangan }}</span>
                                </div>
                                {{-- Sumber Dana: Sembunyi jika Katalog --}}
                                @if(!$isKatalog)
                                    <div class="col-6">
                                        <label class="text-secondary small d-block">Sumber Dana</label>
                                        <span class="fw-semibold text-body">{{ $item->sumber_dana ?? '-' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deskripsi Baru (Langkah 1) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-card-text me-2 text-primary"></i>Deskripsi & Kegunaan</h5>
                    <div class="text-secondary description-content">
                            {!! Str::markdown($item->deskripsi ?? 'Belum ada deskripsi.') !!}
                        </div>

                        <style>
                            /* Styling agar list-nya terlihat rapi */
                            .description-content ul {
                                padding-left: 1.2rem;
                                margin-bottom: 1rem;
                            }
                            .description-content p {
                                margin-bottom: 0.8rem;
                            }
                        </style>
                </div>
            </div>

            {{-- Catatan Lokasi: HANYA untuk Admin --}}
            @if(!$isKatalog)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Catatan Internal Lab</h5>
                    <div class="p-3 bg-secondary bg-opacity-10 rounded-3">
                        <p class="mb-0 italic text-body small font-monospace">{{ $item->catatan_lokasi ?? 'Tidak ada catatan spesifik lokasi penyimpanan.' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sisi Kanan: Status & Stok --}}
        <div class="col-lg-4">
            {{-- Card Stok: Tampilkan angka hanya jika LOGIN --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 {{ Auth::check() ? 'bg-primary' : 'bg-secondary bg-opacity-10' }} text-white">
                <div class="card-body p-4 text-center">
                    <h6 class="{{ Auth::check() ? 'opacity-75' : 'text-secondary' }}">Jumlah Stok Tersedia</h6>
                    @auth
                        <h1 class="display-3 fw-bold mb-0 text-white">{{ $item->jumlah_stok }}</h1>
                        <small class="text-white">Unit aktif saat ini</small>
                    @else
                        <div class="py-2">
                            <i class="bi bi-lock-fill fs-2 text-secondary mb-2 d-block"></i>
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary rounded-pill fw-bold">Login untuk cek stok</a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Tombol Pinjam Khusus Mahasiswa di Katalog --}}
            @if($isKatalog && $item->tipe_peminjaman == 'Bisa Dipinjam')
                @auth
                    <button class="btn btn-success btn-lg w-100 rounded-4 mb-4 fw-bold shadow-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalPinjam{{ $item->id }}"
                            {{ $item->jumlah_stok <= 0 ? 'disabled' : '' }}>
                        <i class="bi bi-plus-circle me-2"></i> Ajukan Peminjaman
                    </button>
                    @include('peminjaman.partials.modal_pinjam')
                @else
                    {{-- Tombol Login jika belum masuk --}}
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 rounded-4 mb-4 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login untuk Meminjam
                    </a>
                @endauth
            @endif

            {{-- Kondisi Alat: Sembunyikan Detail jika belum Login --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Kondisi Alat</h6>
                    @auth
                        @if($item->kondisi == 'Baik')
                            <div class="alert alert-success border-0 mb-0 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div><strong>Kondisi Baik</strong><br><small>Alat siap digunakan.</small></div>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 mb-0 d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                <div><strong>{{ $item->kondisi }}</strong><br><small>Hubungi teknisi lab.</small></div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border mb-0 py-2 text-center">
                            <small class="text-muted"><i class="bi bi-shield-lock me-1"></i> Detail kondisi hanya untuk civitas.</small>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Widget Update: Sembunyikan jika belum Login --}}
            @auth
            <div class="list-group shadow-sm rounded-4 overflow-hidden border-0">
                <div class="list-group-item bg-transparent py-3">
                    <small class="text-secondary d-block">Terakhir diperbarui</small>
                    <span class="small text-body">{{ $item->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection