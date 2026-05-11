{{-- File: views/user/dashboard.blade.php --}}

<div class="max-w-7xl mx-auto px-4 py-6 antialiased">
    
    {{-- Header Sederhana --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                Halo, {{ Auth::user()->name }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                Selamat datang di Dashboard Mahasiswa Lab. Pemrograman & Komputasi
            </p>
        </div>
        <div class="hidden md:block">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg">
                {{ date('l, d M Y') }}
            </span>
        </div>
    </div>

    {{-- Statistik Cepat (Dibuat lebih compact) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Dipinjam</p>
            <div class="flex items-center gap-2">
                <span class="text-xl font-black text-blue-600 dark:text-blue-400">{{ $countPeminjamanAktif ?? 0 }}</span>
                <i class="bi bi-box-seam text-slate-300 dark:text-slate-700 text-sm"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Pending</p>
            <div class="flex items-center gap-2">
                <span class="text-xl font-black text-amber-500">{{ $countPending ?? 0 }}</span>
                <i class="bi bi-hourglass-split text-slate-300 dark:text-slate-700 text-sm"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Selesai</p>
            <div class="flex items-center gap-2">
                <span class="text-xl font-black text-emerald-500">{{ $countTotal ?? 0 }}</span>
                <i class="bi bi-check-circle-fill text-slate-300 dark:text-slate-700 text-sm"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm flex flex-col justify-center">
            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Bebas Lab</p>
            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-tight">✓ Aktif</span>
        </div>
    </div>

    {{-- Fitur Utama (Grid yang lebih rapat) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 transition-all hover:shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-blue-600 text-2xl group-hover:scale-110 transition-transform">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="flex-grow">
                <h5 class="text-base font-bold text-slate-900 dark:text-white">Peminjaman Alat</h5>
                <p class="text-xs text-slate-500 mb-3">Ajukan peminjaman alat TA/Praktikum.</p>
                <div class="flex gap-2">
                    <a href="{{ route('katalog.index') }}" class="px-3 py-1.5 bg-blue-600 text-white text-[9px] font-bold rounded-lg uppercase tracking-widest">Katalog</a>
                    <a href="{{ route('peminjaman.index') }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-[9px] font-bold rounded-lg uppercase tracking-widest">Riwayat</a>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-slate-900 border-l-4 border-l-emerald-500 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 transition-all hover:shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-emerald-600 text-2xl">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div class="flex-grow">
                <h5 class="text-base font-bold text-slate-900 dark:text-white">Bebas Lab (RSK)</h5>
                <p class="text-xs text-slate-500 mb-3">Cek tanggungan alat untuk yudisium.</p>
                <a href="{{ route('bebas-lab.form') }}" class="inline-block px-4 py-1.5 bg-emerald-600 text-white text-[9px] font-bold rounded-lg uppercase tracking-widest">Cek Status</a>
            </div>
        </div>

        <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 transition-all hover:shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-amber-500 text-2xl">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="flex-grow">
                <h5 class="text-base font-bold text-slate-900 dark:text-white">Dokumentasi SOP</h5>
                <p class="text-xs text-slate-500 mb-3">Prosedur dan keselamatan kerja.</p>
                <a href="{{ route('sop.index') }}" class="text-amber-600 font-bold text-[9px] uppercase tracking-widest hover:underline">Buka SOP →</a>
            </div>
        </div>

        <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 transition-all hover:shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex-shrink-0 flex items-center justify-center text-slate-500 text-2xl">
                <i class="bi bi-person-gear"></i>
            </div>
            <div class="flex-grow">
                <h5 class="text-base font-bold text-slate-900 dark:text-white">Pengaturan Akun</h5>
                <p class="text-xs text-slate-500 mb-3">Update profil & email institusi.</p>
                <a href="{{ route('profile.edit') }}" class="text-slate-400 font-bold text-[9px] uppercase tracking-widest hover:text-slate-600">Edit Profil</a>
            </div>
        </div>

    </div>
</div>