@extends('layouts.modern')

@section('content')
<div class="min-h-screen bg-transparent py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-2xl mx-auto">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-500/30 mb-4">
                <i class="bi bi-file-earmark-text text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                Pengajuan Surat Bebas Lab
            </h2>
            <p class="mt-2 text-slate-600 dark:text-slate-400 font-medium italic">
                Khusus mahasiswa FMIPA Universitas Tanjungpura
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-center p-4 text-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 rounded-2xl animate-pulse" role="alert">
                <i class="bi bi-check-circle-fill text-xl mr-3"></i>
                <div class="text-sm font-bold tracking-tight">
                    {{ session('success') }}
                </div>
                <button type="button" class="ml-auto hover:scale-110 transition-transform" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800/50 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700/50 backdrop-blur-sm rounded-[2rem] overflow-hidden">
            <div class="p-8 sm:p-10">
                <form action="{{ route('bebas-lab.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="group">
                        <label for="nama" class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" 
                               class="block w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-600" 
                               id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Filza Buana Putra" required>
                        @error('nama') <p class="mt-1 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label for="nim" class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2 ml-1">NIM</label>
                            <input type="text" 
                                   class="block w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-600 uppercase" 
                                   id="nim" name="nim" value="{{ old('nim') }}" placeholder="H10xxxxxx" required>
                            @error('nim') <p class="mt-1 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="group relative">
                            <label for="prodi" class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2 ml-1">Program Studi</label>
                            <div class="relative">
                                <select class="block w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none appearance-none cursor-pointer" 
                                        id="prodi" name="prodi" required>
                                    <option value="" class="dark:bg-slate-900" selected disabled>Pilih Prodi...</option>
                                    @foreach(['Rekayasa Sistem Komputer', 'Matematika', 'Sistem Informasi', 'Fisika', 'Geofisika', 'Biologi', 'S1 Kimia', 'S2 Kimia', 'Ilmu Kelautan', 'Statistika'] as $p)
                                        <option value="{{ $p }}" class="dark:bg-slate-900 dark:text-slate-200" {{ old('prodi') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 group-focus-within:text-blue-600">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </div>
                            @error('prodi') <p class="mt-1 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="group">
                        <label for="email" class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2 ml-1">Email Student Untan</label>
                        <div class="relative group-focus-within:scale-[1.01] transition-transform">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <input type="email" 
                                   class="block w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="h... @student.untan.ac.id" 
                                   pattern="[Hh][0-9]+@student\.untan\.ac\.id"
                                   oninvalid="this.setCustomValidity('Maaf, email harus menggunakan format NIM FMIPA (contoh: h10xxxxxx@student.untan.ac.id)')"
                                   oninput="this.setCustomValidity('')"
                                   required>
                        </div>
                        <p class="mt-3 text-[10px] text-slate-400 dark:text-slate-500 flex items-center italic">
                            <i class="bi bi-info-circle mr-1.5 text-blue-500 font-bold"></i> Tautan verifikasi otomatis akan dikirim ke email institusi Anda.
                        </p>
                        @error('email') <p class="mt-1 text-xs text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition-all shadow-xl shadow-blue-600/20 active:scale-[0.98]">
                            <i class="bi bi-send mr-2 text-sm"></i> Ajukan Bebas Lab
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-10 p-8 bg-slate-100/50 dark:bg-slate-800/30 rounded-[2rem] border border-slate-200 dark:border-slate-700/50 transition-all">
            <h5 class="flex items-center font-black text-slate-900 dark:text-slate-200 mb-6 uppercase tracking-wider text-xs">
                <i class="bi bi-diagram-3-fill text-blue-600 mr-2"></i> Prosedur Pengajuan
            </h5>
            <div class="grid grid-cols-1 gap-4">
                @foreach([
                    'Submit formulir dengan NIM & Email aktif.',
                    'Klik verifikasi di email student Anda.',
                    'Validasi peminjaman oleh staf PLP.',
                    'PDF Bebas Lab dikirim otomatis ke email.'
                ] as $index => $step)
                <div class="flex items-center space-x-4 group">
                    <span class="flex-shrink-0 w-6 h-6 bg-white dark:bg-slate-900 text-blue-600 border border-slate-200 dark:border-slate-700 rounded-lg text-[10px] flex items-center justify-center font-black shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        {{ $index + 1 }}
                    </span>
                    <p class="text-[13px] text-slate-600 dark:text-slate-400 font-medium tracking-tight">{{ $step }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection