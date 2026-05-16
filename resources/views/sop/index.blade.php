@extends('layouts.modern')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20 text-[10px] font-bold uppercase tracking-widest">
                Resource Center
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Repository <span class="text-blue-600">SOP</span>
            </h2>
            <p class="text-sm md:text-base text-slate-500 dark:text-zinc-400 max-w-2xl">
                Daftar Standar Operasional Prosedur resmi untuk menunjang kegiatan di Laboratorium Pemrograman dan Komputasi.
            </p>
        </div>

        @if(Auth::check() && Auth::user()->is_admin)
            <a href="{{ route('admin.sop.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-500/20 group">
                <i class="bi bi-plus-lg transition-transform group-hover:rotate-90"></i> 
                Tambah SOP Baru
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($semuaSop as $sop)
        <div class="group relative flex flex-col bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[1.5rem] overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1">
            
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border border-slate-200 dark:border-white/10">
                        {{ $sop->kategori }}
                    </span>
                    <i class="bi bi-file-earmark-pdf text-2xl text-slate-300 dark:text-slate-700 group-hover:text-blue-500 transition-colors"></i>
                </div>

                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ $sop->judul }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-zinc-400 leading-relaxed line-clamp-3">
                    {{ $sop->deskripsi }}
                </p>
            </div>

            <div class="px-6 pb-6 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('sop.show', $sop->slug) }}" class="flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-white/5 rounded-lg border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                        Lihat Detail
                    </a>
                    <a href="{{ asset('dokumen-sop/' . $sop->file_pdf) }}" 
                       download="{{ $sop->file_pdf }}"
                       class="flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20">
                        <i class="bi bi-download"></i> PDF
                    </a>
                </div>

                {{-- ADMIN CONTROLS --}}
                @if(Auth::check() && Auth::user()->is_admin)
                <div class="pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.sop.edit', $sop->id) }}" class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition-colors" title="Edit SOP">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('admin.sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SOP ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus SOP">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($semuaSop->isEmpty())
    <div class="text-center py-20 bg-slate-50 dark:bg-white/[0.02] rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
        <i class="bi bi-folder2-open text-5xl text-slate-300 mb-4 inline-block"></i>
        <p class="text-slate-500 dark:text-zinc-500 font-medium">Belum ada dokumen SOP yang tersedia.</p>
    </div>
    @endif

</div>
@endsection