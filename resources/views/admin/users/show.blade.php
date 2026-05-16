@extends('layouts.modern')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-none">Manajemen Otoritas</h1>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mt-1">Sistem Informasi Laboratorium FMIPA UNTAN</p>
            </div>
        </div>

        @if(auth()->user()->is_admin)
        <form action="{{ route('admin.users.impersonate', $user->id) }}" method="POST">
            @csrf
            <button type="submit" class="group flex items-center gap-3 px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl shadow-xl hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all active:scale-95">
                <i class="bi bi-incognito text-xl"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Login Sebagai User</span>
            </button>
        </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Sidebar: Identitas & Akses Kontrol --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-railway-card rounded-[2.5rem] p-8 border border-slate-100 dark:border-railway-border shadow-2xl relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>
                
                <div class="text-center mb-8 relative">
                    <div class="w-28 h-28 rounded-[2.5rem] bg-gradient-to-br from-blue-500 to-indigo-600 text-white mx-auto flex items-center justify-center text-4xl font-black mb-4 border-8 border-slate-50 dark:border-railway-dark shadow-2xl overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase italic tracking-tight">{{ $user->name }}</h2>
                    <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest mt-1">{{ $user->email }}</p>
                    
                    {{-- Badge Status (Menampilkan Semua Role) --}}
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        @if($user->is_admin)
                            <span class="px-3 py-1 bg-amber-500 text-white text-[9px] font-black uppercase rounded-full italic shadow-sm shadow-amber-500/20">Superadmin</span>
                        @endif
                        
                        @forelse($user->getRoleNames() as $role)
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-500/10 text-blue-600 text-[9px] font-black uppercase rounded-full italic border border-blue-200 dark:border-blue-500/20">
                                {{ str_replace('_', ' ', $role) }}
                            </span>
                        @empty
                            @if(!$user->is_admin)
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[9px] font-black uppercase rounded-full italic">No Role</span>
                            @endif
                        @endforelse
                    </div>
                </div>

                {{-- Khusus Superadmin: Form Checkbox Multi-Role --}}
                @if(auth()->user()->is_admin)
                <div class="pt-6 border-t border-slate-50 dark:border-white/5">
                    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-1">Konfigurasi Otoritas</label>
                        
                        <div class="space-y-2">
                            @foreach(['mahasiswa','asisten_praktikum','plp','kepala_lab','dosen'] as $role)
                            <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-railway-dark/50 rounded-xl cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-500/5 border border-slate-100 dark:border-railway-border transition-all group">
                                <input type="checkbox" name="roles[]" value="{{ $role }}" {{ $user->hasRole($role) ? 'checked' : '' }} 
                                       class="w-5 h-5 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500/20 transition-all">
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase italic group-hover:text-blue-600 transition-colors">
                                    {{ str_replace('_', ' ', $role) }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        <div class="my-6 h-px bg-gradient-to-r from-transparent via-slate-200 dark:via-railway-border to-transparent"></div>

                        <label class="flex items-center gap-3 p-4 bg-amber-500/5 dark:bg-amber-500/10 rounded-2xl cursor-pointer hover:bg-amber-500/10 transition-all border border-amber-500/20 shadow-inner">
                            <input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }} class="w-5 h-5 rounded-lg border-amber-500/30 text-amber-500 focus:ring-amber-500/20">
                            <span class="text-[10px] font-black text-amber-600 uppercase italic">Hak Akses Admin Utama</span>
                        </label>

                        <button type="submit" class="w-full py-4 bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all italic active:scale-95 mt-4">
                            Update Otoritas
                        </button>
                    </form>
                </div>
                @else
                <div class="p-4 bg-amber-500/5 border border-amber-500/20 rounded-2xl">
                    <p class="text-[10px] text-amber-600 font-black uppercase italic leading-relaxed text-center">
                        <i class="bi bi-exclamation-triangle mr-1"></i> Perubahan Role Hanya Dapat Dilakukan Oleh Superadmin
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Statistik Ringkas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-railway-card p-8 rounded-[2.5rem] border border-slate-100 dark:border-railway-border shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pinjaman Aktif</span>
                        <i class="bi bi-box-seam text-blue-500"></i>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-800 dark:text-white italic">{{ $user->peminjamans_count }}</span>
                        <span class="text-xs font-black text-slate-400 uppercase italic">Item Alat</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-railway-card p-8 rounded-[2.5rem] border border-slate-100 dark:border-railway-border shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Bebas Lab</span>
                        <i class="bi bi-shield-check {{ $user->bebas_lab ? 'text-emerald-500' : 'text-amber-500' }}"></i>
                    </div>
                    <span class="text-3xl font-black {{ $user->bebas_lab ? 'text-emerald-500' : 'text-amber-500' }} italic tracking-tighter">
                        {{ $user->bebas_lab ? 'CLEARED' : 'ON PROGRESS' }}
                    </span>
                </div>
            </div>

            {{-- Bagian Khusus Konten Lab --}}
            <div class="bg-white dark:bg-railway-card rounded-[2.5rem] border border-slate-100 dark:border-railway-border overflow-hidden shadow-sm">
                <div class="px-8 py-6 bg-slate-50/50 dark:bg-white/5 border-b border-slate-100 dark:border-railway-border flex justify-between items-center">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase italic tracking-widest">Informasi Lab & Inventori</h3>
                    
                    @if($user->hasRole('plp'))
                        <span class="text-[9px] bg-emerald-500 text-white px-3 py-1 rounded-full font-black italic uppercase">Petugas Lab</span>
                    @else
                        <span class="text-[9px] bg-slate-200 dark:bg-white/10 text-slate-500 px-3 py-1 rounded-full font-black italic uppercase">Bukan Petugas</span>
                    @endif
                </div>
                
                <div class="p-8">
                    @if($user->hasRole('plp') || auth()->user()->is_admin)
                        <div class="p-6 bg-blue-500/5 rounded-3xl border border-blue-500/10">
                            <p class="text-sm text-slate-600 dark:text-slate-400 italic">User ini memiliki akses pengelolaan laboratorium. Daftar alat yang dikelola akan tampil di sini...</p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bi bi-lock-fill text-4xl text-slate-200 mb-3 block"></i>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Akses Manajemen Inventori Terbatas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection