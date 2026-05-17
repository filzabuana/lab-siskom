@extends('layouts.modern')

@section('content')
<div class="bg-white dark:bg-[#0f172a] min-h-screen pb-20 font-sans selection:bg-blue-500/30 transition-all overflow-visible">
    
    {{-- Progress Bar Membaca Artikel --}}
    <div id="scroll-progress" class="fixed top-0 left-0 h-1.5 bg-gradient-to-r from-blue-600 to-cyan-400 z-[10020] transition-all duration-75" style="width: 0%"></div>

    {{-- Tombol Pemicu TOC di Mobile --}}
    <button id="mobile-toc-btn" class="lg:hidden fixed bottom-6 right-6 z-[10010] w-14 h-14 bg-blue-600 text-white rounded-2xl shadow-2xl flex items-center justify-center border border-blue-400 active:scale-95 transition-all">
        <i class="bi bi-list-nested text-2xl"></i>
    </button>

    {{-- Overlay Gelap saat TOC Mobile Aktif --}}
    <div id="drawer-overlay" class="lg:hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[10000] hidden opacity-0 transition-opacity duration-300"></div>

    <div class="max-w-6xl mx-auto px-6 py-12">
        
        {{-- Breadcrumbs Navigasi --}}
        <nav class="mb-8 flex items-center space-x-2 text-[10px] font-bold uppercase tracking-[2px] text-slate-400 dark:text-slate-500 overflow-x-auto whitespace-nowrap no-scrollbar">
            <a href="/" class="hover:text-blue-600 transition-colors">Portal</a>
            <span class="opacity-30 text-lg">/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-blue-600 transition-colors">Documentation</a>
            <span class="opacity-30 text-lg">/</span>
            <span class="text-blue-600 truncate">Doc</span>
        </nav>

        {{-- Judul Artikel Utama --}}
        <header class="mb-10">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight uppercase leading-tight italic mb-4">
                {{ $post->title }}
            </h1>
            
            {{-- Meta Data: Penulis, Kategori, dan Tanggal --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-person-circle text-base text-slate-400 dark:text-slate-500"></i>
                    <span>{{ $post->user->name ?? 'Admin Lab' }}</span>
                </span>
                <span class="hidden md:inline text-slate-300 dark:text-slate-700">•</span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-folder text-base text-slate-400 dark:text-slate-500"></i>
                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2.5 py-0.5 rounded-md font-semibold text-[11px] tracking-wide uppercase">
                        {{ $post->category ?? 'General' }}
                    </span>
                </span>
                <span class="hidden md:inline text-slate-300 dark:text-slate-700">•</span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-sm text-slate-400 dark:text-slate-500"></i>
                    <span>{{ $post->created_at ? $post->created_at->format('d M Y') : '-' }}</span>
                </span>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-16 items-start overflow-visible relative">
            
            {{-- Area Konten Utama Artikel --}}
            <main class="w-full lg:w-2/3 order-1 flex-shrink-0 overflow-visible">
                <figure class="mb-12 relative overflow-hidden rounded-3xl shadow-2xl border dark:border-slate-800">
                    <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/1200x600/1e293b/ffffff?text=LAB+KOMPUTASI' }}" 
                         class="w-full h-auto object-cover"
                         alt="Cover Image">
                </figure>

                {{-- Render langsung output HTML dari Tiptap Vue Editor --}}
                <article id="rendered-markdown" class="article-markdown-engine prose prose-slate dark:prose-invert max-w-none">
                    {!! $post->content !!}
                </article>

                {{-- Banner Informasi Tambahan di Bawah Artikel (Mobile Only) --}}
                <div class="lg:hidden mt-20 bg-blue-600 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden group">
                    <i class="bi bi-cpu absolute -right-4 -bottom-4 text-7xl text-white/10 group-hover:scale-110 transition-transform duration-700"></i>
                    <div class="relative z-10">
                        <h5 class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70">Lab Komputasi</h5>
                        <p class="text-sm font-medium mb-6 italic">FMIPA Universitas Tanjungpura</p>
                        <a href="#" class="inline-block bg-white text-blue-600 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest">Profil Lab</a>
                    </div>
                </div>
            </main>

            {{-- Sidebar Navigasi / Table of Contents --}}
            <aside class="w-full lg:w-1/3 order-2 lg:sticky lg:top-10 overflow-visible z-[10005]" id="aside-container">
                <div class="space-y-8">
                    <div id="toc-box" class="bg-white dark:bg-slate-900 lg:bg-slate-50 lg:dark:bg-slate-800/40 p-8 lg:rounded-[2.5rem] border border-slate-100 dark:border-slate-800 lg:shadow-sm">
                        <div class="flex items-center justify-between mb-6 border-b dark:border-slate-700 pb-4">
                            <h4 class="text-[10px] font-black uppercase tracking-[3px] text-slate-400 flex items-center">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span> Navigasi
                            </h4>
                            <button id="close-drawer" class="lg:hidden text-slate-500 hover:text-red-500 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <nav id="toc-sidebar-list" class="flex flex-col space-y-1 no-scrollbar max-h-[60vh] overflow-y-auto"></nav>
                    </div>

                    {{-- Banner Informasi Tambahan di Sidebar (Desktop Only) --}}
                    <div class="hidden lg:block bg-blue-600 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden group">
                        <i class="bi bi-cpu absolute -right-4 -bottom-4 text-7xl text-white/10 group-hover:scale-110 transition-transform duration-700"></i>
                        <div class="relative z-10">
                            <h5 class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70">Lab Komputasi</h5>
                            <p class="text-sm font-medium mb-6 italic">FMIPA Universitas Tanjungpura</p>
                            <a href="#" class="inline-block bg-white text-blue-600 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest">Profil Lab</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const elements = {
        tocBox: document.getElementById('toc-box'),
        toggleBtn: document.getElementById('mobile-toc-btn'),
        closeBtn: document.getElementById('close-drawer'),
        overlay: document.getElementById('drawer-overlay'),
        progressBar: document.getElementById("scroll-progress"),
        article: document.getElementById('rendered-markdown'),
        tocList: document.getElementById('toc-sidebar-list'),
        aside: document.getElementById('aside-container')
    };

    // Progress Bar Reading Meter
    if (elements.progressBar) {
        window.addEventListener('scroll', () => {
            const winScroll = window.pageYOffset || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            elements.progressBar.style.width = ((winScroll / height) * 100) + "%";
        });
    }

    // Penanganan Buka/Tutup Drawer Mobile
    function openDrawer() {
        if (!elements.tocBox || !elements.overlay || window.innerWidth >= 1024) return;
        elements.overlay.classList.remove('hidden');
        if (elements.aside) elements.aside.classList.add('active');
        setTimeout(() => {
            elements.tocBox.classList.add('active');
            elements.overlay.classList.add('active');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        if (!elements.tocBox || !elements.overlay) return;
        elements.tocBox.classList.remove('active');
        elements.overlay.classList.remove('active');
        setTimeout(() => {
            if (elements.aside) elements.aside.classList.remove('active');
            elements.overlay.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    if (elements.toggleBtn) elements.toggleBtn.onclick = openDrawer;
    if (elements.closeBtn) elements.closeBtn.onclick = closeDrawer;
    if (elements.overlay) elements.overlay.onclick = closeDrawer;

    // Otomatis Generator Daftar Isi (TOC)
    if (elements.article && elements.tocList) {
        const headings = elements.article.querySelectorAll('h2, h3');
        headings.forEach((heading, index) => {
            const anchorId = 'section-' + index;
            heading.setAttribute('id', anchorId);
            
            const link = document.createElement('a');
            link.href = '#' + anchorId;
            link.textContent = heading.textContent;
            const isH3 = heading.tagName.toLowerCase() === 'h3';
            
            link.className = isH3 
                ? 'pl-6 text-[12px] text-slate-500 py-1.5 border-l border-slate-200 dark:border-slate-800 hover:text-blue-600 transition-all uppercase tracking-wider'
                : 'text-[13px] font-bold text-slate-700 dark:text-slate-300 py-2 border-l-2 border-transparent pl-4 hover:text-blue-600 transition-all tracking-tighter italic uppercase';
            
            link.onclick = (e) => {
                e.preventDefault();
                const bodyRect = document.body.getBoundingClientRect().top;
                const offsetPosition = (heading.getBoundingClientRect().top - bodyRect) - 40;

                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                if (window.innerWidth < 1024) setTimeout(closeDrawer, 450);
            };
            elements.tocList.appendChild(link);
        });
    }

    // CLEAN INJECTOR INTERNAL TAG PRE (Tanpa Div Baru)
    const codeBlocks = elements.article ? elements.article.querySelectorAll('pre') : [];
    codeBlocks.forEach((block) => {
        const codeElement = block.querySelector('code');
        if (!codeElement) return;

        // Identifikasi nama bahasa pemrograman
        let languageName = 'CODE';
        const langClass = Array.from(codeElement.classList).find(cls => cls.startsWith('language-'));
        if (langClass) {
            languageName = langClass.replace('language-', '').toUpperCase();
            const map = { 'JS': 'JAVASCRIPT', 'TS': 'TYPESCRIPT', 'XML': 'HTML', 'CPP': 'C++', 'BASH': 'BASH' };
            if (map[languageName]) languageName = map[languageName];
        }

        // Tandai kontainer pre bawaan
        block.classList.add('custom-pre-container');

        // Buat baris penampung atas (Flexbox Space-Between)
        const headerRow = document.createElement('div');
        headerRow.className = 'code-block-header-inline';

        // Buat judul bahasa
        const langBadge = document.createElement('span');
        langBadge.className = 'code-block-lang-text';
        langBadge.textContent = languageName;

        // Buat tombol copy
        const copyButton = document.createElement('button');
        copyButton.className = 'code-block-copy-btn'; 
        copyButton.type = 'button';
        copyButton.innerHTML = '<i class="bi bi-clipboard mr-1.5"></i>Copy';

        // Satukan komponen ke baris header internal
        headerRow.appendChild(langBadge);
        headerRow.appendChild(copyButton);

        // Sisipkan baris header tepat di atas block code internal tag <pre>
        block.insertBefore(headerRow, block.firstChild);

        // Event Handler Salin Kode
        copyButton.addEventListener('click', () => {
            const text = codeElement.innerText;
            const setStatus = (html) => {
                copyButton.innerHTML = html;
                setTimeout(() => copyButton.innerHTML = '<i class="bi bi-clipboard mr-1.5"></i>Copy', 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text)
                    .then(() => setStatus('<i class="bi bi-check2 text-green-400 mr-1.5"></i><span class="text-green-400">Copied!</span>'))
                    .catch(() => fallbackCopy(text, setStatus));
            } else {
                fallbackCopy(text, setStatus);
            }
        });
    });

    function fallbackCopy(text, cb) {
        try {
            const ta = document.createElement("textarea");
            ta.value = text;
            ta.style.position = "fixed"; ta.style.left = "-9999px";
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            cb(ok ? '<i class="bi bi-check2 text-green-400 mr-1.5"></i><span class="text-green-400">Copied!</span>' : '<i class="bi bi-exclamation-triangle text-red-400 mr-1.5"></i>Failed');
        } catch {
            cb('<i class="bi bi-exclamation-triangle text-red-400 mr-1.5"></i>Failed');
        }
    }

    if (typeof Prism !== 'undefined') { Prism.highlightAll(); }
});
</script>

<style>
/* Layouting Drawer Mobile */
@media (max-width: 1023px) {
    #aside-container { position: fixed !important; inset: 0; z-index: 10005; pointer-events: none; display: none; }
    #aside-container.active { display: block !important; }
    #toc-box { position: fixed; bottom: -100%; left: 0; width: 100%; background: white; z-index: 10010; transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 2rem 2rem 0 0; pointer-events: auto; box-shadow: 0 -10px 25px -5px rgba(0, 0, 0, 0.1); }
    .dark #toc-box { background-color: #0f172a; }
    #toc-box.active { bottom: 0 !important; }
    #drawer-overlay.active { display: block !important; opacity: 1 !important; }
}

/* OVERRIDE TOTAL & PENGHANCURAN ORNAMEN BAWAAN TIPTAP / PRISM */
.prose pre::before, .prose pre::after,
.prose-invert pre::before, .prose-invert pre::after,
.article-markdown-engine pre::before, .article-markdown-engine pre::after,
pre[class*="language-"]::before, pre[class*="language-"]::after,
code[class*="language-"]::before, code[class*="language-"]::after,
.code-action-button, .dots-menu-class, .code-block-header-actions, .window-dots {
    display: none !important; content: none !important; visibility: hidden !important; opacity: 0 !important; width: 0 !important; height: 0 !important;
}

/* STYLING UTAMA KONTEN UTAMA TAG PRE */
.prose pre, .prose-invert pre, pre[class*="language-"], .custom-pre-container {
    position: relative !important;
    margin: 1.75rem 0 !important; 
    padding: 0 !important; /* Nolkan padding bawaan agar header nempel ke ujung atas */
    background-color: #1e293b !important; 
    border: 1px solid #334155 !important;
    border-radius: 1rem !important; 
    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5) !important;
    display: block !important; /* Kembalikan ke block murni */
    overflow-x: auto !important; /* Aktifkan scrollbar horizontal otomatis */
}

/* Baris Header Internal (Judul & Copy Terpisah Sebaris secara Sempurna) */
.code-block-header-inline {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    background-color: #0f172a !important; /* Lebih gelap dari blok kode */
    padding: 0.6rem 1.25rem !important;
    border-bottom: 1px solid #334155 !important;
    border-radius: 1rem 1rem 0 0;
    user-select: none;
    position: sticky; /* Tetap terlihat saat di-scroll horizontal */
    left: 0;
    width: 100%;
}

/* Teks Spesifikasi Bahasa Komputasi */
.code-block-lang-text { 
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; 
    font-size: 0.75rem; 
    font-weight: 800; 
    letter-spacing: 0.08em; 
    color: #38bdf8 !important; /* Biru Cyan Terang */
    text-transform: uppercase; 
}

/* Tombol Copy Pembersih Ornamen Melayang */
.code-block-copy-btn { 
    display: flex !important; 
    align-items: center !important; 
    font-family: system-ui, -apple-system, sans-serif; 
    font-size: 0.72rem !important; 
    font-weight: 600; 
    color: #94a3b8; 
    background-color: #1e293b !important; 
    padding: 0.3rem 0.65rem !important; 
    border: 1px solid #334155 !important; 
    border-radius: 0.375rem !important; 
    cursor: pointer; 
    transition: all 0.15s ease-in-out; 
}

.code-block-copy-btn:hover { 
    color: #ffffff; 
    background-color: #334155 !important; 
    border-color: #475569 !important; 
}

/* GAYA SEGMEN BARIS KODE (Kunci Anti-Wrap & Scroll Aktif) */
.prose pre code, .prose-invert pre code {
    display: block !important; 
    padding: 1.25rem 1.5rem !important; /* Kembalikan padding baca di dalam sini */
    background-color: transparent !important; 
    font-size: 0.88rem !important; 
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
    color: #e2e8f0 !important;
    
    /* Penguncian Mutlak Sistem Scroll Horizontal */
    white-space: pre !important;        /* Memaksa kode lurus mendatar tanpa patah baris */
    word-wrap: normal !important;       /* Nonaktifkan pemisahan otomatis */
    word-break: normal !important;      /* Cegah pemotongan kata di ujung kontainer */
    overflow-x: visible !important;
    line-height: 1.65 !important; 
}

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- Injeksi CSS & JS Pendukung Komponen --}}
<link class="main-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
@endsection