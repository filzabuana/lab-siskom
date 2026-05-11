@extends('layouts.modern')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">
    
    {{-- Breadcrumb Modern --}}
    <nav class="flex mb-8 px-1" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.2em] italic">
            <li>
                <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-blue-500 transition-colors">
                    Manajemen User
                </a>
            </li>
            <li class="flex items-center space-x-2">
                <span class="text-slate-300 dark:text-slate-700">/</span>
                <span class="text-slate-900 dark:text-white">{{ $user->name }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Kolom Kiri: Profil Mahasiswa --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-railway-card rounded-[2.5rem] border border-slate-100 dark:border-railway-border shadow-xl p-8 text-center relative overflow-hidden">
                {{-- Decorative Element --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 -mr-16 -mt-16 rounded-full"></div>
                
                <div class="relative mb-6">
                    <div class="inline-flex w-24 h-24 rounded-[2rem] bg-gradient-to-br from-blue-500 to-indigo-600 items-center justify-center text-white shadow-2xl shadow-blue-500/30">
                        <i class="bi bi-person-badge text-4xl"></i>
                    </div>
                </div>
                
                <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter mb-1">
                    {{ $user->name }}
                </h2>
                <p class="text-xs font-medium text-slate-400 mb-4">{{ $user->email }}</p>
                
                <div class="inline-block px-4 py-1.5 bg-slate-50 dark:bg-railway-dark border border-slate-100 dark:border-railway-border rounded-full mb-8">
                    <span class="text-[10px] font-mono font-black text-slate-500 uppercase tracking-[0.2em]">
                        NIM: {{ $user->nim ?? 'UNREGISTERED' }}
                    </span>
                </div>

                <div class="space-y-3">
                    @php 
                        $isClear = $riwayat->where('status', 'disetujui')->count() === 0;
                    @endphp

                    @if($isClear)
                        <button class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] italic transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                            <i class="bi bi-patch-check-fill mr-2 text-lg align-middle"></i> Proses Bebas Lab
                        </button>
                    @else
                        <div class="w-full py-4 bg-slate-100 dark:bg-railway-dark text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] italic border border-slate-200 dark:border-railway-border opacity-60 cursor-not-allowed">
                            <i class="bi bi-exclamation-triangle-fill mr-2 text-red-500"></i> Belum Bebas Lab
                        </div>
                        <p class="text-[9px] font-bold text-red-500/70 uppercase tracking-widest italic mt-2">
                            Masih ada alat dalam tanggungan
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Tanggungan & Riwayat --}}
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-railway-card rounded-[2.5rem] border border-slate-100 dark:border-railway-border shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 dark:border-railway-border flex justify-between items-center bg-slate-50/50 dark:bg-white/[0.02]">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest">
                        Inventory Log
                    </h3>
                    <span class="px-4 py-1.5 bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-full">
                        {{ $riwayat->count() }} Entries
                    </span>
                </div>

                <div class="overflow-x-auto text-nowrap">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-slate-50 dark:border-railway-border">
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Asset Details</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Timeline</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-center">Security Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-railway-border">
                            @forelse($riwayat as $item)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.01] transition-colors">
                                <td class="px-8 py-5 text-nowrap">
                                    <div class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase italic tracking-tight mb-0.5">
                                        {{ $item->inventaris->nama_aset ?? 'Unknown Item' }}
                                    </div>
                                    <div class="text-[9px] font-mono text-slate-400 uppercase tracking-widest">
                                        SN: {{ $item->inventaris->kode_barang ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase italic mb-0.5">
                                        {{ $item->created_at->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[8px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-[0.1em]">Registered Date</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($item->status == 'disetujui')
                                        <span class="px-4 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-red-100 dark:border-red-500/20 italic">
                                            Not Returned
                                        </span>
                                    @elseif($item->status == 'selesai')
                                        <span class="px-4 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-lg italic">
                                            Returned
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 bg-slate-100 dark:bg-railway-dark text-slate-500 text-[9px] font-black uppercase tracking-widest rounded-lg italic">
                                            {{ strtoupper($item->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-20 dark:opacity-10">
                                        <i class="bi bi-shield-slash text-6xl mb-4"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] italic text-slate-500">No Inventory Records Found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection