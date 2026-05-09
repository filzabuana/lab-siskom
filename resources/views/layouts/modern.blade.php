<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Pemrograman & Komputasi - Siskom Untan</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi Tailwind untuk mendukung class-based dark mode
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons & Plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Inisialisasi Tema sebelum konten dimuat untuk mencegah flickering
        const storedTheme = localStorage.getItem('theme') || 'light';
        if (storedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        function initThemeSwitch(el) {
            if (el) {
                el.checked = (localStorage.getItem('theme') === 'dark');
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Slider Styling untuk mencocokkan app.blade.php */
        .slider {
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .slider:before {
            content: "";
            position: absolute;
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
        }
        input:checked + .slider:before {
            transform: translateX(30px);
        }
        input:checked + .slider {
            background-color: #2563eb; /* blue-600 */
        }
    </style>
</head>
<body class="bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 bg-[#1a1a1a] shadow-md py-3 px-4">
        <div class="container mx-auto flex items-center justify-between">
            
            <!-- Brand -->
            <a class="flex items-center gap-3 no-underline" href="/">
                <img src="{{ asset('logo.png') }}" alt="Logo LAB" class="h-10 w-auto object-contain">
                <div class="leading-none">
                    <span class="block text-white font-bold text-lg tracking-tight">LAB SISKOM</span>
                    <small class="text-white/50 text-[10px] font-light uppercase tracking-widest">Pemrograman & Komputasi</small>
                </div>
            </a>

            <!-- Mobile Actions -->
            <div class="flex items-center lg:hidden gap-3">
                <!-- Theme Switch Mobile -->
                <label class="relative inline-block w-[60px] h-[30px] cursor-pointer">
                    <input type="checkbox" id="checkbox-mobile" class="sr-only peer" onchange="applyTheme(this.checked)">
                    <script>initThemeSwitch(document.getElementById('checkbox-mobile'));</script>
                    <div class="slider absolute inset-0 bg-slate-700 rounded-full flex items-center justify-between px-2">
                        <i class="bi bi-moon-stars-fill text-[#f1c40f] text-xs"></i>
                        <i class="bi bi-sun-fill text-[#f1c40f] text-xs"></i>
                    </div>
                </label>
                
                <!-- Toggle Menu (Handled via Alpine or simple JS) -->
                <button onclick="toggleMenu()" class="text-white p-2">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>

            <!-- Desktop Menu -->
            <div id="navMenu" class="hidden lg:flex items-center flex-grow">
                <ul class="flex items-center ml-auto gap-1 list-none p-0 m-0">
                    <li><a href="/blog" class="px-4 py-2 text-white/80 hover:text-blue-400 text-sm font-medium transition-colors"><i class="bi bi-newspaper mr-2"></i>Blog</a></li>
                    <li><a href="/katalog" class="px-4 py-2 text-white/80 hover:text-blue-400 text-sm font-medium transition-colors"><i class="bi bi-search mr-2"></i>Katalog Alat</a></li>
                    <li><a href="/bebas-lab" class="px-4 py-2 text-white/80 hover:text-blue-400 text-sm font-medium transition-colors"><i class="bi bi-shield-check mr-2"></i>Bebas Lab</a></li>
                    <li><a href="/sop" class="px-4 py-2 text-white/80 hover:text-blue-400 text-sm font-medium transition-colors"><i class="bi bi-journal-text mr-2"></i>Repository SOP</a></li>

                    @auth
                        @if(Auth::user()->is_admin)
                            <li><a href="{{ route('admin.posts.index') }}" class="px-4 py-2 text-cyan-400 font-bold text-sm"><i class="bi bi-pencil-square mr-1"></i>Kelola Blog</a></li>
                            <li><a href="/admin/inventaris" class="px-4 py-2 text-yellow-400 font-bold text-sm"><i class="bi bi-boxes mr-1"></i>Inventaris</a></li>
                        @endif

                        <!-- User Dropdown Desktop -->
                        <div class="relative ml-4 group">
                            <button class="flex items-center gap-2 px-4 py-1.5 border border-slate-600 rounded-full text-white text-sm hover:bg-slate-800 transition-all">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->name }}
                            </button>
                            <!-- Dropdown content (Simple CSS hover or JS) -->
                            <div class="absolute right-0 w-48 mt-2 py-2 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 hidden group-hover:block animate-in fade-in slide-in-from-top-1">
                                <a href="/dashboard" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20"><i class="bi bi-speedometer2 mr-2"></i>Dashboard</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20"><i class="bi bi-person-gear mr-2"></i>Profil</a>
                                <hr class="my-1 border-slate-100 dark:border-slate-800">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 font-bold">
                                        <i class="bi bi-box-arrow-right mr-2"></i>Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <li><a href="{{ route('login') }}" class="ml-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-full transition-all">Login</a></li>
                    @endauth

                    <!-- Theme Switch Desktop -->
                    <li class="ml-6 border-l border-slate-700 pl-6">
                        <label class="relative inline-block w-[60px] h-[30px] cursor-pointer">
                            <input type="checkbox" id="checkbox-desktop" class="sr-only peer" onchange="applyTheme(this.checked)">
                            <script>initThemeSwitch(document.getElementById('checkbox-desktop'));</script>
                            <div class="slider absolute inset-0 bg-slate-700 rounded-full flex items-center justify-between px-2">
                                <i class="bi bi-moon-stars-fill text-[#f1c40f] text-xs"></i>
                                <i class="bi bi-sun-fill text-[#f1c40f] text-xs"></i>
                            </div>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-[#1a1a1a] border-t border-white/10 px-4 py-4 space-y-2">
            <a href="/blog" class="block text-white/80 py-2"><i class="bi bi-newspaper mr-3"></i>Blog</a>
            <a href="/katalog" class="block text-white/80 py-2"><i class="bi bi-search mr-3"></i>Katalog</a>
            <a href="/bebas-lab" class="block text-white/80 py-2"><i class="bi bi-shield-check mr-3"></i>Bebas Lab</a>
            <a href="/sop" class="block text-white/80 py-2"><i class="bi bi-journal-text mr-3"></i>Repository SOP</a>
            @auth
                <hr class="border-white/10">
                <a href="/dashboard" class="block text-white py-2 font-bold">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-400 py-2 font-bold">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block w-full py-3 bg-blue-600 text-center text-white font-bold rounded-xl mt-4">Login</a>
            @endauth
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container mx-auto pt-6 lg:pt-16 pb-16 px-4">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1a1a1a] py-10 mt-auto border-t border-white/5">
        <div class="container mx-auto px-4 text-center">
            <p class="text-white font-bold mb-2">&copy; 2026 Lab Pemrograman dan Komputasi</p>
            <p class="text-white/40 text-sm">Rekayasa Sistem Komputer - FMIPA Universitas Tanjungpura</p>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        function applyTheme(isDark) {
            const theme = isDark ? 'dark' : 'light';
            
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            localStorage.setItem('theme', theme);
            
            // Sinkronisasi checkbox desktop & mobile
            const cbDesktop = document.getElementById('checkbox-desktop');
            const cbMobile = document.getElementById('checkbox-mobile');
            if (cbDesktop) cbDesktop.checked = isDark;
            if (cbMobile) cbMobile.checked = isDark;
        }

        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // SweetAlert Konfigurasi Dark Mode
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const swalConfig = {
                background: isDark ? '#0f172a' : '#fff',
                color: isDark ? '#fff' : '#000',
                confirmButtonColor: '#2563eb',
                timer: 3500,
                timerProgressBar: true
            };

            @if(session('success'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                });
            @endif
        });
    </script>
</body>
</html>