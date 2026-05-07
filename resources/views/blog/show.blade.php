@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Artikel</li>
        </ol>
    </nav>

    <div class="row g-5">
        <article class="col-lg-8">
            <header class="mb-4">
                <h1 class="fw-bold text-body mb-3">{{ $post->title }}</h1>
                <div class="d-flex align-items-center text-secondary small">
                    <div class="d-flex align-items-center me-4">
                        <i class="bi bi-calendar3 me-2"></i> {{ $post->created_at->format('d F Y') }}
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i> Admin Lab
                    </div>
                </div>
            </header>

            <figure class="mb-5">
                <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/1200x600?text=Blog+Header' }}" 
                     class="img-fluid rounded-4 shadow-sm w-100" alt="{{ $post->title }}">
            </figure>


            <div class="toc-container mb-5 p-4 rounded-4 shadow-sm border-0" id="toc-section" style="display: none;">
    <h5 class="fw-bold mb-3 d-flex align-items-center">
        <i class="bi bi-list-ul me-2 text-primary"></i> Daftar Isi
    </h5>
    <nav id="toc-content">
        <!-- Link akan muncul otomatis di sini -->
    </nav>
</div>
            <section class="content-body text-body-secondary lh-lg" id="main-article-content" style="font-size: 1.1rem;">
    @php
        $parsedown = new \Parsedown();
        $parsedown->setSafeMode(true);
    @endphp
    
    {!! $parsedown->text($post->content) !!}
</section>

<!-- Tombol Floating khusus Mobile (hanya muncul di layar < 992px) -->
<button class="btn btn-primary d-lg-none position-fixed bottom-0 end-0 m-4 rounded-circle shadow-lg" 
        type="button" 
        data-bs-toggle="offcanvas" 
        data-bs-target="#tocOffcanvas" 
        style="z-index: 1050; width: 60px; height: 60px;">
    <i class="bi bi-list-nested fs-3"></i>
</button>

<!-- Offcanvas (Menu yang muncul dari bawah) -->
<div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="tocOffcanvas" style="height: 60vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">Daftar Isi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="toc-content-mobile">
        <!-- Isi daftar isi akan disalin otomatis ke sini oleh JS -->
    </div>
</div>

            <hr class="my-5">
            
            <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Blog
            </a>
        </article>

        <aside class="col-lg-4">
            <div class="sticky-top" style="top: 5.5rem; z-index: 10;"> 
                <!-- Card Profil Lab dengan Desain Modern -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 card-profile-lab">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-shape bg-primary-soft rounded-3 me-3">
                                <i class="bi bi-cpu text-primary fs-4"></i>
                            </div>
                            <h5 class="fw-bold m-0 text-title-sidebar">Lab Pemrograman dan Komputasi</h5>
                        </div>
                        <p class="small text-muted-sidebar mb-4">
                            Mendukung digitalisasi administrasi dan inovasi komputasi di lingkungan <strong>Untan</strong>.
                        </p>
                        <div class="d-grid">
                            <a href="/about" class="btn btn-primary-modern rounded-pill btn-sm fw-bold">
                                Profil Kami <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Layanan Terkait dengan Hover Effect -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 text-uppercase tracking-wider small text-secondary">Layanan Terkait</h6>
                        <div class="list-group list-group-flush gap-2">
                            <a href="{{ route('katalog.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 d-flex align-items-center p-3 sidebar-link">
                                <i class="bi bi-tools me-3 text-primary"></i> Peminjaman Alat
                            </a>
                            <a href="{{ route('sop.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 d-flex align-items-center p-3 sidebar-link">
                                <i class="bi bi-file-earmark-text me-3 text-success"></i> Prosedur Lab
                            </a>
                            <a href="{{ route('bebas-lab.form') }}" class="list-group-item list-group-item-action border-0 rounded-3 d-flex align-items-center p-3 sidebar-link">
                                <i class="bi bi-shield-check me-3 text-info"></i> Bebas Lab
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<style>
    .content-body img { max-width: 100%; height: auto; border-radius: 12px; margin: 20px 0; }
    .content-body h1, .content-body h2, .content-body h3 { color: #212529; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; }
    .content-body ul, .content-body ol { margin-bottom: 1.5rem; }
    .content-body code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; color: #d63384; font-size: 0.9em; }
    .content-body pre { background: #212529; color: #fff; padding: 1.5rem; border-radius: 12px; overflow-x: auto; }
   .content-body pre code {
        background: transparent;
        color: #d1d1d1 !important; /* Ubah dari inherit ke abu-abu terang */
        padding: 0;
        text-shadow: none !important;
    } 
    /* Untuk Mode Terang (Optional: agar tidak terlalu hitam pekat) */
.content-body h1, .content-body h2, .content-body h3 {
    color: #343a40; /* Abu-abu gelap, bukan hitam kaku */
}

/* KHUSUS MODE GELAP (Dark Mode) */
[data-bs-theme="dark"] .content-body h1, 
[data-bs-theme="dark"] .content-body h2, 
[data-bs-theme="dark"] .content-body h3 { 
    /* Ganti warna putih (#f8f9fa) menjadi abu-abu terang yang lebih lembut */
    color: #ced4da !important; 
    
    /* Tambahkan ini jika ingin mengurangi efek "glow" pada judul juga */
    text-shadow: none !important;
}

/* Jika Anda ingin judul yang ada garis birunya (h2) juga lebih redup */
[data-bs-theme="dark"] .content-body h2 {
    border-left: 5px solid #0a58ca; /* Biru yang sedikit lebih gelap dari biru standar */
}


/* Core Sidebar Styling */
.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1);
}

.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tracking-wider {
    letter-spacing: 0.05rem;
}

/* Tombol Modern */
.btn-primary-modern {
    background: #0d6efd;
    color: white;
    border: none;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
}

.btn-primary-modern:hover {
    background: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

/* Sidebar Links */
.sidebar-link {
    background: transparent;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    font-weight: 500;
}

.sidebar-link:hover {
    background: rgba(var(--bs-primary-rgb), 0.05) !important;
    color: var(--bs-primary) !important;
    transform: translateX(5px);
}

/* --- DARK MODE SPECIFIC --- */
[data-bs-theme="dark"] .card-profile-lab {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
}

[data-bs-theme="dark"] .text-title-sidebar {
    color: #e9ecef;
}

[data-bs-theme="dark"] .text-muted-sidebar {
    color: #adb5bd;
}

[data-bs-theme="dark"] .sidebar-link {
    color: #ced4da;
}

[data-bs-theme="dark"] .btn-primary-modern {
    background: rgba(13, 110, 253, 0.2);
    border: 1px solid rgba(13, 110, 253, 0.4);
    color: #7abaff;
}

[data-bs-theme="dark"] .btn-primary-modern:hover {
    background: #0d6efd;
    color: white;
}
</style>

<style>
    /* Content Body Styling */
    .content-body {
        color: var(--bs-body-color);
        line-height: 1.8;
    }

    /* Heading Styling yang lebih Bold & Modern */
    .content-body h1, .content-body h2, .content-body h3 {
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        color: var(--bs-emphasis-color);
    }

    .content-body h2 {
        font-size: 1.75rem;
        border-left: 5px solid #0d6efd;
        padding-left: 1rem;
    }

    /* Image Styling */
    .content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        margin: 2.5rem 0;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    /* Blockquote / Kutipan */
    .content-body blockquote {
        border-left: 4px solid #dee2e6;
        padding: 1rem 1.5rem;
        margin: 2rem 0;
        background: rgba(0,0,0,0.02);
        border-radius: 0 12px 12px 0;
        font-style: italic;
    }

    /* List Styling */
    .content-body ul, .content-body ol {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .content-body li {
        margin-bottom: 0.5rem;
    }

    /* Code & Syntax Highlighting */
    .content-body code {
        background: rgba(214, 51, 132, 0.1);
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        color: #d63384;
        font-size: 0.875em;
        font-family: 'Fira Code', monospace;
    }

    .content-body pre {
        background: #1a1a1a;
        color: #e9ecef;
        padding: 1.5rem;
        border-radius: 16px;
        overflow-x: auto;
        margin: 2rem 0;
        border: 1px solid #333;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .content-body pre code {
        background: transparent;
        color: inherit;
        padding: 0;
    }

    /* Table Styling dalam Markdown */
    .content-body table {
        width: 100%;
        margin-bottom: 2rem;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .content-body table th {
        background: #0d6efd;
        color: white;
        padding: 12px 15px;
    }

    .content-body table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    /* Dark Mode Overrides */
    [data-bs-theme="dark"] .content-body blockquote {
        background: rgba(255,255,255,0.05);
        border-left-color: #444;
    }
    
    [data-bs-theme="dark"] .content-body table td {
        border-bottom-color: #333;
    }

    [data-bs-theme="dark"] .content-body code {
        background: rgba(230, 133, 181, 0.15);
        color: #e685b5;
    }

    /* Sidebar Sticky Adjustments */
    .sticky-custom {
        top: 6rem !important;
        z-index: 1000;
    }


    .toc-container {
    background: rgba(var(--bs-primary-rgb), 0.03);
    border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
}

#toc-content ul {
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
}

#toc-content li {
    margin-bottom: 8px;
}

#toc-content a {
    text-decoration: none;
    color: var(--bs-body-color);
    font-size: 0.95rem;
    transition: all 0.2s;
    display: inline-block;
}

#toc-content a:hover {
    color: #0d6efd;
    padding-left: 5px;
}

#toc-content .toc-h3 {
    padding-left: 1.5rem;
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Animasi tombol mengambang */
.btn-primary.position-fixed {
    transition: transform 0.2s;
}
.btn-primary.position-fixed:active {
    transform: scale(0.9);
}

/* Mempercantik Offcanvas di Mobile */
.offcanvas-bottom {
    box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
}

#toc-content-mobile ul {
    list-style: none;
    padding-left: 0;
}

#toc-content-mobile a {
    text-decoration: none;
    color: var(--bs-body-color);
    display: block;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 5px;
    background: rgba(0,0,0,0.02);
}

/* Mengatur tampilan toolbar */
div.code-toolbar {
    position: relative;
}

/* Mempercantik tombol Copy */
div.code-toolbar .toolbar-item button {
    background: #0d6efd !important; /* Warna biru Bootstrap */
    color: #fff !important;
    border-radius: 8px !important;
    padding: 0.4rem 0.8rem !important;
    font-size: 0.75rem !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
    border: none !important;
    transition: all 0.2s ease;
    margin-right: 10px;
    margin-top: 10px;
}

div.code-toolbar .toolbar-item button:hover {
    background: #0b5ed7 !important;
    transform: translateY(-1px);
}

/* Menghilangkan background bawaan toolbar yang kadang mengganggu */
div.code-toolbar .toolbar {
    opacity: 1; /* Agar tombol selalu muncul, atau ganti 0 agar muncul saat hover */
}

/* Menghilangkan efek bayangan/glowing pada teks kode Prism */
.content-body pre code, 
.content-body pre span {
    text-shadow: none !important;
    -webkit-font-smoothing: antialiased;
}

/* Opsional: Jika warna teks terlalu kontras/terang, bisa dikurangi sedikit */
[data-bs-theme="dark"] .content-body pre {
    filter: saturate(0.9); /* Mengurangi sedikit kepekatan warna agar tidak menusuk mata */
}

/* KILL THE GLOW: Target semua elemen token Prism secara spesifik */
.content-body pre[class*="language-"] code,
.content-body pre[class*="language-"] span.token {
    text-shadow: none !important;
    background: none !important; /* Menghilangkan pendaran background pada token tertentu */
}

/* Pertajam font untuk tampilan Dark Mode yang lebih bersih */
[data-bs-theme="dark"] .content-body pre[class*="language-"] {
    background: #1a1a1a !important; /* Pastikan background solid, bukan gradient */
    border: 1px solid #333;
}

/* Pastikan teks biasa di dalam pre juga tidak berbayang */
.content-body pre code {
    text-shadow: none !important;
}

/* Mengatur ukuran font blok kode */
.content-body pre code {
    font-size: 0.8rem !important; /* Ukuran lebih kecil (sekitar 14px) */
    font-family: 'Fira Code', 'JetBrains Mono', 'Courier New', monospace;
    line-height: 1.5; /* Spasi antar baris agar lebih enak dibaca */
}

/* Mengatur ukuran teks pada toolbar (tombol Copy) agar ikut menyesuaikan */
div.code-toolbar .toolbar-item span {
    font-size: 0.75rem !important;
}



</style>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const content = document.getElementById('main-article-content');
    const tocContent = document.getElementById('toc-content');
    const tocContentMobile = document.getElementById('toc-content-mobile');
    const tocSection = document.getElementById('toc-section');
    
    const headings = content.querySelectorAll('h2, h3');
    
    if (headings.length > 0) {
        tocSection.style.display = 'block';
        const tocList = document.createElement('ul');
        
        headings.forEach((heading, index) => {
            const id = 'heading-' + index;
            heading.setAttribute('id', id);
            
            const li = document.createElement('li');
            li.className = heading.tagName.toLowerCase() === 'h3' ? 'toc-h3' : 'toc-h2';
            
            const link = document.createElement('a');
            link.setAttribute('href', '#' + id);
            link.textContent = heading.textContent;
            
            // Tutup offcanvas otomatis saat link diklik (khusus mobile)
            link.addEventListener('click', () => {
                const offcanvasElement = document.getElementById('tocOffcanvas');
                const instance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if(instance) instance.hide();
            });

            li.appendChild(link);
            tocList.appendChild(li);
        });
        
        // Masukkan list ke Desktop
        tocContent.appendChild(tocList.cloneNode(true));
        
        // Masukkan list ke Mobile Offcanvas (dengan event listener tetap aktif)
        tocContentMobile.appendChild(tocList);
    }
});
</script>

<!-- Prism.js Core & Autoloader (Otomatis deteksi bahasa) -->
<!-- Prism.js Core -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<!-- Autoloader (Deteksi bahasa otomatis) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<!-- Toolbar & Copy Button Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>@endsection