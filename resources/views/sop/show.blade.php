@extends('layouts.modern')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="space-y-2">
            <a href="{{ route('sop.index') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-blue-600 transition-colors group">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Repository
            </a>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase">
                {{ $sop->judul }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-lg bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest">
                    {{ $sop->kategori }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                    Publikasi: {{ $sop->created_at->format('d M Y') }}
                </span>
            </div>
        </div>

        {{-- PDF Download --}}
        <a href="{{ asset('dokumen-sop/' . $sop->file_pdf) }}" 
           class="flex items-center gap-3 px-6 py-3 bg-white dark:bg-slate-800 border-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-500 hover:text-white transition-all shadow-lg shadow-emerald-500/10 active:scale-95" 
           download>
            <i class="bi bi-file-earmark-pdf-fill text-lg"></i> Unduh Dokumen Resmi
        </a>
    </div>

    {{-- Deskripsi --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 mb-8">
        <h6 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-3 italic">Deskripsi Prosedur</h6>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
            {{ $sop->deskripsi }}
        </p>
    </div>

    {{-- Visualisasi Alur (Vue Component) --}}
    <div class="space-y-4">
        <div class="flex items-center gap-4">
            <div class="h-[1px] flex-grow bg-slate-200 dark:bg-slate-800"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic">Visualisasi Flowchart</span>
            <div class="h-[1px] flex-grow bg-slate-200 dark:bg-slate-800"></div>
        </div>

        <div id="vue-flow-renderer">
            {{-- Mengirim data array ke prop initial-data --}}
            <flowchart-viewer :initial-data="{{ json_encode($flowchartsData) }}"></flowchart-viewer>
        </div>
    </div>

    {{-- Info Footer Lab --}}
    <div class="mt-12 text-center border-t border-slate-100 dark:border-slate-800 pt-8">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Laboratorium Pemrograman dan Komputasi <br>
            <span class="text-blue-500">Fakultas Matematika dan Ilmu Pengetahuan Alam - Universitas Tanjungpura</span>
        </p>
    </div>
</div>
@endsection