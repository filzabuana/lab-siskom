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
</style>

<div class="railway-grid space-y-20 py-10 px-4 sm:px-6 lg:px-8 min-h-screen">
    
    <!-- HERO SECTION -->
    <section class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 items-center gap-12 py-12 lg:py-20">
            
            <!-- Left Content -->
            <div class="space-y-8 text-center lg:text-left z-10">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono tracking-tighter bg-blue-500/10 text-blue-500 border border-blue-500/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        SYSTEMS STABLE v2.0
                    </div>
                    <h1 class="text-4xl lg:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                        Build <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">Future</span> <br>
                        in Laboratory.
                    </h1>
                </div>

                <div class="text-xl text-slate-600 dark:text-slate-400 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Solusi terintegrasi untuk 
                    <span class="font-mono text-blue-600 dark:text-blue-400 bg-blue-500/5 px-2 py-0.5 rounded border border-blue-500/10">
                        <span id="typed-text"></span>
                    </span>
                    yang efisien bagi mahasiswa FMIPA UNTAN.
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#layanan" class="group relative inline-flex items-center justify-center px-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-950 font-bold rounded-xl overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl">
                        <i class="bi bi-terminal mr-2"></i> Deploy Request
                    </a>
                    <a href="/sop" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all">
                        Documentation
                    </a>
                </div>
            </div>

            <!-- Right Content: Technical Mockup -->
            <div class="relative lg:block hidden">
                <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 to-purple-500/20 blur-3xl rounded-full"></div>
                <div class="relative bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="flex items-center gap-1.5 px-4 py-3 border-b border-slate-800 bg-slate-900/50">
                        <div class="w-3 h-3 rounded-full bg-red-500/20 border border-red-500/40"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/20 border border-yellow-500/40"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/20 border border-green-500/40"></div>
                        <span class="ml-2 text-[10px] font-mono text-slate-500 uppercase tracking-widest">untan_lab_monitor.exe</span>
                    </div>
                    <img src="{{ asset('images/hero-lab.jpeg') }}" class="w-full h-[400px] object-cover opacity-80 mix-blend-luminosity hover:mix-blend-normal transition-all duration-700" alt="Lab Untan">
                </div>
            </div>
        </div>
    </section>

    <!-- HIGHLIGHT LAYANAN (Railway-style Tiles) -->
    <section id="layanan" class="max-w-7xl mx-auto space-y-10">
        <div class="flex items-center gap-4">
            <h2 class="text-sm font-mono uppercase tracking-[0.3em] text-blue-500 font-bold">Services</h2>
            <div class="h-[1px] flex-grow bg-gradient-to-r from-blue-500/20 to-transparent"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-1">
            <!-- Peminjaman Alat -->
            <div class="railway-card group p-8 border border-slate-200 dark:border-slate-800 lg:rounded-l-3xl hover:z-10 hover:border-blue-500/50">
                <div class="space-y-4">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-500">
                        <i class="bi bi-cpu-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Inventory Access</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm leading-relaxed">Akses penuh ke katalog perangkat keras, sensor, dan kit mikrokontroler.</p>
                    </div>
                    <a href="#katalog" class="inline-flex items-center text-xs font-mono text-blue-500 hover:gap-2 transition-all">
                        EXPLORE_RESOURCES <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Bebas Lab -->
            <div class="railway-card group p-8 border border-slate-200 dark:border-slate-800 hover:z-10 hover:border-cyan-500/50">
                <div class="space-y-4">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-500">
                        <i class="bi bi-shield-check text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Lab Clearance</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm leading-relaxed">Otomatisasi surat keterangan bebas pinjam alat untuk syarat kelulusan.</p>
                    </div>
                    <a href="{{ route('bebas-lab.form') }}" class="inline-flex items-center text-xs font-mono text-cyan-500 hover:gap-2 transition-all">
                        START_APPLICATION <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- SOP -->
            <div class="railway-card group p-8 border border-slate-200 dark:border-slate-800 lg:rounded-r-3xl hover:z-10 hover:border-emerald-500/50">
                <div class="space-y-4">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-500">
                        <i class="bi bi-journal-code text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Procedures</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm leading-relaxed">Dokumentasi standar operasional prosedur penggunaan ruang dan alat.</p>
                    </div>
                    <a href="/sop" class="inline-flex items-center text-xs font-mono text-emerald-500 hover:gap-2 transition-all">
                        READ_DOCS <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- KATALOG ALAT (Modern List Style) -->
    <section id="katalog" class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold dark:text-white flex items-center gap-3">
                <i class="bi bi-box-seam text-blue-500"></i> Hardware Assets
            </h2>
            <a href="{{ url('/katalog') }}" class="text-xs font-mono text-slate-500 hover:text-blue-500 transition-colors uppercase tracking-widest">view_all_assets()</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($inventaris->take(4) as $item)
            <div class="railway-card rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden group">
                <div class="relative h-48 bg-slate-100 dark:bg-slate-900/50">
                    @if($item->foto_barang)
                        <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    @else
                        <div class="flex items-center justify-center h-full text-slate-700"><i class="bi bi-hash text-4xl"></i></div>
                    @endif
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-slate-900 dark:text-white truncate">{{ $item->nama_aset }}</h3>
                    </div>
                    <p class="font-mono text-[10px] text-slate-500">{{ $item->kode_barang }}</p>
                    
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-[10px] font-mono {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'text-green-500' : 'text-slate-500' }}">
                             {{ strtoupper(str_replace(' ', '_', $item->tipe_peminjaman)) }}
                        </span>
                        @auth
                            <button class="text-blue-500 hover:text-blue-600 font-bold text-xs">RESERVE</button>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-full text-center py-20 font-mono text-slate-500">STOK_EMPTY_NULL</div>
            @endforelse
        </div>
    </section>

    <!-- ARTIKEL (Clean Typography) -->
    <section id="blog" class="max-w-7xl mx-auto py-12 border-t border-slate-200 dark:border-slate-800">
        <div class="grid lg:grid-cols-4 gap-12">
            <div class="lg:col-span-1 space-y-4">
                <h2 class="text-2xl font-bold dark:text-white leading-tight">Engineering Updates</h2>
                <p class="text-sm text-slate-500">Catatan teknis, berita laboratorium, dan pengumuman riset terbaru.</p>
                <a href="{{ route('blog.index') }}" class="inline-flex items-center text-sm font-bold text-blue-500">
                    Browse Archive <i class="bi bi-arrow-up-right ml-2 text-xs"></i>
                </a>
            </div>
            
            <div class="lg:col-span-3 grid sm:grid-cols-2 gap-8">
                @foreach($latestPosts->take(2) as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group block space-y-4">
                    <div class="aspect-video rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400?text=LAB' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-[10px] font-mono text-slate-500">
                            <span>{{ $post->created_at->format('Y.m.d') }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                            <span>{{ strtoupper($post->category ?? 'General') }}</span>
                        </div>
                        <h3 class="text-lg font-bold dark:text-white group-hover:text-blue-500 transition-colors">{{ $post->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Typed !== 'undefined') {
            new Typed('#typed-text', {
                strings: [
                    'asset_management',
                    'research_resources',
                    'administrative_flow',
                    'embedded_systems'
                ],
                typeSpeed: 40,
                backSpeed: 20,
                backDelay: 2500,
                loop: true,
                cursorChar: '_',
            });
        }
    });
</script>
@endsection