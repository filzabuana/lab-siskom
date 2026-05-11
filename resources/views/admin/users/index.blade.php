@extends('layouts.modern')

@section('content')
{{-- Container Utama --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter">
                Manajemen User
            </h1>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic">
                Daftar mahasiswa terdaftar
            </p>
        </div>
    </div>

    {{-- Versi TABLE (Hanya muncul di Desktop / md ke atas) --}}
    <div class="hidden md:block bg-white dark:bg-railway-card rounded-[2rem] border border-slate-100 dark:border-railway-border shadow-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-white/5 border-b border-slate-100 dark:border-railway-border">
                    <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic">Mahasiswa</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic text-center">Status</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-railway-border">
                @foreach($users as $user)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 font-black text-[10px]">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight italic">{{ $user->name }}</div>
                                <div class="text-[9px] font-mono text-slate-400 uppercase tracking-widest">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->peminjamans_count > 0)
                            <span class="px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-red-100 dark:border-red-500/20 italic">
                                {{ $user->peminjamans_count }} Alat
                            </span>
                        @else
                            <span class="text-slate-300 dark:text-slate-600 text-[9px] font-black uppercase tracking-widest italic">Nihil</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all italic shadow-md">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


{{-- Versi CARD (Hanya muncul di Mobile / di bawah md) --}}
<div class="grid grid-cols-1 gap-4 md:hidden px-2">
    @foreach($users as $user)
    <div class="bg-white dark:bg-railway-card rounded-[2rem] p-6 border border-slate-100 dark:border-railway-border shadow-sm active:scale-[0.98] transition-transform">
        <div class="flex items-center gap-4 mb-5">
            {{-- Icon User / Avatar --}}
            <div class="relative flex-shrink-0">
                <div class="w-14 h-14 rounded-[1.2rem] bg-slate-100 dark:bg-railway-dark flex items-center justify-center text-slate-400 dark:text-zinc-600 border border-slate-200 dark:border-railway-border shadow-inner">
                    <i class="bi bi-person-fill text-3xl"></i>
                </div>
                
                @if($user->peminjamans_count > 0)
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white dark:border-railway-card rounded-full flex items-center justify-center">
                        <i class="bi bi-exclamation text-[10px] text-white font-bold"></i>
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase italic tracking-tight truncate mb-0.5">
                    {{ $user->name }}
                </h3>
                <div class="flex flex-col gap-0.5">
                    <p class="text-[9px] font-mono text-slate-400 uppercase tracking-[0.2em]">
                        {{ $user->nim ?? 'NIM TIDAK TERDATA' }}
                    </p>
                    {{-- Menampilkan Email di bawah NIM --}}
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-envelope-at text-[10px] text-blue-500/60"></i>
                        <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400 truncate tracking-tight">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Info Status & Tombol --}}
        <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-railway-border">
            <div class="flex flex-col">
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5 italic text-opacity-70">Inventory Status</span>
                @if($user->peminjamans_count > 0)
                    <span class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase italic">
                         Holding {{ $user->peminjamans_count }} Items
                    </span>
                @else
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase italic">
                        No Active Loans
                    </span>
                @endif
            </div>

            <a href="{{ route('admin.users.show', $user->id) }}" 
               class="flex items-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-xl italic shadow-md">
                View <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>
</div>
@endsection