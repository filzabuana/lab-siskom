@extends('layouts.modern')

@section('content')
{{-- Container Utama --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-none">
                Otoritas Pengguna
            </h1>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mt-2 italic">
                Sistem Manajemen Hak Akses Laboratorium
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.users.create') }}" 
                   class="flex items-center gap-3 px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-600/20 transition-all active:scale-95 italic">
                    <i class="bi bi-person-plus-fill text-lg"></i> Register Akun
                </a>
            @endif

            <div class="px-5 py-4 bg-white dark:bg-railway-card border border-slate-100 dark:border-railway-border rounded-2xl shadow-sm">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Total Basis Data: <span class="text-blue-600">{{ $users->count() }} User</span></span>
            </div>
        </div>
    </div>

    {{-- Versi TABLE (Desktop) --}}
    <div class="hidden md:block bg-white dark:bg-railway-card rounded-[3rem] border border-slate-100 dark:border-railway-border shadow-2xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-white/5 border-b border-slate-100 dark:border-railway-border">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic">Identitas Pengguna</th>
                    <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic text-center">Otoritas / Role</th>
                    <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic text-center text-red-500">Alat Aktif</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 italic text-right">Opsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-railway-border">
                @foreach($users as $user)
                <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-500/[0.02] transition-all">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            @php 
                                $userRoles = $user->getRoleNames(); // Mengambil semua role
                                $avatarColor = $user->is_admin ? 'bg-amber-500/10 text-amber-600' : ($userRoles->contains('plp') ? 'bg-purple-500/10 text-purple-600' : 'bg-blue-500/10 text-blue-600');
                            @endphp

                            <div class="w-12 h-12 rounded-2xl {{ $avatarColor }} flex items-center justify-center font-black text-sm border border-current border-opacity-10 shadow-sm transition-transform group-hover:scale-110 overflow-hidden">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    @if($user->is_admin)
                                        <i class="bi bi-shield-check text-xl"></i>
                                    @elseif($userRoles->contains('plp'))
                                        <i class="bi bi-person-badge text-xl"></i>
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    @endif
                                @endif
                            </div>
                            
                            <div>
                                <div class="text-sm font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight italic flex items-center gap-2">
                                    {{ $user->name }}
                                    @if($user->is_admin)
                                        <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)] animate-pulse"></span>
                                    @endif
                                </div>
                                <div class="text-[9px] font-mono text-slate-400 uppercase tracking-[0.2em] mt-0.5">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- BAGIAN ROLE: Diubah untuk menampilkan semua role --}}
                    <td class="px-6 py-5">
                        <div class="flex flex-wrap justify-center gap-1 max-w-[200px] mx-auto">
                            @if($user->is_admin)
                                <span class="px-2 py-0.5 bg-amber-500 text-white text-[7px] font-black uppercase rounded-md italic shadow-sm">
                                    Superadmin
                                </span>
                            @endif
                            
                            @foreach($userRoles as $role)
                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 text-[7px] font-black uppercase tracking-widest rounded-md border border-slate-200 dark:border-white/10 italic">
                                    {{ str_replace('_', ' ', $role) }}
                                </span>
                            @endforeach

                            @if($userRoles->isEmpty() && !$user->is_admin)
                                <span class="text-[7px] text-slate-400 italic">No Role Assigned</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-5 text-center">
                        @if($user->peminjamans_count > 0)
                            <div class="inline-flex flex-col">
                                <span class="text-lg font-black text-red-500 leading-none italic">{{ $user->peminjamans_count }}</span>
                                <span class="text-[8px] font-black text-red-400 uppercase tracking-tighter">Items</span>
                            </div>
                        @else
                            <i class="bi bi-check2-all text-emerald-500 text-xl"></i>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all italic shadow-xl active:scale-95">
                            {{ auth()->user()->is_admin ? 'Manage' : 'Detail' }} <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Versi CARD (Mobile) --}}
    <div class="grid grid-cols-1 gap-6 md:hidden">
        @foreach($users as $user)
        <div class="bg-white dark:bg-railway-card rounded-[2.5rem] p-7 border border-slate-100 dark:border-railway-border shadow-lg relative overflow-hidden">
            @if($user->is_admin)
                <div class="absolute top-0 right-0 px-4 py-1 bg-amber-500 text-white text-[8px] font-black uppercase italic rounded-bl-2xl">Admin</div>
            @endif

            <div class="flex items-center gap-5 mb-6">
                <div class="w-16 h-16 rounded-3xl bg-slate-50 dark:bg-railway-dark flex items-center justify-center border border-slate-100 dark:border-white/5 shadow-inner overflow-hidden">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="bi {{ $user->is_admin ? 'bi-shield-lock text-amber-500' : 'bi-person text-blue-500' }} text-3xl"></i>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-black text-slate-800 dark:text-white uppercase italic tracking-tighter truncate leading-none mb-1">
                        {{ $user->name }}
                    </h3>
                    <p class="text-[9px] font-mono text-slate-400 uppercase tracking-widest truncate mb-2">
                        {{ $user->email }}
                    </p>
                    <div class="flex flex-wrap gap-1">
                        @foreach($user->getRoleNames() as $role)
                            <span class="px-2 py-0.5 bg-blue-500/10 text-blue-600 text-[7px] font-black uppercase rounded-md border border-blue-500/10 italic">
                                {{ str_replace('_', ' ', $role) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-5 border-t border-slate-50 dark:border-white/5">
                <div class="flex gap-4">
                    <div class="text-center">
                        <p class="text-[8px] font-black text-slate-400 uppercase mb-0.5">Alat</p>
                        <p class="text-xs font-black {{ $user->peminjamans_count > 0 ? 'text-red-500' : 'text-slate-300' }} italic">{{ $user->peminjamans_count }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[8px] font-black text-slate-400 uppercase mb-0.5">Bebas Lab</p>
                        <i class="bi {{ $user->bebas_lab ? 'bi-check-circle-fill text-emerald-500' : 'bi-dash-circle text-slate-200' }} text-xs"></i>
                    </div>
                </div>

                <a href="{{ route('admin.users.show', $user->id) }}" 
                   class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-2xl italic shadow-lg active:scale-95 transition-all">
                    Detail Profil
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection