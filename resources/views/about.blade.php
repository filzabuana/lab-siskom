@extends('layouts.modern')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    
    <div class="text-center mb-16 space-y-4">
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter">
            Tentang Kami
        </h2>
        <p class="max-w-3xl mx-auto text-sm md:text-base text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
            Laboratorium Pemrograman & Komputasi Program Studi <span class="text-slate-900 dark:text-white font-bold">Rekayasa Sistem Komputer</span> Universitas Tanjungpura berfokus pada pengembangan teknologi berbasis 
            <span class="text-blue-600 font-bold uppercase tracking-tighter">Automation & Embedded System (AES)</span> dan <span class="text-blue-600 font-bold uppercase tracking-tighter">Network Intelligent Control (NIC)</span>.
        </p>
        <div class="mx-auto w-16 h-1 bg-blue-600 rounded-full shadow-lg shadow-blue-500/50"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20 max-w-5xl mx-auto">
        
        <div class="group relative bg-white dark:bg-railway-card border border-slate-100 dark:border-railway-border rounded-[2.5rem] shadow-xl overflow-hidden transition-all hover:-translate-y-2">
            <div class="h-32 bg-slate-50 dark:bg-white/[0.02]"></div>
            <div class="px-8 pb-10 -mt-16 text-center">
                <div class="inline-block relative">
                    <div class="w-32 h-32 rounded-full border-4 border-white dark:border-railway-card shadow-2xl overflow-hidden bg-slate-200">
                        <img src="{{ asset('images/suhardi.png') }}" alt="Suhardi" class="w-100 h-100 object-cover">
                    </div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 bg-blue-500 rounded-full border-2 border-white dark:border-railway-card"></div>
                </div>
                
                <h3 class="mt-4 text-lg font-black text-slate-900 dark:text-white uppercase italic tracking-tight">
                    Suhardi, S.T., M.Eng.
                </h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">NIP 198606182020121006</p>
                <p class="text-[11px] font-black text-blue-600 uppercase italic tracking-wider mb-4">Kepala Laboratorium</p>
                
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-8 px-4 font-medium italic">
                    "Dosen Program Studi Rekayasa Sistem Komputer dengan fokus pada Automation & Embedded System dan IoT."
                </p>
                
                <div class="flex justify-center gap-4">
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-white/[0.05] text-slate-400 hover:text-pink-500 transition-colors">
                        <i class="bi bi-instagram text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-white/[0.05] text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="bi bi-linkedin text-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="group relative bg-white dark:bg-railway-card border border-slate-100 dark:border-railway-border rounded-[2.5rem] shadow-xl overflow-hidden transition-all hover:-translate-y-2">
            <div class="h-32 bg-slate-50 dark:bg-white/[0.02]"></div>
            <div class="px-8 pb-10 -mt-16 text-center">
                <div class="inline-block relative">
                    <div class="w-32 h-32 rounded-full border-4 border-white dark:border-railway-card shadow-2xl overflow-hidden bg-slate-200">
                        <img src="{{ asset('images/filza.jpg') }}" alt="Filza Buana Putra" class="w-100 h-100 object-cover">
                    </div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-500 rounded-full border-2 border-white dark:border-railway-card"></div>
                </div>
                
                <h3 class="mt-4 text-lg font-black text-slate-900 dark:text-white uppercase italic tracking-tight">
                    Filza Buana Putra, S.Mat.
                </h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">NIP 199611192025061007</p>
                <p class="text-[11px] font-black text-emerald-500 uppercase italic tracking-wider mb-4">Pranata Laboratorium Pendidikan (PLP)</p>
                
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-8 px-4 font-medium italic">
                    "Mengelola operasional harian, perawatan fasilitas, dan pendampingan teknis praktikum mahasiswa."
                </p>
                
                <div class="flex justify-center gap-4">
                    <a href="https://www.instagram.com/filzabuana" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-white/[0.05] text-slate-400 hover:text-pink-500 transition-colors">
                        <i class="bi bi-instagram text-lg"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/filza-buana-putra-45a5a41a3" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-white/[0.05] text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="bi bi-linkedin text-lg"></i>
                    </a>
                    <a href="https://github.com/filzabuana" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-white/[0.05] text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <i class="bi bi-github text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-20">
        <div class="bg-slate-900 dark:bg-railway-card rounded-[3rem] p-8 md:p-16 relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h3 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                        Visi & Misi Lab
                    </h3>
                    <div class="space-y-4 text-slate-300 text-sm md:text-base leading-relaxed font-medium">
                        <p>
                            Laboratorium Pemrograman & Komputasi merupakan unit pendukung akademik di lingkungan Program Studi Rekayasa Sistem Komputer, FMIPA, UNTAN. Kami berdedikasi untuk menciptakan ekosistem pembelajaran yang adaptif terhadap kemajuan teknologi global.
                        </p>
                        <p>
                            Menjadi pusat unggulan dalam pengembangan sistem kontrol cerdas dan otomasi yang memberikan kontribusi nyata melalui pendidikan dan penelitian.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex flex-shrink-0 items-center justify-center text-white shadow-lg shadow-blue-500/20">
                                <i class="bi bi-cpu text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-white uppercase italic tracking-wider">AES Focus</h4>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Pengembangan sistem tertanam, robotika, dan otomasi industri berbasis IoT.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-cyan-500 rounded-xl flex flex-shrink-0 items-center justify-center text-white shadow-lg shadow-cyan-500/20">
                                <i class="bi bi-diagram-3 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-white uppercase italic tracking-wider">NIC Focus</h4>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Manajemen jaringan cerdas, keamanan data, dan kontrol sistem jarak jauh.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch max-w-5xl mx-auto">
        <div class="lg:col-span-7 p-8 bg-white dark:bg-railway-card border border-slate-100 dark:border-railway-border rounded-[2.5rem] shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-geo-alt-fill text-red-500 text-xl"></i>
                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Lokasi Lab</h4>
            </div>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Gedung Sistem Komputer, FMIPA UNTAN</p>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Jl. Prof. Dr. H. Hadari Nawawi, Bansir Laut, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat.</p>
        </div>

        <div class="lg:col-span-5 p-8 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-railway-border rounded-[2.5rem] shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <i class="bi bi-clock-fill text-blue-600 text-xl"></i>
                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Work Hours</h4>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center text-[11px] font-bold">
                    <span class="text-slate-500 uppercase tracking-tighter">Senin - Kamis</span>
                    <span class="text-slate-900 dark:text-white">07:30 - 16:00 WIB</span>
                </div>
                <div class="flex justify-between items-center text-[11px] font-bold">
                    <span class="text-slate-500 uppercase tracking-tighter">Jumat</span>
                    <span class="text-slate-900 dark:text-white">07:30 - 16:30 WIB</span>
                </div>
                <div class="pt-3 border-t border-slate-200 dark:border-white/5 flex justify-between items-center text-[11px] font-black uppercase italic">
                    <span class="text-red-500">Sabtu - Minggu</span>
                    <span class="px-2 py-0.5 bg-red-100 dark:bg-red-500/10 text-red-600 rounded">Closed</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection