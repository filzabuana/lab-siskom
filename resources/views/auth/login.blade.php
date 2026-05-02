@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg overflow-hidden rounded-4 bg-body">
                <div class="row g-0">
                    
                    {{-- Sisi Kiri: Visual Portal --}}
                    <div class="col-lg-6 d-none d-lg-block" style="background: linear-gradient(135deg, #0d6efd 0%, #003d99 100%); position: relative;">
                        <div class="h-100 d-flex flex-column justify-content-center align-items-center text-white p-5 text-center">
                            <img src="{{ asset('images/hero-lab.jpeg') }}" alt="Lab Illustration" class="img-fluid mb-4 rounded-3" style="max-height: 250px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));">
                            <h3 class="fw-bold">Sistem Informasi Lab</h3>
                            <p class="text-white-50">Akses layanan mandiri mahasiswa, repositori dokumen, dan manajemen laboratorium dalam satu pintu.</p>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Form Login --}}
                    <div class="col-lg-6 p-4 p-md-5 bg-body">
                        <div class="mb-4">
                            <h2 class="fw-bold text-body">Selamat Datang</h2>
                            <p class="text-body-secondary">Silakan masuk dengan akun institusi Anda.</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mb-4 border-0 bg-success bg-opacity-10 text-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email Input --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-body">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0 text-body-secondary">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" name="email" 
                                        class="form-control bg-body-tertiary border-start-0 text-body @error('email') is-invalid @enderror" 
                                        value="{{ old('email') }}" required autofocus 
                                        placeholder="nim@student.untan.ac.id">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password Input dengan Fitur Show/Hide --}}
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-body">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0 text-body-secondary">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" name="password" 
                                        class="form-control bg-body-tertiary border-x-0 text-body @error('password') is-invalid @enderror" 
                                        required autocomplete="current-password" 
                                        placeholder="••••••••">
                                    {{-- Tombol Mata --}}
                                    <button class="btn btn-outline-secondary bg-body-tertiary border-start-0 text-body-secondary px-3" 
                                            type="button" id="togglePassword" style="border-color: var(--bs-border-color);">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Remember Me & Forgot Password --}}
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input cursor-pointer" id="remember_me">
                                    <label class="form-check-label text-body-secondary small cursor-pointer" for="remember_me">
                                        Ingat saya
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small fw-medium" href="{{ route('password.request') }}">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3">
                                    Masuk <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 pt-3 border-top text-center">
                            <p class="text-body-secondary small mb-0">
                                Belum memiliki akun? Hubungi <a href="#" class="text-decoration-none fw-medium">Admin</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Khusus Toggle Password --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleButton.addEventListener('click', function() {
            // Toggle tipe input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon mata
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    });
</script>

<style>
    .form-control, .input-group-text, .btn {
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    /* Hilangkan border biru bawaan saat button diklik agar rapi */
    .btn:focus {
        box-shadow: none !important;
    }

    .form-control:focus {
        background-color: var(--bs-body-bg) !important;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .cursor-pointer { cursor: pointer; }
</style>
@endsection