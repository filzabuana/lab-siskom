@extends('layouts.modern')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl py-10">

    {{-- Top Navigation: Back to Public Catalog --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <a href="{{ route('katalog.index') }}" 
           class="group flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-blue-600 transition-colors italic">
            <i class="bi bi-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Sisi Kiri: Foto & Informasi Utama --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Main Info Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    {{-- Image Section --}}
                    <div class="md:w-5/12 relative bg-slate-100 dark:bg-slate-900">
                        @if($item->foto_barang)
                            <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                                 class="h-full w-full object-cover min-h-[300px]" 
                                 alt="{{ $item->nama_aset }}">
                        @else
                            <div class="h-full w-full min-h-[300px] flex flex-col items-center justify-center text-slate-400 space-y-3">
                                <i class="bi bi-image text-5xl opacity-20"></i>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] italic">No Image Available</span>
                            </div>
                        @endif
                    </div>

                    {{-- Text Info --}}
                    <div class="md:w-7/12 p-8 md:p-10">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded-full italic">
                                {{ $item->kategori }}
                            </span>
                            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                <span class="px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-full italic border border-emerald-100 dark:border-emerald-800">
                                    <i class="bi bi-house-door mr-1"></i> Mobile
                                </span>
                            @else
                                <span class="px-4 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-full italic">
                                    <i class="bi bi-lock-fill mr-1"></i> Statis
                                </span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-black text-slate-900 dark:text-white leading-tight mb-2 uppercase italic tracking-tighter">
                            {{ $item->nama_aset }}
                        </h1>
                        
                        <p class="font-mono text-xs text-slate-400 mb-8 tracking-widest">
                            {{ $item->kode_barang }}
                        </p>

                        <div class="grid grid-cols-2 gap-y-6 gap-x-4 border-t border-slate-50 dark:border-slate-700 pt-8">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Merk / Brand</label>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase">{{ $item->merk ?? 'Generic' }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Tahun Perolehan</label>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $item->tahun_perolehan }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Ruangan / Lokasi</label>
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase italic">
                                    <i class="bi bi-geo-alt mr-1"></i>{{ $item->ruangan }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-6 flex items-center italic">
                    <i class="bi bi-card-text mr-3 text-blue-600"></i> Deskripsi & Kegunaan
                </h3>
                <div class="prose dark:prose-invert prose-sm max-w-none text-slate-500 dark:text-slate-400 leading-relaxed italic">
                    {!! Str::markdown($item->deskripsi ?? 'Belum ada deskripsi spesifik untuk alat ini.') !!}
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Status & Stok --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Stock Status Card --}}
            <div class="{{ Auth::check() ? 'bg-blue-600 shadow-blue-200' : 'bg-slate-100 dark:bg-slate-800' }} rounded-[2.5rem] p-8 text-center shadow-xl dark:shadow-none transition-all">
                <h6 class="text-[10px] font-black uppercase tracking-[0.2em] mb-2 {{ Auth::check() ? 'text-blue-100' : 'text-slate-400' }} italic">Stok Tersedia</h6>
                @auth
                    <div class="text-6xl font-black text-white italic tracking-tighter mb-1">
                        {{ str_pad($stokTersedia, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest italic">Unit Siap Pakai</p>
                @else
                    <div class="py-6 flex flex-col items-center gap-4">
                        <i class="bi bi-shield-lock text-3xl text-slate-300"></i>
                        <a href="{{ route('google.login') }}" class="text-[10px] font-black uppercase tracking-widest px-6 py-3 bg-white text-slate-900 rounded-full shadow-sm hover:bg-slate-50 transition-all italic">Login untuk Cek Stok</a>
                    </div>
                @endauth
            </div>

            {{-- Action Button (Mengarahkan ke Halaman Sisi Peminjaman Mahasiswa) --}}
            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                @auth
                    <a href="{{ route('peminjaman.katalog') }}"
                       class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[1.5rem] shadow-lg shadow-emerald-200 dark:shadow-none font-black text-xs uppercase tracking-[0.2em] transition-all hover:-translate-y-1 italic flex items-center justify-center gap-3 {{ $stokTersedia <= 0 ? 'pointer-events-none opacity-40 bg-slate-400' : '' }}">
                        <i class="bi bi-plus-circle text-lg"></i> Pinjam Alat Ini di Aplikasi
                    </a>
                @else
                    <a href="{{ route('google.login') }}" 
                       class="w-full py-5 bg-slate-900 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] transition-all hover:bg-black italic flex items-center justify-center gap-3">
                        <i class="bi bi-box-arrow-in-right text-lg"></i> Login Meminjam
                    </a>
                @endauth
            @endif

            {{-- Condition Badge --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic ml-1">Kondisi Alat</h6>
                @auth
                    @if($item->kondisi == 'Baik')
                        <div class="flex items-center gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800/30">
                            <i class="bi bi-check-circle-fill text-2xl text-emerald-500"></i>
                            <div>
                                <p class="text-xs font-black text-emerald-800 dark:text-emerald-400 uppercase italic">Kondisi Baik</p>
                                <p class="text-[10px] text-emerald-600/70 italic leading-none">Siap Dioperasikan</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-800/30">
                            <i class="bi bi-exclamation-triangle-fill text-2xl text-amber-500"></i>
                            <div>
                                <p class="text-xs font-black text-amber-800 dark:text-amber-400 uppercase italic">{{ $item->kondisi }}</p>
                                <p class="text-[10px] text-amber-600/70 italic leading-none">Laporkan Bila Bermasalah</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic leading-relaxed">
                            <i class="bi bi-shield-lock mr-1"></i> Detail Kondisi Diproteksi
                        </p>
                    </div>
                @endauth
            </div>

            {{-- Timestamp --}}
            @auth
            <div class="flex items-center justify-between px-6 py-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Terakhir Update</span>
                <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 italic">{{ $item->updated_at->diffForHumans() }}</span>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection