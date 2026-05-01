<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Pemrograman & Komputasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script>
        // Set tema secepat mungkin sebelum render
        const storedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', storedTheme);
        
        function initThemeSwitch(el) {
            el.checked = (localStorage.getItem('theme') === 'dark');
        }
    </script>

    <style>
        body { 
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease;
        }
        .navbar { background-color: #1a1a1a !important; }
        .footer { background: #1a1a1a; color: rgba(255,255,255,0.6); padding: 30px 0; margin-top: 60px; }
        .brand-logo { height: 32px; width: auto; object-fit: contain; }

        /* Theme Switch Styling */
        .theme-switch-wrapper { display: flex; align-items: center; }
        .theme-switch { display: inline-block; height: 30px; position: relative; width: 60px; }
        .theme-switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            background-color: #333;
            bottom: 0; cursor: pointer; left: 0; position: absolute; right: 0; top: 0;
            border-radius: 34px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 8px; border: 1px solid #444;
            transition: none; 
        }

        .slider:before {
            background-color: #fff; bottom: 3px; content: ""; height: 22px; left: 4px;
            position: absolute; width: 22px; border-radius: 50%; z-index: 2;
            transition: none;
        }

        [data-bs-theme="dark"] .slider:before { transform: translateX(30px); }
        [data-bs-theme="dark"] .slider { background-color: #0d6efd; border-color: #0d6efd; }

        .ready .slider, .ready .slider:before {
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .slider i { color: #f1c40f; font-size: 14px; z-index: 1; }

        [data-bs-theme="light"] body { background-color: #ffffff !important; }
        [data-bs-theme="dark"] body { background-color: #121212 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
                <img src="{{ asset('logo.png') }}" alt="Logo LAB" class="brand-logo me-2">
                LAB SISKOM
            </a>

            <!-- Mobile Toggle -->
            <div class="d-flex align-items-center d-lg-none">
                <div class="theme-switch-wrapper me-2">
                    <label class="theme-switch">
                        <input type="checkbox" id="checkbox-mobile" onchange="applyTheme(this.checked)">
                        <script>initThemeSwitch(document.getElementById('checkbox-mobile'));</script>
                        <div class="slider">
                            <i class="bi bi-sun-fill"></i>
                            <i class="bi bi-moon-stars-fill"></i>
                        </div>
                    </label>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="/sop">Repository SOP</a></li>
                    
                    @auth
                        <!-- Dropdown User (Tampil hanya jika sudah Login) -->
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-outline-light btn-sm px-3 text-white border-secondary rounded-pill" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i> Edit Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Tombol Login (Tampil hanya jika belum Login) -->
                        <li class="nav-item">
                            <a class="nav-link text-white bg-primary rounded-pill px-4 ms-lg-2 fw-bold" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth

                    <!-- Desktop Switch -->
                    <li class="nav-item d-none d-lg-block ms-lg-4">
                        <div class="theme-switch-wrapper">
                            <label class="theme-switch">
                                <input type="checkbox" id="checkbox-desktop" onchange="applyTheme(this.checked)">
                                <script>initThemeSwitch(document.getElementById('checkbox-desktop'));</script>
                                <div class="slider shadow-sm">
                                    <i class="bi bi-sun-fill"></i>
                                    <i class="bi bi-moon-stars-fill"></i>
                                </div>
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">&copy; 2026 Lab Pemrograman dan Komputasi FMIPA Untan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function applyTheme(isDark) {
            const theme = isDark ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            
            const cbDesktop = document.getElementById('checkbox-desktop');
            const cbMobile = document.getElementById('checkbox-mobile');
            if (cbDesktop) cbDesktop.checked = isDark;
            if (cbMobile) cbMobile.checked = isDark;

            document.body.classList.add('ready');
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.body.classList.add('ready');
            }, 100);
        });
    </script>
</body>
</html>