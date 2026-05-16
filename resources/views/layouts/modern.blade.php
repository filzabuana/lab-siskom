<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Pemrograman & Komputasi - Siskom Untan</title>
    
    <!-- Vite: Menggunakan Tailwind Lokal Project -->
    @vite(['resources/css/app.css', 'resources/js/app-blade.js'])

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   

    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'dark';
            if (theme === 'dark') document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        })();
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-blur { backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
        
        /* Toggle Switch Styling */
        .slider { transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; justify-content: space-between; padding: 0 5px; }
        .slider:before {
            content: ""; position: absolute; height: 16px; width: 16px; left: 3px; bottom: 2px;
            background-color: white; border-radius: 50%; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 10;
        }
        input:checked + .slider:before { transform: translateX(20px); }
        input:checked + .slider { background-color: #3b82f6; border-color: #3b82f6; }
        
        /* Navbar Link Animation */
        .nav-link { position: relative; }
        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
            background-color: #3b82f6; transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }
        [v-cloak] {
            display: none !important;
        }

        /* CSS untuk "menjinakkan" SweetAlert2 agar sesuai tema Bapak */
.swal2-popup {
    padding: 2rem !important;
    font-family: inherit !important;
}

.swal2-title {
    padding-top: 1rem !important;
}

/* Mengubah bentuk icon agar tidak terlalu kaku */
.swal2-icon {
    border-width: 3px !important;
    transform: scale(0.8);
}

/* Dark mode support paksa jika auto-detect gagal */
.dark .swal2-popup {
    background: #1e293b !important; /* railway-card color */
    color: white !important;
}

.dark input::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(100%) contrast(100%);
    cursor: pointer;
}


.dark input::-webkit-inner-spin-button,
.dark input::-webkit-outer-spin-button {
    filter: invert(1);
}

.dark input:focus::-webkit-calendar-picker-indicator {
    filter: invert(42%) sepia(93%) saturate(1352%) hue-rotate(209deg) brightness(92%) contrast(101%);
}

/* CSS Transisi Modal Vue */
    .fade-enter-active, .fade-leave-active { 
        transition: opacity 0.3s ease; 
    }
    .fade-enter-from, .fade-leave-to { 
        opacity: 0; 
    }

    .zoom-enter-active { 
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); 
    }
    .zoom-leave-active { 
        transition: all 0.2s ease-in; 
    }
    .zoom-enter-from, .zoom-leave-to { 
        opacity: 0; 
        transform: scale(0.95) translateY(10px); 
    }
    </style>
</head>
<body  class="bg-white dark:bg-railway-dark text-slate-900 dark:text-slate-100 transition-colors duration-300 min-h-screen flex flex-col">
@if(session()->has('impersonate'))
    <div class="sticky top-0 z-[9999] bg-gradient-to-r from-amber-600 via-orange-500 to-amber-600 shadow-lg">
        <div class="max-w-7xl mx-auto py-2 px-4 flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
                <div class="animate-pulse bg-white/20 p-1 rounded-full">
                    <i class="bi bi-incognito text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">Mode Impersonasi Aktif</span>
                    <span class="text-[9px] font-medium opacity-90">Melihat sistem sebagai: <strong>{{ Auth::user()->name }} ({{ Auth::user()->role }})</strong></span>
                </div>
            </div>
            
            <a href="{{ route('admin.stop-impersonate') }}" 
               class="bg-white/10 hover:bg-white text-white hover:text-amber-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/20 transition-all active:scale-95 flex items-center gap-2">
                <i class="bi bi-door-open-fill"></i> Keluar Mode Simulasi
            </a>
        </div>
    </div>
    
    {{-- Efek Border di sekeliling layar agar makin jelas --}}
    <div class="fixed inset-0 border-[6px] border-amber-500/30 pointer-events-none z-[9998]"></div>
@endif


<!-- NAVBAR -->
<nav id="mainNavbar" class="sticky top-0 z-50 bg-white/90 dark:bg-railway-dark/90 border-b border-slate-200 dark:border-railway-border nav-blur py-3 px-4 transition-colors duration-300">
    <div class="container mx-auto flex items-center">
        
        <!-- LEFT: Brand -->
        <div class="flex-1 flex justify-start">
            <a class="flex items-center gap-2 sm:gap-3 no-underline group" href="/">
                <div class="p-1.5 bg-slate-50 dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-lg group-hover:border-blue-500 transition-all duration-300 shadow-sm">
                    <img src="{{ asset('logo.png') }}" 
                        alt="Logo" 
                        class="h-6 w-auto sm:h-7 transition-all duration-300 invert dark:invert-0">
                </div>
                <div class="leading-none">
                    <span class="block font-bold tracking-tight text-slate-900 dark:text-white uppercase text-[13px] sm:text-lg">Lab Siskom</span>
                    <small class="text-slate-500 dark:text-zinc-500 text-[8px] sm:text-[9px] font-semibold uppercase tracking-[0.2em]">Pemrograman dan Komputasi</small>
                </div>
            </a>
        </div>

        <!-- CENTER: Menu -->
        <div class="hidden lg:flex items-center justify-center">
            <ul class="flex items-center gap-1 list-none p-0 m-0 text-sm font-medium">
                <li><a href="/blog" class="nav-link px-4 py-2 text-slate-600 dark:text-zinc-400 hover:text-blue-500 dark:hover:text-white transition-colors">Blog</a></li>
                <li><a href="/katalog" class="nav-link px-4 py-2 text-slate-600 dark:text-zinc-400 hover:text-blue-500 dark:hover:text-white transition-colors">Katalog Alat</a></li>
                <li><a href="/bebas-lab" class="nav-link px-4 py-2 text-slate-600 dark:text-zinc-400 hover:text-blue-500 dark:hover:text-white transition-colors">Bebas Lab</a></li>
                <li><a href="/sop" class="nav-link px-4 py-2 text-slate-600 dark:text-zinc-400 hover:text-blue-500 dark:hover:text-white transition-colors">SOP</a></li>
            </ul>
        </div>

        <!-- RIGHT: Auth & Toggle -->
        <div class="flex-1 flex items-center justify-end gap-3">
            <div class="hidden lg:flex items-center">
                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-full group-hover:border-blue-500 transition-all cursor-pointer shadow-sm">
                            <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-[10px] text-white font-bold shadow-inner">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-slate-700 dark:text-zinc-200 text-sm font-medium">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        
                        <div class="absolute right-0 w-52 pt-2 hidden group-hover:block animate-in fade-in zoom-in-95 duration-150 z-[60]">
                            <div class="bg-white dark:bg-railway-card rounded-xl shadow-2xl border border-slate-200 dark:border-railway-border overflow-hidden text-sm">
                                <div class="px-4 py-3 bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-railway-border">
                                    <p class="text-slate-500 dark:text-zinc-500 text-[10px] uppercase tracking-wider mb-0.5">Signed in as</p>
                                    <p class="font-bold truncate text-slate-900 dark:text-white text-xs">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-slate-700 dark:text-slate-200"><i class="bi bi-grid-1x2 text-blue-500"></i> Dashboard</a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-slate-700 dark:text-slate-200"><i class="bi bi-person-gear text-blue-500"></i> Profil</a>
                                <hr class="border-slate-100 dark:border-railway-border m-0">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 font-bold transition-colors">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-bold text-sm rounded-lg transition-all shadow-md shadow-blue-500/20 active:scale-95">Sign In</a>
                @endauth
            </div>

            <div class="h-6 w-[1px] bg-slate-200 dark:bg-railway-border hidden lg:block mx-1"></div>

            <label class="relative inline-block w-[44px] h-[22px] cursor-pointer">
                <input type="checkbox" id="checkbox-theme" class="sr-only peer">
                <div class="slider absolute inset-0 bg-slate-100 dark:bg-railway-card rounded-full border border-slate-200 dark:border-railway-border transition-all">
                    <i class="bi bi-moon-stars-fill text-[10px] text-slate-400 dark:text-zinc-500 ml-1"></i>
                    <i class="bi bi-sun-fill text-[10px] text-yellow-500 opacity-0 dark:opacity-100 mr-1"></i>
                </div>
            </label>

            <button id="menuButton" onclick="toggleMenu()" class="lg:hidden text-slate-600 dark:text-white p-2 hover:bg-slate-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                <i class="bi bi-list text-2xl"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden lg:hidden bg-white dark:bg-railway-dark border-t border-slate-200 dark:border-railway-border px-4 py-6 space-y-4 nav-blur shadow-xl transition-all duration-300">
        <div class="grid gap-2">
            <a href="/blog" class="px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 font-medium flex items-center gap-3 transition-colors text-slate-700 dark:text-zinc-200">
                <i class="bi bi-journal-text text-blue-500"></i> Blog
            </a>
            <a href="/katalog" class="px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 font-medium flex items-center gap-3 transition-colors text-slate-700 dark:text-zinc-200">
                <i class="bi bi-box-seam text-blue-500"></i> Katalog Alat
            </a>
            <a href="/bebas-lab" class="px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 font-medium flex items-center gap-3 transition-colors text-slate-700 dark:text-zinc-200">
                <i class="bi bi-shield-check text-blue-500"></i> Bebas Lab
            </a>
            <a href="/sop" class="px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 font-medium flex items-center gap-3 transition-colors text-slate-700 dark:text-zinc-200">
                <i class="bi bi-file-earmark-ruled text-blue-500"></i> SOP
            </a>
        </div>
        <hr class="border-slate-100 dark:border-railway-border">
        @auth
            <div class="px-2 py-2">
                <p class="text-[10px] text-slate-500 dark:text-zinc-500 uppercase tracking-widest mb-3 px-2">Account Management</p>
                <div class="grid gap-1">
                    <a href="/dashboard" class="px-4 py-3 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 text-blue-500 font-bold flex items-center gap-3"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-500 font-bold flex items-center gap-3 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="block w-full py-3 bg-blue-500 text-center text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-transform">Sign In</a>
        @endauth
    </div>
</nav>
@if(Route::is('welcome') || Route::is('about') || Route::is('sop.index') || Route::is('bebas-lab.*') || Route::is('login')) 
<canvas id="starCanvas" class="fixed inset-0 -z-10 pointer-events-none bg-transparent"></canvas>

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
                size: Math.random() * 1.8+ 0.6,
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
            ctx.fillStyle = `rgba(${dotColor}, ${Math.abs(p.opacity) * 0.4})`;
            ctx.fill();

            for (let j = i + 1; j < particles.length; j++) {
                const p2 = particles[j];
                const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                if (dist < 130) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(${lineColor}, ${0.18 * (1 - dist / 130)})`;
                    ctx.lineWidth = 0.6;
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


@endif
    
    <div id="app-site" class="min-h-screen flex flex-col bg-transparent">
    <main class="container mx-auto pt-4 md:pt-6 pb-10 px-4 flex-grow">
        @yield('content')
    </main>
    </div>

    <footer class="bg-slate-50 dark:bg-railway-dark py-12 border-t border-slate-200 dark:border-railway-border mt-auto">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6 text-sm">
            <div class="text-center md:text-left">
                <p class="font-bold dark:text-white uppercase tracking-tight">Lab Pemrograman dan Komputasi</p>
                <p class="text-slate-500">FMIPA Universitas Tanjungpura &copy; 2026</p>
            </div>
            <div class="flex gap-6 text-slate-400">
                <a href="#" class="hover:text-blue-500 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-blue-500 transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggle = document.getElementById('checkbox-theme');
        const html = document.documentElement;
        const mobileMenu = document.getElementById('mobileMenu');
        const menuButton = document.getElementById('menuButton');

        const currentTheme = localStorage.getItem('theme') || 'dark';
        themeToggle.checked = (currentTheme === 'dark');

        // 1. Fungsi ini SEHARUSNYA cuma buat setup warna default Swal, bukan trigger api fire
        function getSwalConfig() {
            const isDark = document.documentElement.classList.contains('dark');
            return { 
                background: isDark ? '#1e293b' : '#fff', // Pakai railway-card Bapak
                color: isDark ? '#fff' : '#000', 
            };
        }

        themeToggle.addEventListener('change', function() {
            const isDark = this.checked;
            html.classList.toggle('dark', isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            // Saat ganti tema, tidak perlu panggil Swal.fire lagi di sini
        });

        // 2. Picu SweetAlert HANYA SEKALI saat halaman dimuat (DOMContentLoaded)
        @if(session('success'))
            Swal.fire({ 
                ...getSwalConfig(), 
                icon: 'success', 
                title: 'SYSTEM_STABLE', 
                text: "{{ session('success') }}", 
                timer: 3000, 
                showConfirmButton: false 
            });
        @endif

        @if(session('error'))
            Swal.fire({ 
                ...getSwalConfig(), 
                icon: 'error', 
                title: 'ERROR_DETECTED', 
                text: "{{ session('error') }}" 
            });
        @endif

        // Fitur Click Outside
        window.addEventListener('click', (e) => {
            if (!mobileMenu.classList.contains('hidden')) {
                if (!mobileMenu.contains(e.target) && !menuButton.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            }
        });
    });

    function toggleMenu() { 
        document.getElementById('mobileMenu').classList.toggle('hidden'); 
    }
</script>


</body>
</html>