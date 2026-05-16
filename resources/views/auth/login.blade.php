@extends('layouts.modern')

@section('content')
{{-- Container utama dibuat transparan agar canvas di layout tembus --}}
<div class="relative min-h-[80vh] flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased overflow-hidden bg-transparent">
    
    <div class="max-w-5xl w-full z-20">
        {{-- Card: Menggunakan backdrop-blur agar partikel di belakang terlihat samar --}}
        <div class="bg-white/90 dark:bg-railway-card/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.08)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden flex flex-col lg:flex-row border border-slate-100 dark:border-railway-border transition-all">
            
            {{-- Sisi Kiri: Visual Branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-blue-700 to-blue-900 dark:from-blue-900/40 dark:to-railway-dark/40 p-12 flex-col justify-center items-center text-center">
                <div class="relative z-10 space-y-6">
                    <div class="bg-white/10 backdrop-blur-2xl p-5 rounded-[2rem] shadow-2xl border border-white/20 inline-block">
                        <img src="{{ asset('images/hero-lab.jpeg') }}" alt="Lab Illustration" 
                             class="rounded-2xl shadow-2xl max-h-60 w-full object-cover grayscale-[10%] dark:grayscale-0">
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-white tracking-tight uppercase mb-3 leading-none">Sistem Informasi Lab</h3>
                        <div class="w-16 h-1.5 bg-blue-400 rounded-full mx-auto mb-4"></div>
                        <p class="text-blue-100/90 font-medium leading-relaxed px-4 text-sm">
                            Layanan mandiri mahasiswa dan manajemen laboratorium dalam satu pintu.
                        </p>
                    </div>
                </div>
                
                <div class="absolute bottom-10 w-full text-center">
                    <p class="text-blue-300/40 text-[10px] font-black uppercase tracking-[0.4em]">FMIPA Universitas Tanjungpura</p>
                </div>
            </div>

            {{-- Sisi Kanan: Form Login --}}
            <div class="w-full lg:w-1/2 p-8 md:p-16 flex flex-col justify-center">
                <div class="mb-10">
                    <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter mb-2">Selamat Datang</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Masuk dengan akun institusi Anda.</p>
                </div>

                {{-- REVISI TOTAL: Tombol Google yang Rapi & Proporsional --}}
                <div class="mb-6">
                    <a href="{{ route('google.login') }}" 
                    class="group relative w-full flex items-center justify-center h-14 bg-white/10 dark:bg-white/[0.03] backdrop-blur-md border border-white/20 dark:border-railway-border rounded-2xl transition-all hover:bg-white/20 dark:hover:bg-railway-card hover:shadow-lg active:scale-[0.98]">
                        
                        {{-- Ikon: Dibuat Absolute di Kiri agar tidak mendorong teks --}}
                        <div class="absolute left-2 flex items-center justify-center w-10 h-10 bg-white rounded-xl shadow-sm group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                            </svg>
                        </div>
                        
                        {{-- Teks: Font size disesuaikan agar pas satu baris --}}
                        <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200 tracking-normal pl-6">
                            Masuk dengan Google
                        </span>
                    </a>

                    {{-- Divider yang lebih tipis agar clean --}}
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/10 dark:border-white/5"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-[#0f172a] px-4 text-[10px] uppercase tracking-[0.3em] font-medium text-slate-500">Atau</span>
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 rounded-r-xl text-emerald-700 dark:text-emerald-400 text-sm font-bold">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 px-1">Email Institusi</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 dark:text-slate-600 group-focus-within:text-blue-600 transition-colors">
                                <i class="bi bi-envelope-at-fill"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-11 pr-4 py-4 bg-slate-50/50 dark:bg-railway-dark/50 border border-slate-200 dark:border-railway-border rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all placeholder-slate-300 dark:placeholder-slate-700"
                                placeholder="nim@student.untan.ac.id">
                        </div>
                        @error('email')
                            <span class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 px-1">Password</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 dark:text-slate-600 group-focus-within:text-blue-600 transition-colors">
                                <i class="bi bi-shield-lock-fill"></i>
                            </span>
                            <input id="password" type="password" name="password" required
                                class="block w-full pl-11 pr-12 py-4 bg-slate-50/50 dark:bg-railway-dark/50 border border-slate-200 dark:border-railway-border rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all placeholder-slate-300 dark:placeholder-slate-700"
                                placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-600 transition-colors">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" id="remember_me" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:bg-railway-dark dark:border-railway-border transition-all">
                            <span class="ml-2 text-xs font-bold text-slate-500 dark:text-slate-400 group-hover:text-blue-600 transition-colors">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-black text-blue-600 dark:text-railway-accent uppercase tracking-tighter hover:underline decoration-2 underline-offset-4">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 dark:bg-railway-accent hover:bg-blue-700 text-white rounded-full font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/25 transition-all transform hover:-translate-y-1 active:scale-95">
                        Masuk <i class="bi bi-arrow-right-short ms-2 text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleButton && passwordInput) {
            toggleButton.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>

@endsection