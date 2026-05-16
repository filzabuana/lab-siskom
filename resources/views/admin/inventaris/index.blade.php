@extends('layouts.modern')

@section('content')
<div class="container py-4 px-3 md:py-10 md:px-4">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 md:mb-10">
        <div class="min-w-0">
            <h2 class="text-xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic flex items-center">
                <span class="w-1.5 h-6 md:w-2.5 md:h-11 bg-blue-600 mr-2 md:mr-3 rounded-full flex-shrink-0"></span>
                <span class="truncate">Inventaris &nbsp;</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-[9px] md:text-sm mt-0.5 uppercase tracking-[0.1em] md:tracking-[0.2em]">Aset Lab Pemrograman & Komputasi</p>
        </div>
        
        <a href="{{ route('admin.inventaris.create') }}" 
           class="inline-flex items-center justify-center px-5 py-3 md:px-8 md:py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl md:rounded-full shadow-lg transition-all hover:-translate-y-1 active:scale-95 flex-shrink-0">
            <i class="bi bi-plus-lg md:me-2"></i> 
            <span class="tracking-tighter">Tambah Barang</span>
        </a>
    </div>

    {{-- Filter & Search Section --}}
    <form action="{{ route('admin.inventaris.index') }}" method="GET" class="mb-6 p-3 md:p-4 bg-white/60 dark:bg-slate-800/60 backdrop-blur-md rounded-3xl md:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
        <div class="flex flex-col gap-3">
            {{-- Row 1: Search (Full Width) --}}
            <div class="relative w-full">
                <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="CARI NAMA / KODE..." 
                       class="w-full pl-12 pr-4 py-3 md:py-4 bg-white dark:bg-slate-900 border-none rounded-xl md:rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-blue-500 italic shadow-inner">
            </div>

            {{-- Row 2: Grid 2x2 di Mobile, Sejajar di PC --}}
            <div class="grid grid-cols-2 md:flex md:flex-row gap-2 md:gap-3">
                <select name="kategori" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border-none rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest px-4 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-blue-500 italic text-slate-500 cursor-pointer shadow-sm">
                    <option value="">KATEGORI</option>
                    @foreach($listKategori as $cat)
                        <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ strtoupper($cat) }}</option>
                    @endforeach
                </select>

                <select name="ruangan" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border-none rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest px-4 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-blue-500 italic text-slate-500 cursor-pointer shadow-sm">
                    <option value="">RUANGAN</option>
                    @foreach($listRuangan as $ruang)
                        <option value="{{ $ruang }}" {{ request('ruangan') == $ruang ? 'selected' : '' }}>{{ strtoupper($ruang) }}</option>
                    @endforeach
                </select>

                <select name="lokasi" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border-none rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest px-4 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-blue-500 italic text-slate-500 cursor-pointer shadow-sm">
                    <option value="">LOKASI</option>
                    @foreach($listLokasi as $lok)
                        <option value="{{ $lok }}" {{ request('lokasi') == $lok ? 'selected' : '' }}>{{ strtoupper($lok) }}</option>
                    @endforeach
                </select>

                <select name="per_page" onchange="this.form.submit()" class="bg-blue-50 dark:bg-slate-900 border-none rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest px-4 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-blue-500 italic text-blue-600 cursor-pointer shadow-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 DATA</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 DATA</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 DATA</option>
                </select>
            </div>
        </div>
        
        @if(request()->anyFilled(['search', 'kategori', 'ruangan', 'lokasi']))
            <div class="mt-3 flex justify-end">
                <a href="{{ route('admin.inventaris.index') }}" class="text-[8px] md:text-[9px] font-black text-red-500 uppercase tracking-tighter hover:bg-red-50 dark:hover:bg-red-900/20 px-2 py-1 rounded-lg transition-colors">
                    <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset
                </a>
            </div>
        @endif
    </form>

    {{-- Items Container --}}
    <div class="lg:bg-white lg:dark:bg-slate-800 lg:border lg:border-slate-100 lg:dark:border-slate-700 lg:rounded-[3rem] lg:shadow-2xl lg:shadow-slate-200/60 lg:dark:shadow-none lg:overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-3 lg:border-spacing-y-0 lg:border-collapse">
                <thead class="hidden lg:table-header-group">
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Info Aset</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Kategori & Ruang</th>
                        <th class="px-8 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Stok / Kondisi</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Lokasi</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="block lg:table-row-group px-1">
                    @forelse($semuaInventaris as $item)
                    <tr class="block lg:table-row bg-white dark:bg-slate-800 lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-100 dark:border-slate-700 lg:border-none shadow-sm lg:shadow-none p-3 lg:p-0 mb-3 lg:mb-0 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-all group">
                        
                        {{-- Info Aset --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-1 lg:py-6">
                            <div class="flex items-center gap-3 md:gap-4">
                                <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 flex-shrink-0 shadow-sm">
                                    @if($item->foto_barang)
                                        <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600"><i class="bi bi-box-seam text-base md:text-xl"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[11px] md:text-[13px] font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight italic leading-tight truncate">{{ $item->nama_aset }} &nbsp;</div>
                                    <span class="font-mono text-[8px] md:text-[9px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-1.5 py-0.5 rounded border border-blue-100 dark:border-blue-800 uppercase mt-0.5 inline-block">
                                        {{ $item->kode_barang }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori & Ruang (Ringkas di Mobile) --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-2 lg:py-6 border-b lg:border-none border-slate-50 dark:border-slate-700">
                            <div class="flex justify-between items-center lg:block">
                                <span class="lg:hidden text-[8px] font-black text-slate-300 uppercase italic">Kategori & Ruang</span>
                                <div class="text-right lg:text-left">
                                    <span class="text-[9px] md:text-[10px] font-black text-slate-700 dark:text-slate-200 uppercase italic">{{ $item->kategori }}</span>
                                    <span class="hidden lg:inline"> · </span>
                                    <span class="text-[9px] md:text-[10px] font-bold text-blue-500 uppercase tracking-tighter lg:mt-0.5 block lg:inline">
                                        <i class="bi bi-geo-alt-fill mr-1"></i>{{ $item->ruangan }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Stok --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-2 lg:py-6">
                            <div class="flex justify-between items-center lg:block">
                                <span class="lg:hidden text-[8px] font-black text-slate-300 uppercase italic">Stok</span>
                                <div class="text-right lg:text-center">
                                    <span class="text-[10px] md:text-sm font-black text-slate-700 dark:text-slate-200 italic tracking-tighter">{{ $item->jumlah_stok }} UNITS</span>
                                    <div class="lg:mt-1">
                                        <span class="text-[8px] md:text-[9px] font-black {{ $item->jumlah_rusak > 0 ? 'text-red-500' : 'text-emerald-500' }} uppercase italic bg-slate-50 dark:bg-slate-900/50 px-1.5 py-0.5 rounded-md border border-slate-100 dark:border-slate-700">
                                            {{ $item->jumlah_rusak > 0 ? $item->jumlah_rusak . ' Rusak' : 'Kondisi Baik' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Lokasi Spesifik --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-2 lg:py-6">
                            <div class="flex justify-between items-center lg:block">
                                <span class="lg:hidden text-[8px] font-black text-slate-300 uppercase italic">Lokasi</span>
                                <div class="text-right lg:text-left min-w-0">
                                    <p class="font-mono text-[9px] md:text-[11px] text-blue-600 dark:text-blue-400 font-bold italic bg-blue-50/50 dark:bg-slate-900/50 px-2 py-0.5 rounded-lg truncate inline-block max-w-[150px] lg:max-w-none">
                                        {{ $item->catatan_lokasi ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="block lg:table-cell px-0 lg:px-8 py-2 lg:py-6">
                            <div class="flex justify-end gap-1.5 md:gap-2 pt-2 lg:pt-0 border-t lg:border-none border-slate-50 dark:border-slate-700">
                                <a href="{{ route('admin.inventaris.show', $item->id) }}" class="p-2 md:p-3 bg-slate-50 dark:bg-slate-900 rounded-xl text-slate-400 hover:bg-blue-600 hover:text-white transition-all" title="Detail"><i class="bi bi-eye text-xs md:text-base"></i></a>
                                <a href="{{ route('admin.inventaris.edit', $item->id) }}" class="p-2 md:p-3 bg-slate-50 dark:bg-slate-900 rounded-xl text-amber-500 hover:bg-amber-500 hover:text-white transition-all" title="Edit"><i class="bi bi-pencil-square text-xs md:text-base"></i></a>
                                <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 md:p-3 bg-slate-50 dark:bg-slate-900 rounded-xl text-red-500 hover:bg-red-500 hover:text-white transition-all" title="Hapus"><i class="bi bi-trash3 text-xs md:text-base"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-box-seam text-4xl text-slate-200 mb-3"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Data tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-6 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700">
            {{ $semuaInventaris->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection