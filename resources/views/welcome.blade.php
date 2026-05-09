@extends('layouts.modern')

@section('content')
<!-- Script Typed.js -->
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

<style>
    /* Railway-style background effects */
    .railway-grid {
        background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0);
        background-size: 40px 40px;
    }
    [data-bs-theme="light"] .railway-grid {
        background-image: radial-gradient(circle at 2px 2px, rgba(0,0,0,0.05) 1px, transparent 0);
    }
    
    /* Subtle Glow for Cards */
    .railway-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(8px);
    }
    [data-bs-theme="light"] .railway-card {
        background: rgba(255, 255, 255, 0.8);
    }
    #layanan {
    /* Sesuaikan angka 100px ini dengan tinggi navbar kamu + sedikit space */
    scroll-margin-top: 90px;
    }
    #katalog {
         scroll-margin-top: 80px;
    }
</style>
<!-- Background Star Canvas -->
<canvas id="starCanvas" class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none bg-slate-50 dark:bg-[#0a0f1c]"></canvas>
<div class="railway-grid space-y-20 py-10 px-4 sm:px-6 lg:px-8 min-h-screen">
    
   <!-- HERO SECTION -->
<section class="relative overflow-hidden">
    <!-- Perbaikan: py-12 lg:py-20 diubah menjadi pt-6 lg:pt-10 pb-12 lg:pb-16 -->
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 items-center gap-12 pt-6 lg:pt-10 pb-12 lg:pb-16">
        
        <!-- Left Content -->
        <div class="space-y-6 lg:space-y-8 text-center lg:text-left z-10">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono tracking-tighter bg-blue-500/10 text-blue-500 border border-blue-500/20">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    SYSTEMS STABLE v2.0
                </div>
                <!-- Perbaikan: text-4xl lg:text-7xl diatur agar lebih compact -->
                <h1 class="text-4xl lg:text-6xl xl:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                    Build <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">Future</span> <br>
                    in Laboratory.
                </h1>
            </div>

            <div class="text-lg lg:text-xl text-slate-600 dark:text-slate-400 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Solusi terintegrasi untuk
                <div class="mt-2">
                    <span class="font-mono text-blue-600 dark:text-blue-400 bg-blue-500/5 px-2 py-1 rounded border border-blue-500/10 inline-block">
                        <span id="typed-text"></span>
                    </span>
                </div>
                <div class="mt-2">yang efisien.</div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                <a href="#layanan" class="group relative inline-flex items-center justify-center px-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-950 font-bold rounded-xl overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl">
                    <i class="bi bi-terminal mr-2"></i> Layanan
                </a>
                <a href="/sop" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all">
                    <i class="bi bi-book-half mr-2"></i> Panduan Penggunaan
                </a>
            </div>
        </div>

        <!-- Right Content: Technical Mockup -->
        <div class="relative lg:block hidden lg:mt-0">
            <!-- Background Glow -->
            <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 to-purple-500/20 blur-3xl rounded-full"></div>
            
            <!-- ICON DEKORATIF 1: ATAS KANAN (Processor/CPU) -->
            <div class="absolute -top-6 -right-6 z-20 animate-bounce-slow">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 flex flex-col items-center gap-1">
                    <i class="bi bi-cpu text-2xl text-blue-500"></i>
                    <span class="text-[8px] font-bold font-mono text-slate-400 uppercase">Core Process</span>
                </div>
            </div>

            <!-- ICON DEKORATIF 2: BAWAH KIRI (IOT/Network Hub) -->
            <div class="absolute -bottom-6 -left-6 z-20 animate-bounce-slow-reverse">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 flex flex-col items-center gap-1">
                    <i class="bi bi-router text-2xl text-cyan-500"></i>
                    <span class="text-[8px] font-bold font-mono text-slate-400 uppercase">IOT Gateway</span>
                </div>
            </div>

            <!-- Main Mockup Container -->
            <div class="relative bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden transition-all duration-500">
                <div class="flex items-center gap-1.5 px-4 py-3 border-b border-slate-800 bg-slate-900/50">
                    <div class="w-3 h-3 rounded-full bg-red-500/20 border border-red-500/40"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500/20 border border-yellow-500/40"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500/20 border border-green-500/40"></div>
                    <span class="ml-2 text-[10px] font-mono text-slate-500 uppercase tracking-widest">lab_pemrograman.php</span>
                </div>

                <img src="{{ asset('images/hero-lab.jpeg') }}" 
                    class="w-full h-[380px] object-cover opacity-80 mix-blend-luminosity" 
                    alt="Lab Untan">
            </div>
        </div>
    </div>
</section>

<!-- FEATURED: VIRTUAL LAB HIGHLIGHT -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="/apps" class="group relative block overflow-hidden rounded-[2rem] border border-blue-500/30 bg-slate-900 shadow-2xl transition-all hover:border-blue-500/60">
            <!-- Glow Effect Background -->
            <div class="absolute -inset-x-20 -top-40 h-[500px] bg-blue-600/20 blur-[120px] transition-opacity group-hover:opacity-70"></div>
            
            <div class="relative z-10 grid lg:grid-cols-2 gap-10 items-center p-8 md:p-12">
                <div class="space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-mono font-bold tracking-widest bg-blue-500 text-white uppercase mx-auto lg:mx-0">
                        Featured App
                    </div>
                    <div class="space-y-3">
                        <h2 class="text-3xl md:text-5xl font-black text-white leading-tight">
                            Virtual Laboratory <br class="hidden sm:block">
                            <span class="text-blue-400">Environment.</span>
                        </h2>
                        <p class="text-slate-400 text-sm md:text-lg max-w-md mx-auto lg:mx-0 leading-relaxed">
                            Akses simulasi pemrograman, komputasi, dan eksperimen perangkat lunak secara virtual langsung dari browser Anda.
                        </p>
                    </div>
                    <div class="flex items-center justify-center lg:justify-start gap-3 text-blue-400 font-mono text-xs md:text-sm">
                        <span class="flex h-2 w-2 md:h-3 md:w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 md:h-3 md:w-3 bg-blue-500"></span>
                        </span>
                        READY_TO_LAUNCH_INSTANCE
                    </div>
                </div>

                <!-- Visual Element: Now visible on mobile with adjusted sizing -->
                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-40 h-40 md:w-64 md:h-64 lg:w-80 lg:h-80 flex items-center justify-center">
                        <!-- Rotating Rings -->
                        <div class="absolute inset-0 border-2 border-dashed border-blue-500/20 rounded-full animate-[spin_20s_linear_infinite]"></div>
                        <div class="absolute inset-6 md:inset-10 border border-blue-500/40 rounded-full animate-[spin_10s_linear_infinite_reverse]"></div>
                        
                        <!-- Icon Core -->
                        <div class="relative bg-blue-500 shadow-[0_0_40px_rgba(59,130,246,0.5)] w-16 h-16 md:w-24 md:h-24 rounded-2xl md:rounded-3xl flex items-center justify-center text-white text-2xl md:text-4xl group-hover:scale-110 transition-transform duration-500">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar Decoration -->
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>
        </a>
    </section>

    
   <!-- HIGHLIGHT LAYANAN -->
 <section id="layanan" class="max-w-7xl mx-auto space-y-4 md:space-y-10 px-4 md:px-0">
    <div class="flex items-center gap-4">
        <h2 class="text-[10px] md:text-sm font-mono uppercase tracking-[0.3em] text-blue-500 font-bold">Services</h2>
        <div class="h-[1px] flex-grow bg-gradient-to-r from-blue-500/20 to-transparent"></div>
    </div>

    <!-- Grid Vertikal yang Kompak di Mobile -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-1">
        
        <!-- Inventory Access -->
        <div class="railway-card group p-4 md:p-8 border border-slate-200 dark:border-slate-800 rounded-xl md:rounded-none lg:rounded-l-3xl hover:z-10 hover:border-blue-500/50 transition-all">
            <div class="flex md:block items-start gap-4 md:space-y-4">
                <div class="shrink-0 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-500">
                    <i class="bi bi-cpu-fill text-lg md:text-xl"></i>
                </div>
                <div class="flex-grow">
                    <h3 class="text-base md:text-xl font-bold text-slate-900 dark:text-white">Inventory Access</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 md:mt-2 text-[11px] md:text-sm leading-snug md:leading-relaxed">Akses katalog perangkat keras dan mikrokontroler.</p>
                    <a href="/katalog" class="inline-flex items-center mt-2 text-[10px] font-mono text-blue-500 hover:gap-2 transition-all">
                        EXPLORE <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Surat Bebas Lab -->
        <div class="railway-card group p-4 md:p-8 border border-slate-200 dark:border-slate-800 rounded-xl md:rounded-none hover:z-10 hover:border-cyan-500/50 transition-all">
            <div class="flex md:block items-start gap-4 md:space-y-4">
                <div class="shrink-0 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-500">
                    <i class="bi bi-shield-check text-lg md:text-xl"></i>
                </div>
                <div class="flex-grow">
                    <h3 class="text-base md:text-xl font-bold text-slate-900 dark:text-white">Surat Bebas Lab</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 md:mt-2 text-[11px] md:text-sm leading-snug md:leading-relaxed">Otomatisasi bebas pinjam alat untuk yudisium.</p>
                    <a href="{{ route('bebas-lab.form') }}" class="inline-flex items-center mt-2 text-[10px] font-mono text-cyan-500 hover:gap-2 transition-all">
                        APPLY <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Procedures -->
        <div class="railway-card group p-4 md:p-8 border border-slate-200 dark:border-slate-800 rounded-xl md:rounded-none lg:rounded-r-3xl hover:z-10 hover:border-emerald-500/50 transition-all">
            <div class="flex md:block items-start gap-4 md:space-y-4">
                <div class="shrink-0 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-500">
                    <i class="bi bi-journal-code text-lg md:text-xl"></i>
                </div>
                <div class="flex-grow">
                    <h3 class="text-base md:text-xl font-bold text-slate-900 dark:text-white">Procedures</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 md:mt-2 text-[11px] md:text-sm leading-snug md:leading-relaxed">Dokumentasi SOP penggunaan ruang dan alat lab.</p>
                    <a href="/sop" class="inline-flex items-center mt-2 text-[10px] font-mono text-emerald-500 hover:gap-2 transition-all">
                        READ <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

   <!-- KATALOG ALAT -->
<section id="katalog" class="max-w-7xl mx-auto px-4 md:px-0">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
        <div class="space-y-1">
            <h2 class="text-xl md:text-2xl font-bold dark:text-white flex items-center gap-3">
                <i class="bi bi-box-seam text-blue-500"></i> Hardware Assets
            </h2>
        </div>

    </div>

    <!-- Container Grid: 2 Kolom di Mobile (grid-cols-2), 4 Kolom di Desktop (lg:grid-cols-4) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
        
        @forelse($inventaris->take(4) as $item)
        <div class="railway-card rounded-xl md:rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden group bg-white dark:bg-railway-card flex flex-col transition-all duration-300">
            
            <!-- Area Gambar: Tinggi dikurangi sedikit (h-32) agar pas dalam grid 2x2 -->
            <div class="relative h-32 md:h-48 bg-slate-100 dark:bg-slate-900/50">
                @if($item->foto_barang)
                    <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                         class="w-full h-full object-cover grayscale mix-blend-luminosity opacity-80" 
                         alt="{{ $item->nama_aset }}">
                @else
                    <div class="flex items-center justify-center h-full text-slate-400 dark:text-slate-600">
                        <i class="bi bi-hash text-2xl md:text-4xl opacity-20"></i>
                    </div>
                @endif
                
                <!-- Badge Status: Ukuran teks diperkecil untuk mobile -->
                <div class="absolute top-2 left-2">
                    <span class="px-1.5 py-0.5 rounded-md text-[7px] md:text-[8px] font-mono font-bold backdrop-blur-md {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30' }}">
                        {{ strtoupper(str_replace(' ', '_', $item->tipe_peminjaman)) }}
                    </span>
                </div>
            </div>

            <!-- Konten Kartu -->
            <div class="p-3 md:p-5 flex flex-col flex-grow justify-between gap-3">
                <div class="space-y-0.5">
                    <!-- Text truncate agar tidak merusak layout grid -->
                    <h3 class="font-bold text-slate-900 dark:text-white truncate text-[11px] md:text-sm">{{ $item->nama_aset }}</h3>
                    <p class="font-mono text-[8px] md:text-[10px] text-slate-500 uppercase tracking-tighter">{{ $item->kode_barang }}</p>
                </div>
                
                <div class="pt-2 md:pt-4 border-t border-slate-100 dark:border-slate-800">
                    @auth
                        <button class="w-full py-1.5 md:py-2.5 bg-blue-500 text-white rounded-lg md:rounded-xl font-bold text-[9px] md:text-[10px] uppercase tracking-wider">Pinjam</button>
                    @else
                        <a href="{{ route('login') }}" class="w-full py-1.5 md:py-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-center rounded-lg md:rounded-xl font-bold text-[9px] md:text-[10px] uppercase tracking-wider flex items-center justify-center gap-1.5 border border-slate-200 dark:border-white/5">
                            <i class="bi bi-lock-fill text-[8px]"></i> Sign in
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        @empty
            <div class="col-span-full w-full text-center py-10 font-mono text-slate-500">STOK_EMPTY_NULL</div>
        @endforelse
    </div>
    <div class="mt-8 flex justify-center">
        <a href="{{ url('/katalog') }}" class="group flex items-center gap-3 text-[10px] md:text-xs font-mono text-slate-500 hover:text-blue-500 transition-all uppercase tracking-[0.2em] bg-slate-100 dark:bg-white/5 px-6 py-3 rounded-full border border-slate-200 dark:border-white/10 hover:border-blue-500/50 shadow-sm">
            <span>lihat_semua_alat()</span>
            <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
        </a>
    </div>
</section>

    <!-- ARTIKEL -->
<section id="blog" class="max-w-7xl mx-auto py-12 px-4 border-t border-slate-200 dark:border-slate-800">
    <div class="grid lg:grid-cols-4 gap-12">
        <div class="lg:col-span-1 space-y-4">
            <h2 class="text-2xl font-bold dark:text-white leading-tight">Engineering Updates</h2>
            <p class="text-sm text-slate-500">Catatan teknis, berita laboratorium, dan pengumuman riset terbaru.</p>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-sm font-bold text-blue-500 group">
                Browse Archive 
                <i class="bi bi-arrow-up-right ml-2 text-xs transition-transform group-hover:-translate-y-1 group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <div class="lg:col-span-3 grid sm:grid-cols-2 gap-8">
            @foreach($latestPosts->take(2) as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block space-y-4">
                {{-- Container Gambar dengan Overlay --}}
                <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400?text=LAB' }}" 
                         class="w-full h-full object-cover transition-all duration-700 
                                opacity-60 grayscale group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-105"
                         alt="{{ $post->title }}">
                    
                    {{-- Overlay gradasi halus agar teks di atasnya (jika nanti ada) tetap terbaca --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-[10px] font-mono text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <i class="bi bi-calendar3"></i>
                            {{ $post->created_at->format('Y.m.d') }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                        <span class="text-blue-500 font-bold">{{ strtoupper($post->category ?? 'General') }}</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-bold dark:text-white group-hover:text-blue-500 transition-colors leading-snug">
                            {{ $post->title }}
                        </h3>
                        
                        {{-- Preview Isi Artikel --}}
                        <p class="text-xs md:text-sm text-slate-600 dark:text-zinc-400 leading-relaxed line-clamp-2">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                    </div>

                    <div class="pt-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-blue-500 transition-colors flex items-center gap-2">
                            Read Full Report <i class="bi bi-chevron-right text-[8px]"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

   <!-- ABOUT SECTION (COMPACT & MOBILE-FIRST) -->
<section id="about" class="max-w-7xl mx-auto py-8 pb-4 md:pb-8 px-4">
    <div class="relative bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[1.5rem] md:rounded-[2.5rem] p-5 md:p-12 overflow-hidden transition-all duration-500 shadow-sm hover:shadow-md">
        
        <!-- Subtle Glow Background (Ganti Ikon CPU yang Mengganggu) -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center gap-8">
            <!-- Text Content -->
            <div class="flex-1 space-y-4 md:space-y-6">
                <!-- Status Badge (Efek Kedip yang Bapak Suka) -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 text-[9px] md:text-[10px] font-bold uppercase tracking-widest">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-500"></span>
                    </span>
                    Profil Lab
                </div>
                
                <h2 class="text-xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug">
                    Pusat Inovasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 font-black">Pemrograman</span> & Komputasi.
                </h2>
                
                <p class="text-xs md:text-sm text-slate-600 dark:text-zinc-400 leading-relaxed max-w-lg">
                    Di bawah naungan FMIPA Universitas Tanjungpura, kami mengintegrasikan riset perangkat lunak dan sistem cerdas untuk melahirkan solusi teknologi masa depan.
                </p>
                
                <div class="pt-2">
                    <a href="/about" class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:gap-3 transition-all">
                        Selengkapnya tentang visi kami
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

           <!-- Features Grid (Staggered Column Layout) -->
        <div class="w-full lg:max-w-[320px]">
            <div class="grid grid-cols-2 gap-3 items-start">
                
                <!-- Kolom Kiri (Terangkat Ke Atas) -->
                <div class="flex flex-col gap-3 -mt-3 md:-mt-6">
                    <!-- Box 1 -->
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-railway-border flex flex-col items-start gap-2 shadow-sm">
                        <i class="bi bi-code-slash text-blue-500 text-lg"></i>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-500 leading-tight">Software Dev</span>
                    </div>
                    <!-- Box 3 -->
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-railway-border flex flex-col items-start gap-2 shadow-sm">
                        <i class="bi bi-gpu-card text-blue-500 text-lg"></i>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-500 leading-tight">High Computing</span>
                    </div>
                </div>

                <!-- Kolom Kanan (Turun Ke Bawah) -->
                <div class="flex flex-col gap-3 mt-3 md:mt-6">
                    <!-- Box 2 -->
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-railway-border flex flex-col items-start gap-2 shadow-sm">
                        <i class="bi bi-cpu text-blue-500 text-lg"></i>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-500 leading-tight">System Embedded</span>
                    </div>
                    <!-- Box 4 -->
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-railway-border flex flex-col items-start gap-2 shadow-sm">
                        <i class="bi bi-shield-check text-blue-500 text-lg"></i>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-500 leading-tight">Research Lab</span>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</section>

{{-- Section Logo (Instansi & Tech Stack) --}}
<section class="py-16 py-8 md:py-12 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto flex flex-col items-center gap-16">
            
            {{-- Row 1: Instansi (Main Focus - Larger) --}}
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-20 grayscale opacity-70 hover:opacity-100 transition-all duration-700">
                <img src="{{ asset('images/untan.svg') }}" alt="UNTAN" class="h-14 md:h-20 w-auto dark:invert-0 invert">
                <img src="{{ asset('images/diktisaintek.svg') }}" alt="dikti" class="h-14 md:h-20 w-auto">
                <img src="{{ asset('images/tut.svg') }}" alt="Tut Wuri Handayani" class="h-14 md:h-20 w-auto">
            </div>

            {{-- Decorative Divider Line --}}
            <div class="w-24 h-[1px] bg-slate-200 dark:bg-slate-800"></div>

            {{-- Row 2: Tech Stack (Supporting - Original Style) --}}
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 grayscale opacity-40 hover:opacity-100 transition-all duration-700">
                
                {{-- Laravel --}}
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-auto text-white dark:invert-0 invert" viewBox="0 0 50 52" xmlns="http://www.w3.org/2000/svg">
                        <path d="M49.626 11.564a.809.809 0 0 1 .028.209v10.972a.801.801 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 0 1 0 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216l17.62-10.144zM1.602 7.719v31.068L19.22 48.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965 9.654 1.602 7.72zm8.81-5.994L2.405 6.334l8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764l4.645-2.674V7.719l-3.363 1.936-4.646 2.675v20.096l3.364-1.937zM39.243 7.164l-8.006 4.609 8.006 4.609 8.005-4.61-8.005-4.608zm-.801 10.605l-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937v-9.124zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833 7.993 4.524z" fill="currentColor" fill-rule="evenodd"/>
                    </svg>
                    <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase dark:invert-0 invert">Laravel</span>
                </div>

                {{-- Vue --}}
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-auto dark:invert-0 invert " viewBox="0 0 261.76 226.69" xmlns="http://www.w3.org/2000/svg">
                        <g transform="matrix(1.3333 0 0 -1.3333 -76.311 313.34)">
                            <g transform="translate(178.06 235.01)">
                                <path d="m0 0-22.669-39.264-22.669 39.264h-75.491l98.16-170.02 98.16 170.02z" fill="#FFFFFF"/>
                            </g>
                            <g transform="translate(178.06 235.01)">
                                <path d="m0 0-22.669-39.264-22.669 39.264h-36.227l58.896-102.01 58.896 102.01z" fill="#475569"/>
                            </g>
                        </g>
                    </svg>
                    <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase dark:invert-0 invert">Vue.js</span>
                </div>

                {{-- Tailwind  --}}
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-auto text-[#38BDF8] dark:invert-0 invert" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8 1.123.28 1.926 1.096 2.814 1.998C14.457 11.263 15.823 12.65 19.2 12.65c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-1.123-.28-1.926-1.096-2.814-1.998-1.442-1.464-2.808-2.852-6.185-2.852zm-7.2 7.85c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8 1.123.28 1.926 1.096 2.814 1.998 1.442 1.464 2.808 2.852 6.185 2.852 3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-1.123-.28-1.926-1.096-2.814-1.998-1.442-1.464-2.808-2.852-6.185-2.852z"/>
                    </svg>
                    <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase dark:invert-0 invert">Tailwind</span>
                </div>

                {{-- Vite --}}
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-auto dark:invert-0 invert" viewBox="0 0 256 257" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                        <defs>
                            <linearGradient x1="12.052%" y1="18.591%" x2="100%" y2="100%" id="viteGradient">
                                <stop stop-color="#41D1FF" offset="0%"/>
                                <stop stop-color="#BD34FE" offset="100%"/>
                            </linearGradient>
                            <linearGradient x1="19.502%" y1="53.161%" x2="65.304%" y2="58.217%" id="viteBolt">
                                <stop stop-color="#FFEA83" offset="0%"/>
                                <stop stop-color="#FFDD35" offset="100%"/>
                            </linearGradient>
                        </defs>
                        <path d="M255.859 37.818l-90.871 213.125a12.8 12.8 0 01-23.702.046L51.059 37.82a12.8 12.8 0 0118.583-15.704l50.324 29.356a12.8 12.8 0 0015.748-1.226L185.908 4.75a12.8 12.8 0 0121.225 11.233l-5.65 31.831a12.8 12.8 0 0010.59 14.862l32.227 5.176a12.8 12.8 0 0111.56 10.166z" fill="url(#viteGradient)"/>
                        <path d="M152.074 7.53L115.55 77.53a6.4 6.4 0 005.666 9.356h24.542a6.4 6.4 0 015.526 9.626L102.53 175.5a6.4 6.4 0 0010.556 7.426l82.124-105a6.4 6.4 0 00-5.042-10.344h-24.542a6.4 6.4 0 01-5.526-9.626l47.412-90.86a6.4 6.4 0 00-10.438-7.566z" fill="url(#viteBolt)"/>
                    </svg>
                    <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase dark:invert-0 invert">Vite</span>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Typed !== 'undefined') {
            new Typed('#typed-text', {
                strings: [
                    'layanan administrasi',
                    'manajemen inventaris',
                    'panduan praktikum',
                    'lab virtual',
                    'monitoring riset',
                    'arsip digital'
                ],
                typeSpeed: 30, // Sedikit diperlambat agar lebih mudah dibaca
                backSpeed: 30,
                backDelay: 2000,
                loop: true,
                cursorChar: '_',
            });
        }
    });
</script>

<script>
        const canvas = document.getElementById('starCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        
        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            init();
        }

        function init() {
            particles = [];
            const count = Math.floor((canvas.width * canvas.height) / 12000); 
            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * 1.5 + 0.5,
                    vx: (Math.random() - 0.5) * 0.4,
                    vy: (Math.random() - 0.5) * 0.4,
                    opacity: Math.random(),
                    blinkSpeed: Math.random() * 0.02 + 0.005
                });
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach((p, i) => {
                p.x += p.vx;
                p.y += p.vy;
                p.opacity += p.blinkSpeed;
                if (p.opacity > 1 || p.opacity < 0.1) p.blinkSpeed *= -1;
                if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
                if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${Math.abs(p.opacity) * 0.5})`;
                ctx.fill();

                for (let j = i + 1; j < particles.length; j++) {
                    const p2 = particles[j];
                    const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                    if (dist < 150) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(37, 99, 235, ${0.15 * (1 - dist / 150)})`;
                        ctx.lineWidth = 0.5;
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