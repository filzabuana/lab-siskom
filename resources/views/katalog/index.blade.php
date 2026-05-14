@extends('layouts.modern')

@section('content')
<div class="min-h-screen bg-white dark:bg-railway-dark transition-colors duration-500 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    {{-- Decorative Background Element --}}
    <div class="absolute top-0 left-0 w-full h-full opacity-[0.03] dark:opacity-[0.05] pointer-events-none" 
         style="background-image: radial-gradient(#3b82f6 1px, transparent 1px); background-size: 30px 30px;"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        
        {{-- Header Section: Professional & Techy --}}
        <div class="mb-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-[2px] w-12 bg-blue-600"></div>
                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs tracking-[0.3em] uppercase font-bold">System.Assets.Inventory</span>
            </div>
            <h2 class="text-5xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tighter mb-4 uppercase italic leading-none">
                Katalog <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Alat &nbsp;</span>
            </h2>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <p class="text-slate-500 dark:text-slate-400 font-medium max-w-xl uppercase text-[10px] tracking-widest leading-relaxed">
                    Repository perangkat keras dan modul praktikum <br> 
                    <span class="text-slate-900 dark:text-slate-200">Lab Pemrograman & Komputasi — UNTAN</span>
                </p>
                <div class="flex items-center gap-2 bg-slate-100 dark:bg-white/5 px-4 py-2 rounded-lg border border-slate-200 dark:border-railway-border">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="font-mono text-[10px] text-slate-600 dark:text-slate-400 uppercase tracking-tighter">System Status: Operational</span>
                </div>
            </div>
        </div>

        {{-- Grid Katalog: Lebih rapat & responsif --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
            @forelse($inventaris as $item)
                {{-- Card Item: Compact Style --}}
                <div class="group relative bg-white dark:bg-railway-card rounded-2xl sm:rounded-[2rem] border border-slate-200 dark:border-railway-border transition-all duration-500 transform-gpu hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/15 flex flex-col h-full overflow-hidden"
                    style="isolation: isolate;">
                    
                    {{-- Area Gambar: Lebih kecil & efisien --}}
                    <div class="relative block overflow-hidden bg-slate-100 dark:bg-railway-dark/50 aspect-square sm:aspect-[4/3] rounded-t-2xl sm:rounded-t-[2rem]">
                        @if($item->foto_barang)
                            <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" 
                                alt="{{ $item->nama_aset }}" 
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110 transform-gpu"
                                onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=No+Data';">
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-300 dark:text-slate-700">
                                <i class="bi bi-cpu text-3xl sm:text-4xl mb-1"></i>
                                <span class="font-mono text-[7px] sm:text-[9px] uppercase tracking-widest">Null_Ptr</span>
                            </div>
                        @endif

                        {{-- Mini Status Badge --}}
                        <div class="absolute top-2 right-2 sm:top-4 sm:right-4 z-10">
                            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                <div class="px-2 py-0.5 sm:px-3 sm:py-1 bg-emerald-500/90 backdrop-blur-md text-white text-[7px] sm:text-[9px] font-bold uppercase tracking-widest rounded shadow-lg">
                                    Active
                                </div>
                            @else
                                <div class="px-2 py-0.5 sm:px-3 sm:py-1 bg-slate-800/80 backdrop-blur-md text-slate-300 text-[7px] sm:text-[9px] font-bold uppercase tracking-widest rounded shadow-lg">
                                    Locked
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Body Card: Slim & Clean --}}
                    <div class="p-3 sm:p-6 flex flex-col flex-grow text-start">
                        <div class="mb-3 sm:mb-4">
                            <div class="font-mono text-[8px] sm:text-[10px] text-blue-600 dark:text-blue-400 mb-0.5 font-bold opacity-70">
                                #{{ $item->kode_barang }}
                            </div>
                            <a href="{{ route('katalog.show', $item->id) }}" class="block">
                                <h6 class="text-xs sm:text-lg font-black text-slate-800 dark:text-white leading-tight mb-1 uppercase tracking-tighter group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ $item->nama_aset }}
                                </h6>
                            </a>
                        </div>

                        <div class="mt-auto space-y-3">
                            @auth
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <div class="flex items-center justify-between px-0.5 border-b border-slate-100 dark:border-railway-border pb-2">
                                        <span class="font-mono text-[7px] sm:text-[9px] text-slate-400 dark:text-slate-500 uppercase">Stock</span>
                                        <span class="font-mono text-[10px] sm:text-xs font-bold {{ $item->jumlah_stok > 0 ? 'text-blue-600' : 'text-red-500' }}">
                                            {{ str_pad($item->jumlah_stok, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                    
                                    <button @click="openModal({{ $item->id }})" 
                                            class="w-full py-2 sm:py-3 bg-slate-900 dark:bg-blue-600 text-white rounded-lg sm:rounded-xl font-bold text-[8px] sm:text-[10px] uppercase tracking-widest transition-all hover:bg-blue-700 active:scale-95 disabled:opacity-40"
                                            {{ $item->jumlah_stok <= 0 ? 'disabled' : '' }}>
                                        Request_Alat
                                    </button>
                                @else
                                    <div class="py-2 px-2 bg-slate-50 dark:bg-white/5 rounded-lg border border-dashed border-slate-200 dark:border-railway-border text-center">
                                        <span class="font-mono text-[7px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                            Internal_Use
                                        </span>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full py-2 sm:py-3 border border-slate-200 dark:border-railway-border text-center rounded-lg sm:rounded-xl transition-all hover:border-blue-500 bg-slate-50 dark:bg-transparent">
                                    <span class="font-mono text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">
                                        Login()
                                    </span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center border-2 border-dashed border-slate-100 dark:border-railway-border rounded-3xl">
                    <span class="font-mono text-xs text-slate-400 uppercase">Data_Not_Found</span>
                </div>
            @endforelse
        </div>

    
    @auth
        @foreach($inventaris as $item)
            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                @include('peminjaman.partials.modal_pinjam')
            @endif
        @endforeach
    @endauth
</div>
@endsection