@extends('layouts.modern')

@section('content')
{{-- Container utama dibuat transparan agar canvas di layout tembus --}}
<canvas id="starCanvas" class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none bg-slate-50 dark:bg-[#0a0f1c]"></canvas>
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
                        Masuk Sekarang <i class="bi bi-arrow-right-short ms-2 text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Hanya sisakan script toggle password karena canvas sudah di-handle modern.blade.php
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


<script>
    const canvas = document.getElementById('starCanvas');
    const ctx = canvas.getContext('2d');
    let particles = [];
    
    // Deteksi tema untuk menyesuaikan warna titik
    const isDark = document.documentElement.classList.contains('dark');

    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        init();
    }

    function init() {
        particles = [];
        // Mengurangi jumlah partikel di mobile agar tidak berat
        const density = window.innerWidth < 768 ? 20000 : 12000;
        const count = Math.floor((canvas.width * canvas.height) / density); 
        
        for (let i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 1.5 + 0.5,
                vx: (Math.random() - 0.5) * 0.3, // Sedikit lebih lambat agar elegan
                vy: (Math.random() - 0.5) * 0.3,
                opacity: Math.random(),
                blinkSpeed: Math.random() * 0.02 + 0.005
            });
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Sesuaikan warna titik berdasarkan tema
        const dotColor = document.documentElement.classList.contains('dark') ? '255, 255, 255' : '15, 23, 42';
        const lineColor = '37, 99, 235'; // Blue-600

        particles.forEach((p, i) => {
            p.x += p.vx;
            p.y += p.vy;
            p.opacity += p.blinkSpeed;
            if (p.opacity > 1 || p.opacity < 0.1) p.blinkSpeed *= -1;
            
            // Loop balik partikel jika keluar layar
            if (p.x < 0) p.x = canvas.width;
            if (p.x > canvas.width) p.x = 0;
            if (p.y < 0) p.y = canvas.height;
            if (p.y > canvas.height) p.y = 0;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${dotColor}, ${Math.abs(p.opacity) * 0.3})`;
            ctx.fill();

            for (let j = i + 1; j < particles.length; j++) {
                const p2 = particles[j];
                const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                if (dist < 130) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(${lineColor}, ${0.1 * (1 - dist / 130)})`;
                    ctx.lineWidth = 0.4;
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.stroke();
                }
            }
        });
        requestAnimationFrame(animate);
    }

    window.addEventListener('load', () => {
        resize();
        animate();
    });
    window.addEventListener('resize', resize);
</script>

@endsection