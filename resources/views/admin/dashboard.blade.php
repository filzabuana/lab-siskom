{{-- File: views/admin/dashboard.blade.php --}}
{{-- Tanpa @extends karena sudah di-include di file induk --}}

<div class="max-w-7xl mx-auto px-4 py-6 sm:py-8 antialiased">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="space-y-1">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Panel Kendali Admin
                </h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 border border-blue-200 dark:border-blue-800 uppercase tracking-widest">
                    <i class="bi bi-shield-lock-fill me-1"></i> Admin Access
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                Lab. Pemrograman & Komputasi — FMIPA UNTAN
            </p>
        </div>
    </div>

    {{-- Row 1: Statistik Utama --}}
    <div class="grid grid-cols-3 gap-4 md:gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 md:p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-col md:flex-row items-center gap-3 md:gap-4 text-center md:text-left">
                <div class="w-10 h-10 md:w-14 md:h-14 flex items-center justify-center bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl transition-colors">
                    <i class="bi bi-file-earmark-text-fill text-xl md:text-2xl"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[9px] md:text-xs font-bold uppercase tracking-widest text-slate-400 mb-0.5">Total SOP</p>
                    <h5 class="text-lg md:text-2xl font-black text-slate-900 dark:text-white leading-none">
                        {{ \App\Models\Sop::count() }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 md:p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-col md:flex-row items-center gap-3 md:gap-4 text-center md:text-left">
                <div class="w-10 h-10 md:w-14 md:h-14 flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl">
                    <i class="bi bi-patch-check-fill text-xl md:text-2xl"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[9px] md:text-xs font-bold uppercase tracking-widest text-slate-400 mb-0.5">Surat Selesai</p>
                    <h5 class="text-lg md:text-2xl font-black text-slate-900 dark:text-white leading-none">
                        {{ \App\Models\SuratBebasLab::where('status', 'disetujui')->count() }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 md:p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-col md:flex-row items-center gap-3 md:gap-4 text-center md:text-left">
                <div class="w-10 h-10 md:w-14 md:h-14 flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-2xl">
                    <i class="bi bi-arrow-repeat text-xl md:text-2xl"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[9px] md:text-xs font-bold uppercase tracking-widest text-slate-400 mb-0.5">Total Pinjam</p>
                    <h5 class="text-lg md:text-2xl font-black text-slate-900 dark:text-white leading-none">
                        {{ \App\Models\Peminjaman::count() }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Menu Utama (Shortcut Cards) --}}
    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-6">Manajemen Layanan</h3>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
        
        <div class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-6 text-center transition-all hover:border-emerald-500/50 hover:shadow-2xl hover:shadow-emerald-500/10 active:scale-95">
            <div class="relative w-16 h-16 mx-auto mb-5 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center transition-transform group-hover:rotate-6">
                <i class="bi bi-person-check-fill text-emerald-600 text-3xl"></i>
                @php $notifBebasLab = \App\Models\SuratBebasLab::where('status', 'verified_email')->count(); @endphp
                @if($notifBebasLab > 0)
                    <span class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-4 ring-white dark:ring-slate-900 animate-bounce">
                        {{ $notifBebasLab }}
                    </span>
                @endif
            </div>
            <h6 class="font-bold text-slate-900 dark:text-white mb-4 text-sm tracking-tight">Bebas Lab</h6>
            <a href="{{ route('admin.bebas-lab.index') }}" class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all uppercase tracking-widest">
                Cek Berkas
            </a>
        </div>

        <div class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-6 text-center transition-all hover:border-amber-500/50 hover:shadow-2xl hover:shadow-amber-500/10 active:scale-95">
            <div class="relative w-16 h-16 mx-auto mb-5 bg-amber-50 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center transition-transform group-hover:-rotate-6">
                <i class="bi bi-cart-check-fill text-amber-600 text-3xl"></i>
                @php $notifPeminjaman = \App\Models\Peminjaman::where('status', 'pending')->count(); @endphp
                @if($notifPeminjaman > 0)
                    <span class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-4 ring-white dark:ring-slate-900">
                        {{ $notifPeminjaman }}
                    </span>
                @endif
            </div>
            <h6 class="font-bold text-slate-900 dark:text-white mb-4 text-sm tracking-tight">Peminjaman</h6>
            <a href="{{ route('admin.peminjaman.index') }}" class="block w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all uppercase tracking-widest">
                Kelola
            </a>
        </div>

        <div class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-6 text-center transition-all hover:border-cyan-500/50 hover:shadow-2xl hover:shadow-cyan-500/10 active:scale-95">
            <div class="w-16 h-16 mx-auto mb-5 bg-cyan-50 dark:bg-cyan-900/20 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                <i class="bi bi-boxes text-cyan-600 text-3xl"></i>
            </div>
            <h6 class="font-bold text-slate-900 dark:text-white mb-4 text-sm tracking-tight">Inventaris</h6>
            <a href="{{ route('admin.inventaris.index') }}" class="block w-full py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all uppercase tracking-widest">
                Buka Aset
            </a>
        </div>

        <div class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-6 text-center transition-all hover:border-blue-500/50 hover:shadow-2xl hover:shadow-blue-500/10 active:scale-95">
            <div class="w-16 h-16 mx-auto mb-5 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center transition-transform group-hover:rotate-12">
                <i class="bi bi-journal-plus text-blue-600 text-3xl"></i>
            </div>
            <h6 class="font-bold text-slate-900 dark:text-white mb-4 text-sm tracking-tight">Kelola SOP</h6>
            <a href="{{ route('sop.index') }}" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all uppercase tracking-widest">
                Update
            </a>
        </div>

        <div class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-6 text-center transition-all hover:border-rose-500/50 hover:shadow-2xl hover:shadow-rose-500/10 active:scale-95">
            <div class="w-16 h-16 mx-auto mb-5 bg-rose-50 dark:bg-rose-900/20 rounded-2xl flex items-center justify-center transition-transform group-hover:-translate-y-1">
                <i class="bi bi-people-fill text-rose-600 text-3xl"></i>
            </div>
            <h6 class="font-bold text-slate-900 dark:text-white mb-4 text-sm tracking-tight">User & Akun</h6>
            <a href="{{ route('admin.users.index') }}" class="block w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all uppercase tracking-widest">
                Manajemen
            </a>
        </div>

    </div>
</div>

<style>
    /* Utility tambahan untuk menghaluskan transisi */
    .group, .group i, .group a {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
</style>