@extends('layouts.modern')

@section('content')
<div class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
            <div class="flex items-center space-x-5">
                <a href="{{ route('admin.posts.index') }}" 
                   class="inline-flex items-center justify-center w-12 h-12 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-blue-600 shadow-sm hover:shadow-md transition-all">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic">Panduan Penulisan</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm font-medium tracking-widest uppercase">SOP Publikasi Web Lab Pemrograman & Komputasi</p>
                </div>
            </div>
            <div class="hidden md:block">
                <span class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-full border border-blue-100 dark:border-blue-800 uppercase tracking-widest">Official Documentation</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- 1. Standar Visual & Media --}}
            <div class="md:col-span-2 group">
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-sm shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 rounded-[2.5rem] overflow-hidden transition-all hover:border-blue-500/30">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 flex items-center">
                        <i class="bi bi-image text-white text-xl mr-3"></i>
                        <h5 class="text-white font-black uppercase tracking-widest text-sm">1. Standar Visual & Media</h5>
                    </div>
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row items-center gap-10">
                            <div class="flex-1 space-y-6">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center shrink-0 mt-1">
                                        <i class="bi bi-check2-circle font-black"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm font-bold text-slate-900 dark:text-white uppercase italic">Rasio Aspek 16:9</h6>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1">Wajib menggunakan resolusi 1280x720px atau 1920x1080px agar thumbnail tidak terpotong otomatis oleh sistem landing page.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center shrink-0 mt-1">
                                        <i class="bi bi-filetype-webp font-black"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm font-bold text-slate-900 dark:text-white uppercase italic">Format WebP / JPG</h6>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1">Gunakan format <code class="text-blue-500 font-bold">.webp</code> untuk efisiensi loading atau <code class="text-blue-500 font-bold">.jpg</code> yang sudah dikompresi maksimal 2MB.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center shrink-0 mt-1">
                                        <i class="bi bi-exclamation-triangle font-black"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm font-bold text-slate-900 dark:text-white uppercase italic">Tipografi Gambar</h6>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1">Hindari menaruh teks penting di pinggir gambar. Berikan <span class="italic">safe-zone</span> di tengah agar pesan utama tidak terpotong.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-72">
                                <div class="relative bg-slate-100 dark:bg-slate-900 rounded-2xl p-4 border-2 border-dashed border-slate-200 dark:border-slate-700">
                                    <div class="aspect-video bg-white dark:bg-slate-800 rounded-lg shadow-inner flex items-center justify-center border border-slate-200 dark:border-slate-700">
                                        <span class="text-blue-600 dark:text-blue-400 font-black text-xl italic tracking-tighter">16 : 9</span>
                                    </div>
                                    <p class="text-[10px] text-center text-slate-400 mt-3 font-bold uppercase tracking-widest italic">Thumbnail Preview</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Markdown Cheat Sheet --}}
            <div class="md:col-span-2">
                <div class="bg-slate-900 dark:bg-slate-800 shadow-2xl rounded-[2.5rem] overflow-hidden border border-slate-800 dark:border-slate-700">
                    <div class="px-8 py-5 border-b border-slate-800 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="bi bi-markdown text-blue-400 text-xl mr-3"></i>
                            <h5 class="text-white font-black uppercase tracking-[0.2em] text-xs">2. Cheat Sheet Markdown (Parsedown)</h5>
                        </div>
                        <span class="text-[10px] text-slate-500 font-mono tracking-tighter">v1.0.0-stable</span>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            {{-- Struktur Teks --}}
                            <div>
                                <h6 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-6 flex items-center italic">
                                    <span class="w-1.5 h-4 bg-blue-500 mr-2 rounded-full"></span> Struktur Teks
                                </h6>
                                <div class="space-y-4">
                                    @foreach([
                                        '# Judul Utama' => '<h1>Heading 1</h1>',
                                        '### Sub Judul' => '<h3>Heading 3</h3>',
                                        '**Teks Tebal**' => '<strong>Bold Text</strong>',
                                        '*Teks Miring*' => '<em>Italic Text</em>',
                                        '~~Dicoret~~' => '<del>Strikethrough</del>'
                                    ] as $code => $result)
                                        <div class="flex items-center justify-between group/item">
                                            <code class="text-pink-400 text-[11px] font-mono bg-slate-800 dark:bg-slate-900 px-2 py-1 rounded border border-slate-700">{{ $code }}</code>
                                            <div class="text-slate-400 text-sm">{!! $result !!}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-8 p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 italic text-center">Lists & Peluru</p>
                                    <div class="grid grid-cols-2 gap-4 text-[11px]">
                                        <code class="text-blue-300 font-mono">- Item Satu<br>- Item Dua</code>
                                        <code class="text-blue-300 font-mono">1. Langkah A<br>2. Langkah B</code>
                                    </div>
                                </div>
                            </div>

                            {{-- Lanjutan --}}
                            <div>
                                <h6 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-6 flex items-center italic">
                                    <span class="w-1.5 h-4 bg-blue-500 mr-2 rounded-full"></span> Kode & Media
                                </h6>
                                <div class="space-y-4">
                                    <div class="p-4 bg-black/40 rounded-2xl border border-slate-700">
                                        <p class="text-[10px] text-slate-500 font-bold uppercase mb-2 tracking-tighter">Syntax Highlighting</p>
                                        <code class="text-emerald-400 text-[11px] font-mono">
                                            ```php<br>
                                            echo "Hello Lab!";<br>
                                            ```
                                        </code>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-slate-500 font-bold mb-1 italic">Link URL</span>
                                            <code class="text-pink-400 text-[11px] font-mono bg-slate-800 px-2 py-1 rounded border border-slate-700 inline-block">[Teks](https://...)</code>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-slate-500 font-bold mb-1 italic">Gambar Internal</span>
                                            <code class="text-pink-400 text-[11px] font-mono bg-slate-800 px-2 py-1 rounded border border-slate-700 inline-block">![Alt](https://...)</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tips --}}
                        <div class="mt-10 bg-blue-600/10 border border-blue-500/20 rounded-2xl p-4 flex items-start">
                            <i class="bi bi-lightbulb-fill text-blue-400 text-lg mr-4"></i>
                            <p class="text-xs text-slate-300 leading-relaxed italic">
                                <strong class="text-blue-400 uppercase">Pro Tip:</strong> Parsedown mendukung HTML mentah. Anda bisa menggunakan tag <code class="text-pink-400 font-bold">&lt;br&gt;</code> untuk memberikan spasi baris tambahan atau tag <code class="text-pink-400 font-bold">&lt;div class="text-center"&gt;</code> untuk meratakan konten ke tengah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Bahasa & Etika --}}
            <div class="group">
                <div class="h-full bg-white dark:bg-slate-800/50 backdrop-blur-sm p-8 rounded-[2.5rem] border-t-4 border-blue-600 shadow-xl shadow-slate-200/50 dark:shadow-none transition-all hover:scale-[1.02]">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center mr-4">
                            <i class="bi bi-chat-left-quote-fill"></i>
                        </div>
                        <h5 class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Bahasa & Gaya</h5>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-loose italic">
                        Gunakan Bahasa Indonesia formal (PUEBI). Sebagai portal resmi <strong class="text-slate-900 dark:text-white">FMIPA UNTAN</strong>, hindari penggunaan bahasa slang, singkatan tidak baku, atau opini pribadi yang tidak relevan dengan konten laboratorium.
                    </p>
                </div>
            </div>

            {{-- 4. Verifikasi --}}
            <div class="group">
                <div class="h-full bg-white dark:bg-slate-800/50 backdrop-blur-sm p-8 rounded-[2.5rem] border-t-4 border-red-600 shadow-xl shadow-slate-200/50 dark:shadow-none transition-all hover:scale-[1.02]">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 flex items-center justify-center mr-4">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5 class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Verifikasi Konten</h5>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-loose italic">
                        Pastikan status postingan tetap <strong class="text-red-600">DRAFT</strong> hingga konten selesai divalidasi oleh Koordinator Lab atau PLP Ahli. Hal ini penting untuk menjaga akurasi data yang dikonsumsi publik.
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer Help --}}
        <div class="mt-16 text-center">
            <div class="inline-flex flex-col md:flex-row items-center bg-white dark:bg-slate-800 px-6 py-3 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Butuh bantuan teknis?</span>
                <div class="flex items-center">
                    <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mx-2">Filza Buana Putra</span>
                    <span class="text-slate-300 dark:text-slate-600">|</span>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mx-2">Admin Lab FMIPA</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling khusus untuk elemen Markdown di dalam guide */
    code {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }
    /* Transisi halus untuk hover */
    .group:hover .bg-blue-600 {
        background-color: #1d4ed8;
    }
</style>
@endsection