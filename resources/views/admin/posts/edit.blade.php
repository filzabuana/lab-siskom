@extends('layouts.modern')

@section('content')
<div class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            {{-- Kolom Kiri: Editor Utama --}}
            <div class="w-full lg:w-2/3">
                <div class="flex items-center space-x-5 mb-8">
                    <a href="{{ route('admin.posts.index') }}" 
                       class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 transition-all">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic">Edit Artikel</h2>
                </div>

                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-sm shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 rounded-[2rem] p-6 md:p-8">
                    {{-- Input Judul --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 italic">Judul Artikel</label>
                        <input type="text" name="title" 
                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl px-5 py-4 text-lg font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all @error('title') ring-2 ring-red-500 @enderror" 
                               value="{{ old('title', $post->title) }}" placeholder="Masukkan judul yang menarik..." required>
                        @error('title') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Konten --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Konten (Markdown)</label>
                            <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-lg uppercase tracking-tighter">Support MD & HTML</span>
                        </div>
                        <textarea name="content" id="markdown-editor" rows="18" 
                                  class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl p-6 font-mono text-sm leading-relaxed text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-blue-500 transition-all @error('content') ring-2 ring-red-500 @enderror" 
                                  placeholder="Mulai menulis keajaiban..." required>{{ old('content', $post->content) }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Markdown Helper Box --}}
                    <div class="bg-slate-900 rounded-2xl p-5 border border-slate-800">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center italic">
                                <i class="bi bi-terminal me-2 text-blue-400"></i> Cheat Sheet Cepat:
                            </span>
                            <a href="{{ route('admin.posts.guide') }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-[10px] font-black uppercase tracking-widest no-underline">
                                Lihat Panduan <i class="bi bi-arrow-up-right ms-1"></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                            @foreach(['## Judul', '**Tebal**', '*Miring*', '[Link](url)', '- List', '`Code`'] as $item)
                                <div class="bg-slate-800 text-pink-400 text-[10px] font-mono py-2 px-1 rounded-lg border border-slate-700 text-center select-none">{{ $item }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar --}}
            <div class="w-full lg:w-1/3 lg:sticky lg:top-10">
                <div class="space-y-6">
                    {{-- Card Publikasi --}}
                    <div class="bg-white dark:bg-slate-800/50 backdrop-blur-sm shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 rounded-[2rem] p-8">
                        <h5 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-6 flex items-center italic">
                            <span class="w-1.5 h-4 bg-blue-600 mr-2 rounded-full"></span> Konfigurasi
                        </h5>
                        
                        {{-- Switch Status --}}
                        <div class="mb-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Status Publikasi</label>
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Terbitkan Artikel</span>
                                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                                    <input type="checkbox" name="is_published" id="statusSwitch" value="1" {{ $post->is_published ? 'checked' : '' }}
                                           class="absolute w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer checked:right-0 checked:bg-blue-600 transition-all duration-300">
                                    <label for="statusSwitch" class="block overflow-hidden h-6 rounded-full bg-slate-300 dark:bg-slate-700 cursor-pointer"></label>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Gambar --}}
                        <div class="mb-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Gambar Utama (16:9)</label>
                            
                            <div id="image-preview-wrapper" class="group relative aspect-video bg-slate-100 dark:bg-slate-900 rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 dark:border-slate-700 mb-4 transition-all hover:border-blue-500/50">
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover" id="preview-img">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400" id="placeholder-preview">
                                        <i class="bi bi-image text-3xl mb-2"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">No Image</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-[10px] font-black uppercase tracking-widest">Ganti Gambar</span>
                                </div>
                            </div>
                            
                            <input type="file" name="image" id="image-input" class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all" accept="image/*">
                            @error('image') <p class="text-red-500 text-[10px] mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="group relative w-full inline-flex items-center justify-center px-8 py-4 font-black text-white transition-all duration-200 bg-blue-600 rounded-full hover:bg-blue-700 focus:outline-none shadow-lg shadow-blue-200 dark:shadow-none uppercase tracking-widest text-xs italic">
                            <i class="bi bi-cloud-arrow-up-fill me-2 transition-transform group-hover:-translate-y-1"></i>
                            Update Konten
                        </button>
                    </div>

                    {{-- Card Info --}}
                    <div class="bg-blue-600 rounded-[2rem] p-6 text-white overflow-hidden relative shadow-xl shadow-blue-200 dark:shadow-none">
                        <i class="bi bi-shield-check absolute -right-4 -bottom-4 text-7xl opacity-20"></i>
                        <h6 class="text-[10px] font-black uppercase tracking-[0.2em] mb-2">Security Notice</h6>
                        <p class="text-[11px] leading-relaxed opacity-90 italic">Pastikan seluruh link eksternal menggunakan HTTPS dan media yang diunggah tidak melanggar hak cipta.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>

<style>
    #markdown-editor::-webkit-scrollbar { width: 8px; }
    #markdown-editor::-webkit-scrollbar-track { background: transparent; }
    #markdown-editor::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    [data-bs-theme="dark"] #markdown-editor::-webkit-scrollbar-thumb { background: #334155; }

    /* Custom Checkbox Slider */
    #statusSwitch:checked ~ label { background-color: #2563eb; }
    #statusSwitch { right: auto; left: 0; }
    #statusSwitch:checked { right: 0; left: auto; }
</style>

<script>
    // Preview Gambar Logic
    document.getElementById('image-input').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const wrapper = document.getElementById('image-preview-wrapper');
            let preview = document.getElementById('preview-img');
            let placeholder = document.getElementById('placeholder-preview');
            
            if (placeholder) placeholder.remove();
            
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'preview-img';
                preview.className = 'w-full h-full object-cover';
                wrapper.appendChild(preview);
            }
            preview.src = URL.createObjectURL(file);
        }
    }
</script>
@endsection