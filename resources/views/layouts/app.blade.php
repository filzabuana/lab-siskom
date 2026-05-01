<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Pemrograman & Komputasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #fcfcfc; 
            font-family: 'Inter', sans-serif;
        }
        .navbar { 
            background-color: #1a1a1a !important; 
        }
        .footer { 
            background: #1a1a1a; 
            color: rgba(255,255,255,0.6); 
            padding: 30px 0; 
            margin-top: 60px; 
        }
        .carousel-item img {
            height: 400px; 
            object-fit: cover; 
            border-radius: 15px;
        }
        /* Custom Dropdown Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.75rem;
        }
        .dropdown-item {
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        .brand-logo {
        height: 32px; 
        width: auto; /* Agar rasio gambar tetap terjaga */
        object-fit: contain;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
    <img src="{{ asset('logo.png') }}" alt="Logo LAB SISKOM" class="brand-logo me-2">
    LAB SISKOM
</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('sop*') ? 'active' : '' }}" href="/sop">Repository SOP</a>
                    </li>
                    
                    @auth
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-outline-light btn-sm px-3 text-white border-secondary rounded-pill" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-2 animate slideIn">
                                <li>
                                    <a class="dropdown-item" href="/dashboard">
                                        <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-gear me-2 text-info"></i> Edit Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white bg-primary rounded-pill px-4 ms-lg-2 mt-2 mt-lg-0 d-inline-block fw-bold" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-2 mt-lg-4">
    @yield('content')
</div>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-1">&copy; 2026 Lab Pemrograman dan Komputasi FMIPA Untan</p>
            {{-- <small class="text-white-50">Sistem Informasi Manajemen Laboratorium v1.0</small> --}}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({ 
            startOnLoad: true,
            theme: 'default',
            securityLevel: 'loose',
        });
    </script>
</body>
</html>