@extends('layouts.modern')

@section('content')
<div id="app" class="min-h-screen bg-white dark:bg-railway-dark transition-colors duration-500 py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-full opacity-[0.03] dark:opacity-[0.05] pointer-events-none" 
         style="background-image: radial-gradient(#3b82f6 1px, transparent 1px); background-size: 30px 30px;"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <span class="inline-flex px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-full uppercase italic tracking-widest mb-4 border border-blue-100 dark:border-blue-800">
                Warta & Riset Laboratorium
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tighter uppercase italic mb-6">
                Blog <span class="text-blue-600">Eksplorasi</span>
            </h1>
            <p class="max-w-2xl mx-auto text-slate-500 dark:text-slate-400 text-sm md:text-base font-medium leading-relaxed">
                Kumpulan tutorial pemrograman, dokumentasi riset, dan pembaruan kegiatan terbaru dari Laboratorium Pemrograman dan Komputasi.
            </p>
            <div class="w-20 h-1.5 bg-blue-600 mx-auto mt-8 rounded-full"></div>
        </div>

        {{-- Blog Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="group relative bg-white dark:bg-railway-card rounded-[2.5rem] border border-slate-100 dark:border-railway-border overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10">
                    
                    {{-- Image Header --}}
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400?text=Lab+Article' }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                             alt="{{ $post->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent opacity-60"></div>
                        <div class="absolute bottom-4 left-6">
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-black rounded-full uppercase tracking-tighter">
                                {{ $post->created_at->format('d M, Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-8">
                        <h4 class="text-xl font-black text-slate-800 dark:text-white uppercase italic tracking-tighter leading-tight mb-4 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h4>
                        
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed mb-6 line-clamp-3">
                            @php
                                $cleanMarkdown = preg_replace('/[*#_~`>\[\]\(\)]/', '', $post->content);
                                $preview = Str::limit(strip_tags($cleanMarkdown), 120);
                            @endphp
                            {{ $preview }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-railway-border">
                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-[10px] font-black text-blue-600 uppercase tracking-widest group/link">
                                Baca Selengkapnya 
                                <i class="bi bi-arrow-right ms-2 transition-transform group-hover/link:translate-x-2"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <i class="bi bi-journal-x text-6xl text-slate-200 dark:text-railway-border mb-4 inline-block"></i>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-sm">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-16 flex justify-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection