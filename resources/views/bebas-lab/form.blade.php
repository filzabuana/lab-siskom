@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="feature-icon bg-primary text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h2 class="fw-bold text-body">Pengajuan Surat Bebas Lab</h2>
                <p class="text-body-secondary">Khusus mahasiswa FMIPA Universitas Tanjungpura</p>
            </div>

            <!-- Alert Success/Error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('bebas-lab.store') }}" method="POST">
                        @csrf
                        
                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold text-body">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Filza Buana Putra" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- NIM -->
                            <div class="col-md-6 mb-3">
                                <label for="nim" class="form-label fw-semibold text-body">NIM</label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}" placeholder="H10xxxxxx" required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Program Studi -->
                            <div class="col-md-6 mb-3">
                                <label for="prodi" class="form-label fw-semibold text-body">Program Studi</label>
                                <select class="form-select @error('prodi') is-invalid @enderror" id="prodi" name="prodi" required>
                                    <option value="" selected disabled>Pilih Prodi...</option>
                                    <option value="Rekayasa Sistem Komputer" {{ old('prodi') == 'Rekayasa Sistem Komputer' ? 'selected' : '' }}>Rekayasa Sistem Komputer</option>
                                    <option value="Matematika" {{ old('prodi') == 'Matematika' ? 'selected' : '' }}>Matematika</option>
                                    <option value="Sistem Informasi" {{ old('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                    <option value="Fisika" {{ old('prodi') == 'Fisika' ? 'selected' : '' }}>Fisika</option>
                                    <option value="Biologi" {{ old('prodi') == 'Biologi' ? 'selected' : '' }}>Biologi</option>
                                    <option value="Kimia" {{ old('prodi') == 'Kimia' ? 'selected' : '' }}>Kimia</option>
                                    <option value="Kelautan" {{ old('prodi') == 'Kelautan' ? 'selected' : '' }}>Kelautan</option>
                                    <option value="Statistika" {{ old('prodi') == 'Statistika' ? 'selected' : '' }}>Statistika</option>
                                </select>
                                @error('prodi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Student -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-body">Email Student Untan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary text-body-secondary border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nim@student.untan.ac.id" required>
                            </div>
                            <div class="form-text text-body-tertiary small mt-2">
                                <i class="bi bi-info-circle me-1"></i> Tautan verifikasi akan dikirimkan ke email ini.
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                <i class="bi bi-send me-2"></i> Ajukan Bebas Lab
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FAQ/Informasi -->
            <div class="mt-5 p-4 bg-body-tertiary rounded-4">
                <h5 class="fw-bold text-body mb-3"><i class="bi bi-info-square me-2"></i>Informasi Alur</h5>
                <ol class="text-body-secondary mb-0 small">
                    <li class="mb-2">Isi formulir di atas dengan data yang benar (NIM harus unik).</li>
                    <li class="mb-2">Cek kotak masuk/spam email student Anda dan klik tombol verifikasi.</li>
                    <li class="mb-2">Admin Laboratorium akan mengecek status peminjaman alat Anda.</li>
                    <li>Jika sudah bebas pinjaman, Surat Bebas Lab (PDF) akan dikirimkan via email.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection