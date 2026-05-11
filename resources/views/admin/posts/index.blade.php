@extends('layouts.modern')

@section('content')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center uppercase italic">
                <span class="w-2 h-7 bg-blue-600 rounded-full mr-3"></span>
                Manajemen Blog
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Kelola artikel dan warta laboratorium.</p>
        </div>
        <div class="w-full md:w-auto">
            <a href="{{ route('admin.posts.create') }}" 
               class="flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-95 w-full md:w-auto">
                <i class="bi bi-pencil-square mr-2 text-base"></i> Tulis Artikel Baru
            </a>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-white dark:bg-slate-800/50 backdrop-blur-sm shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden">
        
        {{-- Desktop View (Hidden on Mobile) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700/50">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Konten</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Info</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($posts as $post)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/120x80?text=No+Image' }}" 
                                     class="w-20 h-14 rounded-xl object-cover shadow-sm ring-2 ring-white dark:ring-slate-700">
                                <div class="ml-4 max-w-[200px] lg:max-w-xs">
                                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate">{{ $post->title }}</div>
                                    <div class="text-[11px] text-slate-400 truncate italic">/blog/{{ $post->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-[11px] text-slate-700 dark:text-slate-300 font-medium">
                                {{ $post->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $post->is_published ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                {{ $post->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-20 text-center text-slate-400 italic">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View (Hidden on Desktop) --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($posts as $post)
            <div class="p-5 flex flex-col space-y-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/80x80?text=No+Image' }}" 
                             class="w-16 h-16 rounded-xl object-cover shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight truncate-2-lines uppercase">{{ $post->title }}</h4>
                            <p class="text-[10px] text-slate-400 mt-1 italic truncate">/blog/{{ $post->slug }}</p>
                        </div>
                    </div>
                    <span class="inline-flex px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-tighter {{ $post->is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $post->is_published ? 'LIVE' : 'DRAFT' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between pt-2 border-t border-slate-50 dark:border-slate-700/30">
                    <div class="text-[10px] text-slate-500 font-medium italic">
                        <i class="bi bi-calendar-event mr-1"></i> {{ $post->created_at->format('d/m/y') }}
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('blog.show', $post->slug) }}" class="p-2 text-blue-500"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-slate-400"><i class="bi bi-pencil-square"></i></a>
                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-400" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-slate-400 italic">Belum ada artikel.</div>
            @endforelse
        </div>

        @if($posts->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700/50">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* Menangani judul yang terlalu panjang di mobile agar tetap rapi */
    .truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection