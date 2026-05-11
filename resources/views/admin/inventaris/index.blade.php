@extends('layouts.modern')

@section('content')
<div class="container py-6 px-4 md:py-10">
    {{-- Header Section: Mewah tapi Kalem --}}
    <div class="flex items-center justify-between gap-4 mb-10">
        <div class="min-w-0">
            <h2 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic flex items-center">
                <span class="w-2 h-8 md:w-2.5 md:h-11 bg-blue-600 mr-3 rounded-full flex-shrink-0"></span>
                <span class="truncate">Inventaris</span>
            </h2>
            <p class="hidden sm:block text-slate-500 dark:text-slate-400 font-medium text-xs md:text-sm mt-1 uppercase tracking-[0.2em]">Aset Lab Pemrograman & Komputasi</p>
        </div>
        
        {{-- Button: Shadow Gelap (Bukan Glow Biru) --}}
        <a href="{{ route('admin.inventaris.create') }}" 
           class="inline-flex items-center justify-center px-5 py-3 md:px-8 md:py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] md:text-xs uppercase tracking-widest rounded-2xl md:rounded-full shadow-lg shadow-slate-300 dark:shadow-slate-950 transition-all hover:-translate-y-1 active:scale-95 flex-shrink-0">
            <i class="bi bi-plus-lg md:me-2"></i> 
            <span class="hidden md:inline text-nowrap">Tambah Barang</span>
            <span class="md:hidden ml-1">Baru</span>
        </a>
    </div>

    {{-- Container Tabel: Glassmorphism yang sudah diredam --}}
    <div class="lg:bg-white lg:dark:bg-slate-800 lg:border lg:border-slate-100 lg:dark:border-slate-700 lg:rounded-[3rem] lg:shadow-2xl lg:shadow-slate-200/60 lg:dark:shadow-none lg:overflow-hidden">
        <div class="w-full">
            <table class="w-full border-separate border-spacing-y-4 lg:border-spacing-y-0 lg:border-collapse">
                <thead class="hidden lg:table-header-group">
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Kode</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Nama Aset</th>
                        <th class="px-8 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Kondisi</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Akses</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="block lg:table-row-group">
                    @foreach($semuaInventaris as $item)
                    {{-- Row: Jadi Card di Mobile, Shadow Lembut --}}
                    <tr class="block lg:table-row bg-white dark:bg-slate-800 lg:bg-transparent rounded-[2.5rem] lg:rounded-none border border-slate-100 dark:border-slate-700 lg:border-none shadow-xl shadow-slate-100 dark:shadow-none p-6 lg:p-0 mb-5 lg:mb-0 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-all group">
                        
                        {{-- Kode --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-1 lg:py-5">
                            <div class="flex justify-between items-center lg:block mb-4 lg:mb-0">
                                <span class="lg:hidden text-[10px] font-black text-slate-300 uppercase italic">Kode Aset</span>
                                <span class="font-mono text-[11px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-xl border border-blue-100 dark:border-blue-800">
                                    {{ $item->kode_barang }}
                                </span>
                            </div>
                        </td>

                        {{-- Nama & Merk --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-2 lg:py-5 border-b lg:border-none border-slate-50 dark:border-slate-700 pb-4 lg:pb-5">
                            <div class="text-lg lg:text-base font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight">{{ $item->nama_aset }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $item->merk }} • {{ $item->tahun_perolehan }}</div>
                        </td>

                        {{-- Stok --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-4 lg:py-5">
                            <div class="flex justify-between items-center lg:block">
                                <span class="lg:hidden text-[10px] font-black text-slate-300 uppercase italic">Ketersediaan</span>
                                <div class="text-right lg:text-center">
                                    <div class="text-base lg:text-sm font-black text-slate-700 dark:text-slate-200">{{ $item->jumlah_stok + $item->jumlah_rusak }} Units</div>
                                    @if($item->jumlah_rusak > 0)
                                        <span class="text-[10px] font-black text-red-500 uppercase italic bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-lg"><i class="bi bi-exclamation-triangle-fill"></i> {{ $item->jumlah_rusak }} Rusak</span>
                                    @else
                                        <span class="text-[10px] font-black text-emerald-500 uppercase italic tracking-tighter">Kondisi Baik</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Akses --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-3 lg:py-5">
                            <div class="flex justify-between items-center lg:block">
                                <span class="lg:hidden text-[10px] font-black text-slate-300 uppercase italic">Akses</span>
                                <span class="text-[10px] font-black uppercase tracking-[0.15em] px-4 py-1.5 rounded-full {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200 dark:shadow-none' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">
                                    {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'Bisa Dipinjam' : 'Hanya Lab' }}
                                </span>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-5 lg:py-5">
                            <div class="flex justify-end gap-3 border-t lg:border-none border-slate-50 dark:border-slate-700 pt-5 lg:pt-0">
                                <a href="{{ route('admin.inventaris.show', $item->id) }}" class="p-3 bg-slate-50 dark:bg-slate-900 rounded-2xl text-slate-400 hover:bg-blue-600 hover:text-white hover:shadow-xl transition-all"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.inventaris.edit', $item->id) }}" class="p-3 bg-slate-50 dark:bg-slate-900 rounded-2xl text-amber-500 hover:bg-amber-500 hover:text-white hover:shadow-xl transition-all"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="p-3 bg-slate-50 dark:bg-slate-900 rounded-2xl text-red-500 hover:bg-red-500 hover:text-white hover:shadow-xl transition-all btn-delete" data-nama="{{ $item->nama_aset }}"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Mengatasi "Shiny" berlebih: Mengganti shadow biru ke shadow slate yang netral */
    .shadow-lg { box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.1) !important; }
    .shadow-xl { box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05) !important; }
    
    /* Scrollbar minimalis agar tidak merusak pemandangan di mobile */
    @media (max-width: 1024px) {
        body { background-color: #f8fafc; }
    }
</style>
@endsection