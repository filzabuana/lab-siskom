@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="mb-4">
                <!-- Hapus text-dark agar mengikuti warna tema otomatis -->
                <h2 class="fw-bold"><i class="bi bi-person-gear me-2 text-primary"></i>Pengaturan Akun</h2>
                <p class="text-body-secondary">Kelola informasi profil dan keamanan akun Anda sebagai Admin Laboratorium.</p>
            </div>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Profil berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-shield-check me-2"></i> Password Anda telah berhasil diganti.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-1">Informasi Profil</h5>
                    <p class="text-body-secondary small mb-4">Perbarui nama panggilan atau alamat email resmi Anda.</p>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-1">Keamanan Akun</h5>
                    <p class="text-body-secondary small mb-4">Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.</p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control rounded-3 @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-3 @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <!-- Ganti btn-dark ke btn-outline-primary atau custom agar terlihat di dark mode -->
                            <button type="submit" class="btn btn-outline-primary px-4 rounded-pill fw-bold">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Menggunakan variabel Bootstrap agar adaptif */
    body { 
        background-color: var(--bs-tertiary-bg); 
        font-family: 'Inter', sans-serif; 
    }
    
    .card {
        background-color: var(--bs-card-bg);
        border: 1px solid var(--bs-border-color-translucent) !important;
    }

    .form-control { 
        background-color: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color); 
        color: var(--bs-body-color);
        padding: 0.75rem 1rem; 
    }
    
    .form-control:focus { 
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); 
        border-color: #0d6efd; 
    }

    /* Memperbaiki warna input saat autofill browser di mode gelap */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--bs-body-color);
        -webkit-box-shadow: 0 0 0px 1000px var(--bs-body-bg) inset;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>
@endsection