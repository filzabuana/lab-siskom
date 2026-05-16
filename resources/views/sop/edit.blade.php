@extends('layouts.modern')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] transition-colors duration-500 py-10">
    <div class="max-w-4xl mx-auto px-4">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Edit <span class="text-blue-600">SOP</span></h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Memperbarui dokumen: {{ $sop->judul }}</p>
            </div>
            <a href="{{ route('sop.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden transition-colors">
            <form action="{{ route('sop.update', $sop->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">Judul SOP</label>
                        <input type="text" name="judul" value="{{ $sop->judul }}" required
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">Kategori</label>
                        <input type="text" name="kategori" value="{{ $sop->kategori }}" required
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3" required
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none leading-relaxed">{{ $sop->deskripsi }}</textarea>
                </div>

                <div class="p-6 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border-2 border-dashed border-blue-100 dark:border-blue-900/30 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-600 rounded-xl text-white">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-blue-900 dark:text-blue-300">Update File PDF</label>
                            <span class="text-[10px] text-blue-600/70 dark:text-blue-400/50">Kosongkan jika tidak ingin mengubah file saat ini.</span>
                        </div>
                    </div>
                    
                    <input type="file" name="file_pdf" 
                           class="block w-full text-sm text-slate-500 dark:text-slate-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-xs file:font-bold
                                  file:bg-blue-600 file:text-white
                                  hover:file:bg-blue-700 transition-all cursor-pointer">
                    
                    <div class="flex items-center gap-2 px-3 py-2 bg-white/50 dark:bg-black/20 rounded-lg border border-blue-50 dark:border-blue-900/20">
                        <i class="bi bi-info-circle text-blue-500 text-xs"></i>
                        <span class="text-[10px] font-mono text-slate-500 truncate">Aktif: {{ $sop->file_pdf }}</span>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h5 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="bi bi-diagram-3 text-blue-500"></i>
                            Edit Alur Visual
                        </h5>
                    </div>
                    
                    <div id="wrapper-alur" class="space-y-4">
                        @foreach($listAlur as $index => $alur)
                        <div class="alur-item p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Judul Tahapan {{ $index + 1 }}</label>
                                <input type="text" name="alur_judul[]" value="{{ $alur['judul'] }}" required
                                       class="w-full px-4 py-2 rounded-lg bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Kode Mermaid.js</label>
                                <textarea name="alur_kode[]" rows="6" required
                                          class="w-full p-4 rounded-lg bg-slate-900 text-emerald-400 font-mono text-xs border-none focus:ring-2 focus:ring-blue-500 transition-all shadow-inner">{{ $alur['kode'] }}</textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-3">
                    <button type="submit" class="flex-1 md:flex-none px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-95">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('sop.index') }}" class="flex-1 md:flex-none px-8 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Haluskan transisi antar tema */
    * { transition-property: background-color, border-color, color, fill, stroke; transition-duration: 300ms; }
    
    /* Style scrollbar untuk textarea kode */
    textarea::-webkit-scrollbar { width: 6px; }
    textarea::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
</style>
@endsection