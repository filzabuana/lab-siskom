@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.inventaris.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <div class="btn-group">
            <button class="btn btn-outline-warning btn-sm rounded-start-pill px-3">
                <i class="bi bi-pencil me-1"></i> Edit Data
            </button>
            <button class="btn btn-outline-danger btn-sm rounded-end-pill px-3">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </div>
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
                            <h2 class="fw-bold mb-1">{{ $item->nama_aset }}</h2>
                            <p class="text-secondary mb-4">{{ $item->kode_barang }} | NUP: {{ $item->nup ?? '-' }}</p>

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Merk / Brand</label>
                                    <span class="fw-semibold">{{ $item->merk ?? 'Generic' }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Tahun Perolehan</label>
                                    <span class="fw-semibold">{{ $item->tahun_perolehan }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Lokasi</label>
                                    <span class="fw-semibold text-primary"><i class="bi bi-geo-alt me-1"></i>{{ $item->ruangan }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary small d-block">Sumber Dana</label>
                                    <span class="fw-semibold">{{ $item->sumber_dana ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Catatan Lokasi & Inventaris</h5>
                    <div class="p-3 bg-secondary bg-opacity-10 rounded-3">
                        <p class="mb-0 italic">{{ $item->catatan_lokasi ?? 'Tidak ada catatan spesifik lokasi penyimpanan.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Status & Stok --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h6 class="opacity-75">Jumlah Stok Tersedia</h6>
                    <h1 class="display-3 fw-bold mb-0">{{ $item->jumlah_stok }}</h1>
                    <small>Unit aktif saat ini</small>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Kondisi Alat</h6>
                    @if($item->kondisi == 'Baik')
                        <div class="alert alert-success border-0 mb-0 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <div><strong>Kondisi Baik</strong><br><small>Alat siap digunakan.</small></div>
                        </div>
                    @elseif($item->kondisi == 'Rusak Ringan')
                        <div class="alert alert-warning border-0 mb-0 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div><strong>Rusak Ringan</strong><br><small>Perlu pengecekan/servis.</small></div>
                        </div>
                    @else
                        <div class="alert alert-danger border-0 mb-0 d-flex align-items-center">
                            <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                            <div><strong>Rusak Berat</strong><br><small>Tidak dapat dioperasikan.</small></div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Widget Cepat --}}
            <div class="list-group shadow-sm rounded-4 overflow-hidden border-0">
                <div class="list-group-item bg-transparent py-3">
                    <small class="text-secondary d-block">Terakhir diperbarui</small>
                    <span class="small">{{ $item->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection