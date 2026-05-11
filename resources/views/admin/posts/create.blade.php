@extends('layouts.modern')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            {{-- Kolom Kiri: Editor Utama --}}
            <div class="w-full lg:w-2/3 space-y-6">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('admin.posts.index') }}" 
                       class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic">Tulis Artikel Baru</h2>
                </div>

                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-sm shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 rounded-[2rem] p-6 md:p-8">
                    {{-- Input Judul --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Judul Artikel</label>
                        <input type="text" name="title" 
                               class="block w-full px-5 py-4 text-lg font-bold rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 focus:ring-4 focus:ring-blue-600/10 focus:border-blue-600 transition-all outline-none placeholder:text-slate-400" 
                               value="{{ old('title') }}" placeholder="Masukkan judul menarik..." required>
                        @error('title') <p class="mt-2 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Konten --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3 px-1">
                            <label class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Konten (Markdown)</label>
                            <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 px-3 py-1 rounded-full uppercase tracking-widest">Support HTML</span>
                        </div>
                        <textarea name="content" id="markdown-editor" rows="15" 
                                  class="block w-full px-5 py-4 font-mono text-sm rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 focus:ring-4 focus:ring-blue-600/10 focus:border-blue-600 transition-all outline-none leading-relaxed" 
                                  placeholder="Tulis artikel di sini..." required>{{ old('content') }}</textarea>
                        @error('content') <p class="mt-2 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Markdown Helper Box (Bagian Keterangan Tetap Terjaga) --}}
                    <div class="bg-slate-50/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                            <span class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center">
                                <i class="bi bi-code-slash mr-2 text-blue-500"></i> Bantuan Penulisan
                            </span>
                            <a href="{{ route('admin.posts.guide') }}" target="_blank" class="text-[11px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline">
                                Panduan Lengkap &rarr;
                            </a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                            @foreach(['## Judul', '**Tebal**', '*Miring*', '[Link](url)', '- List', '`Code`'] as $helper)
                                <code class="text-[10px] text-center py-2 px-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-600 dark:text-pink-400 shadow-sm font-mono uppercase tracking-tighter">
                                    {{ $helper }}
                                </code>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Meta Data & Publish (Sticky) --}}
            <div class="w-full lg:w-1/3 lg:sticky lg:top-24 space-y-6">
                
                {{-- Card Publikasi --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-sm shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 rounded-[2rem] p-8">
                    <h5 class="text-sm font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em] mb-6 flex items-center italic">
                        <i class="bi bi-send-fill mr-2"></i> Publikasi
                    </h5>
                    
                    {{-- Switch Status --}}
                    <div class="mb-8">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Status Publikasi</label>
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 transition-all hover:border-blue-500/30">
                            <label for="statusSwitch" class="text-xs font-bold text-slate-700 dark:text-slate-300">Terbitkan Sekarang</label>
                            <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                                <input type="checkbox" name="is_published" id="statusSwitch" value="1" {{ old('is_published') ? 'checked' : '' }}
                                       class="peer appearance-none w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-700 checked:bg-blue-600 cursor-pointer transition-colors" />
                                <span class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 ease-in peer-checked:translate-x-6 pointer-events-none"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="mb-8 space-y-4">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Gambar Sampul</label>
                        
                        <div class="bg-blue-50/50 dark:bg-blue-900/10 p-3 rounded-xl border-l-4 border-blue-500">
                            <p class="text-[10px] text-blue-700 dark:text-blue-400 font-medium leading-relaxed italic">
                                <i class="bi bi-aspect-ratio mr-1 font-bold"></i> Gunakan rasio 16:9 agar thumbnail tidak terpotong.
                            </p>
                        </div>

                        <div id="image-preview-wrapper" class="group relative overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 transition-all hover:border-blue-500/50">
                            {{-- Preview Image akan muncul di sini via JS --}}
                            <div id="placeholder-text" class="py-8 flex flex-col items-center justify-center text-slate-400 transition-opacity">
                                <i class="bi bi-cloud-arrow-up text-3xl mb-2"></i>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Pilih Gambar</span>
                            </div>
                            <input type="file" name="image" id="image-input" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        </div>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 text-center italic">JPG, PNG. Maks 2MB.</p>
                        @error('image') <p class="mt-2 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50 mt-6">
                        <button type="submit" class="w-full flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition-all shadow-xl shadow-blue-600/20 active:scale-95">
                            <i class="bi bi-plus-circle mr-2 text-sm"></i> Simpan Artikel
                        </button>
                    </div>
                </div>

                {{-- Card Info --}}
                <div class="p-5 bg-blue-600/5 dark:bg-blue-400/5 border border-blue-600/10 dark:border-blue-400/10 rounded-[2rem] text-center">
                    <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest leading-loose italic">
                        <i class="bi bi-info-circle-fill mr-1"></i> Artikel draft tidak akan muncul di halaman publik.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Logic Preview Gambar
    document.getElementById('image-input').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const wrapper = document.getElementById('image-preview-wrapper');
            const placeholder = document.getElementById('placeholder-text');
            let preview = document.getElementById('preview-img');
            
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'preview-img';
                preview.className = 'w-full aspect-video object-cover transition-all';
                wrapper.prepend(preview);
            }
            
            placeholder.style.display = 'none';
            preview.src = URL.createObjectURL(file);
        }
    }
</script>
@endsection