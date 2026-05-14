@extends('layouts.modern')

@section('content')
@php
    // Logika Ekstraksi NIM & Prodi FMIPA UNTAN
    $emailPrefix = Str::before($user->email, '@');
    $isMahasiswa = preg_match('/^[Hh]\d+/', $emailPrefix);
    $nim = $isMahasiswa ? strtoupper($emailPrefix) : '-';
    
    /** 
     * Mapping Kode Program Studi FMIPA UNTAN 
     */
    $prodiCodes = [
        'H101' => 'Matematika',
        'H102' => 'Fisika',
        'H103' => 'Kimia',
        'H104' => 'Biologi',
        'H105' => 'Sistem Komputer',
        'H107' => 'Geofisika',
        'H108' => 'Statistika',
        'H109' => 'Sistem Informasi',
        'H110' => 'Ilmu Kelautan',
    ];

    $userProdi = 'Tenaga Kependidikan / Dosen';
    if ($isMahasiswa) {
        $subStr = substr($nim, 0, 4);
        $userProdi = $prodiCodes[$subStr] ?? 'Mahasiswa FMIPA';
    }

    // Perbaikan Logika Foto: Memastikan URL Google Valid
    $userPhoto = $user->avatar;
    if ($user->google_id && $userPhoto) {
        // Terkadang URL Google perlu dipaksa menggunakan HTTPS atau pembersihan parameter
        $userPhoto = str_replace('http://', 'https://', $userPhoto);
    }
    // Fallback jika tidak ada foto
    $fallbackAvatar = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff&size=256';
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-zinc-950 py-10">
    <div class="max-w-5xl mx-auto px-4">
        
        <!-- Header & Branding -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Profil Pengguna
                </h2>
                <p class="text-slate-500 dark:text-zinc-400 mt-1">Identitas resmi Anda dalam Sistem Informasi Laboratorium FMIPA UNTAN.</p>
            </div>
            
            {{-- Badge Status --}}
            <div class="inline-flex items-center px-4 py-2 {{ $user->is_admin ? 'bg-amber-500/10 border-amber-500/20 text-amber-600' : 'bg-blue-500/10 border-blue-500/20 text-blue-600' }} border rounded-2xl">
                <i class="bi {{ $user->is_admin ? 'bi-shield-lock-fill' : 'bi-mortarboard-fill' }} me-2"></i>
                <span class="text-sm font-bold">
                    @if($user->is_admin)
                        Administrator System
                    @elseif($isMahasiswa)
                        Mahasiswa Aktif
                    @else
                        Civitas Akademika
                    @endif
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Ringkasan Identitas -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] p-8 shadow-sm text-center">
                    <div class="relative inline-block">
                        <!-- Img dengan atribut referrer policy untuk mengatasi masalah pemblokiran gambar Google -->
                        <img src="{{ $userPhoto ?? $fallbackAvatar }}" 
                             referrerpolicy="no-referrer"
                             onerror="this.src='{{ $fallbackAvatar }}'"
                             class="w-32 h-32 rounded-[2rem] object-cover ring-4 ring-white dark:ring-zinc-800 shadow-xl mx-auto border border-slate-100 dark:border-white/10">
                        
                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 border-4 border-white dark:border-zinc-900 w-8 h-8 rounded-full flex items-center justify-center text-white">
                            <i class="bi bi-check-lg text-xs"></i>
                        </div>
                    </div>

                    <h3 class="mt-6 text-xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-zinc-500 mb-6">{{ $user->email }}</p>
                    
                    <div class="flex flex-col gap-2">
                        <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl text-left border border-slate-100 dark:border-white/5">
                            <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1 leading-none">NIM / Identitas</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300">{{ $nim }}</span>
                        </div>

                        @if($isMahasiswa)
                        <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl text-left border border-slate-100 dark:border-white/5">
                            <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1 leading-none">Program Studi</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300">{{ $userProdi }}</span>
                        </div>
                        @endif

                        <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl text-left border border-slate-100 dark:border-white/5">
                            <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1 leading-none">Metode Login</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-2">
                                @if($user->google_id)
                                    <i class="bi bi-google text-blue-500"></i> Google SSO
                                @else
                                    <i class="bi bi-person-lock text-slate-400"></i> Akun Manual
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-lg shadow-blue-500/20">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <i class="bi bi-shield-check"></i> Keamanan Data
                    </h4>
                    <p class="text-xs text-blue-100 leading-relaxed">
                        Data identitas disinkronkan secara otomatis. @if(!$user->google_id) Gunakan fitur ganti password secara berkala untuk menjaga keamanan akun manual Anda. @else Akun Google Anda dikelola secara eksternal. @endif
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan: Informasi & Password -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section: View Profile (Read-Only) -->
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] overflow-hidden shadow-sm">
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-person-badge text-xl"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">Detail Identitas</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-500 outline-none cursor-not-allowed" value="{{ $user->name }}" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">NIM</label>
                                <input type="text" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-500 outline-none cursor-not-allowed" value="{{ $nim }}" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Program Studi</label>
                                <input type="text" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-500 outline-none cursor-not-allowed" value="{{ $userProdi }}" readonly>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Email Institusi</label>
                                <input type="email" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-500 outline-none cursor-not-allowed" value="{{ $user->email }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Keamanan (Hanya muncul jika TIDAK login dengan Google) -->
                @if(!$user->google_id)
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] overflow-hidden shadow-sm">
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                                <i class="bi bi-key text-xl"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">Ubah Kata Sandi</h4>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('put')
                            
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Password Saat Ini</label>
                                <input type="password" name="current_password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none" required>
                                @error('current_password', 'updatePassword')
                                    <span class="text-xs text-rose-500 mt-2 ml-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Password Baru</label>
                                    <input type="password" name="password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none" required>
                                    @error('password', 'updatePassword')
                                        <span class="text-xs text-rose-500 mt-2 ml-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none" required>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="px-8 py-3.5 bg-slate-900 dark:bg-white dark:text-zinc-950 text-white font-bold rounded-2xl hover:opacity-90 transition-all shadow-lg active:scale-95">
                                    Simpan Perubahan Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <!-- Info untuk pengguna Google -->
                <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-[2rem] p-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex-shrink-0 flex items-center justify-center">
                        <i class="bi bi-google"></i>
                    </div>
                    <div>
                        <h5 class="text-emerald-800 dark:text-emerald-400 font-bold text-sm">Akun Terhubung dengan Google</h5>
                        <p class="text-emerald-700/60 dark:text-emerald-400/60 text-xs mt-1 leading-relaxed">
                            Pengaturan kata sandi dilakukan melalui panel keamanan akun Google Anda. Kata sandi tidak dapat diubah dari sistem laboratorium ini demi keamanan integrasi SSO.
                        </p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection