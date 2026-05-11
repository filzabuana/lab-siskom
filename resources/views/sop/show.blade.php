@extends('layouts.modern')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 md:py-12">
    
    <div class="mb-8 text-center space-y-4">
        <a href="{{ route('sop.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors group">
            <i class="bi bi-arrow-left me-2 transition-transform group-hover:-translate-x-1"></i>
            Kembali ke Repository
        </a>
        
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            {{ $sop->judul }}
        </h2>
        
        <span class="inline-block px-4 py-1.5 rounded-full bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-blue-500/20">
            {{ $sop->kategori }}
        </span>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 mb-8 shadow-sm overflow-hidden relative group">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="bi bi-file-earmark-pdf text-6xl"></i>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <div class="text-center md:text-left">
                <h6 class="font-bold text-slate-900 dark:text-white">Dokumen Resmi Tersedia</h6>
                <p class="text-sm text-slate-500">Unduh versi lengkap untuk keperluan administrasi offline.</p>
            </div>
            <a href="{{ asset('dokumen-sop/' . $sop->file_pdf) }}" 
               class="flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-emerald-500/20 active:scale-95" 
               download>
                <i class="bi bi-file-earmark-arrow-down-fill"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 mb-10 shadow-sm">
        <h6 class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mb-4">Ringkasan Prosedur</h6>
        <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ $sop->deskripsi }}
        </p>
    </div>

    <div class="space-y-6">
        <div class="text-center">
            <span class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">--- Visualisasi Alur Kerja ---</span>
        </div>

        <div class="bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-slate-800 rounded-2xl p-4">
            <div class="text-center mb-3">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Legenda Pelaksana</span>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                @php
                    $actors = [
                        ['name' => 'Kepala Lab', 'color' => 'bg-blue-500'],
                        ['name' => 'Laboran/PLP', 'color' => 'bg-emerald-500'],
                        ['name' => 'Dosen', 'color' => 'bg-amber-500'],
                        ['name' => 'Asisten', 'color' => 'bg-cyan-500'],
                        ['name' => 'Mahasiswa', 'color' => 'bg-slate-500']
                    ];
                @endphp
                @foreach($actors as $actor)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl">
                    <span class="w-2 h-2 rounded-full {{ $actor['color'] }}"></span>
                    <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300">{{ $actor['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @php $semuaAlur = json_decode($sop->gambar_alur, true); @endphp
        @if(is_array($semuaAlur))
        <div class="space-y-4" id="accordionSOP">
            @foreach($semuaAlur as $index => $alur)
            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900 transition-all shadow-sm">
                <button class="w-full flex items-center justify-between p-5 text-left group transition-colors hover:bg-slate-50 dark:hover:bg-white/[0.02]" 
                        onclick="toggleAccordion('{{ $index }}')">
                    <span class="font-bold text-slate-900 dark:text-white flex items-center gap-3">
                        <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-blue-600/10 text-blue-600 text-xs">
                            {{ $index + 1 }}
                        </span>
                        {{ $alur['judul'] }}
                    </span>
                    <i id="icon-{{ $index }}" class="bi bi-chevron-down text-slate-400 transition-transform duration-300"></i>
                </button>
                
                <div id="collapse-{{ $index }}" class="hidden border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-black/20">
                    <div class="overflow-x-auto p-6 flex justify-center">
                        <div class="mermaid-container min-w-full" data-code="{{ $alur['kode'] }}">
                            <div class="text-slate-400 text-[10px] italic py-4 text-center">Inisialisasi diagram...</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="mt-12 p-5 rounded-2xl bg-blue-50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10 flex gap-4">
        <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
        <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
            <strong>Catatan Penting:</strong> Kerusakan pada barang habis pakai (BHP) dikecualikan dari prosedur penggantian barang rusak. Pastikan Anda telah membaca dokumen PDF untuk rincian sanksi dan ketentuan lainnya.
        </p>
    </div>
</div>

{{-- Mermaid JS & Logic --}}
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    // Inisialisasi awal
    const isDarkMode = document.documentElement.classList.contains('dark');
    mermaid.initialize({ 
        theme: isDarkMode ? 'dark' : 'neutral',
        flowchart: { htmlLabels: true, curve: 'stepAfter' }
    });

    // Fungsi Toggle Accordion Manual (karena kita lepas dari bootstrap.js)
    function toggleAccordion(index) {
        const content = document.getElementById(`collapse-${index}`);
        const icon = document.getElementById(`icon-${index}`);
        const container = content.querySelector('.mermaid-container');
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');

        if (!content.classList.contains('hidden') && !container.classList.contains('rendered')) {
            const code = container.getAttribute('data-code');
            container.innerHTML = `<div class="mermaid">${code}</div>`;
            mermaid.init(undefined, container.querySelector('.mermaid')).then(() => {
                container.classList.add('rendered');
            });
        }
    }
</script>

<style>
    /* Styling scrollbar tipis untuk diagram lebar */
    .overflow-x-auto::-webkit-scrollbar { height: 4px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .dark .overflow-x-auto::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection