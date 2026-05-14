@extends('layouts.modern')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" 
           class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-none">Registrasi User</h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic mt-1">Sistem Manajemen Identitas Laboratorium</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-railway-card rounded-[2.5rem] p-8 md:p-12 border border-slate-100 dark:border-white/5 shadow-2xl shadow-slate-200/50 dark:shadow-none relative overflow-hidden">
        {{-- Decorative Glow --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl"></div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8 relative">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama & Email --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full bg-slate-50 dark:bg-white/[0.03] border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 dark:text-white transition-all outline-none">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Email Institusi</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="user@student.untan.ac.id"
                        class="w-full bg-slate-50 dark:bg-white/[0.03] border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 dark:text-white transition-all outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Password Keamanan</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-slate-50 dark:bg-white/[0.03] border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 dark:text-white transition-all outline-none">
            </div>

            {{-- Bagian Multi-Role Checkbox --}}
            <div class="space-y-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Role Laboratorium (Dapat pilih lebih dari satu)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($roles as $role)
                    <label class="group flex items-center gap-3 p-4 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-2xl cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-500/5 transition-all">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                               {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}
                               class="w-5 h-5 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500/20 transition-all">
                        <span class="text-[11px] font-black text-slate-600 dark:text-slate-300 uppercase italic group-hover:text-blue-600">
                            {{ str_replace('_', ' ', $role->name) }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('roles')
                    <span class="text-[10px] text-rose-500 font-bold italic ml-2">{{ $message }}</span>
                @enderror
            </div>

            {{-- Privilege Khusus: Superadmin --}}
            <div class="p-6 bg-amber-500/5 border border-amber-500/10 rounded-[2rem] flex items-center gap-4 transition-all hover:bg-amber-500/10 group">
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                </div>
                <label for="is_admin" class="flex flex-col cursor-pointer">
                    <span class="text-[10px] font-black text-amber-600 uppercase italic tracking-widest group-hover:tracking-[0.15em] transition-all">Akses Superadmin</span>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-zinc-400">Berikan hak akses administrator utama (Root).</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-slate-900 dark:bg-blue-600 text-white text-xs font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl hover:bg-blue-600 transition-all italic active:scale-95 shadow-blue-500/20">
                    Simpan Data Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection