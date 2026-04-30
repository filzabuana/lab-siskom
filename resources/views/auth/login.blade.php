@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg overflow-hidden rounded-4">
                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-block" style="background: linear-gradient(135deg, #0d6efd 0%, #003d99 100%); position: relative;">
                        <div class="h-100 d-flex flex-column justify-content-center align-items-center text-white p-5">
                            <img src="{{ asset('images/hero-lab.jpeg') }}" alt="Admin Illustration" class="img-fluid mb-4" style="max-height: 250px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));">
                            <h3 class="fw-bold">Portal Admin</h3>
                            <p class="text-center text-white-50">Silakan masuk untuk mengelola SOP dan layanan laboratorium komputasi.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 bg-white p-4 p-md-5">
                        <div class="mb-4">
                            <h2 class="fw-bold text-dark">Selamat Datang</h2>
                            <p class="text-muted">Masukkan kredensial Anda untuk melanjutkan.</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mb-4">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input id="email" type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@untan.ac.id">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                    <input id="password" type="password" name="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="••••••••">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                                <label class="form-check-label text-muted" for="remember_me">Ingat saya di perangkat ini</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                    Masuk Sekarang
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center mt-3">
                                    {{-- <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                        Lupa password Anda?
                                    </a> --}}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection