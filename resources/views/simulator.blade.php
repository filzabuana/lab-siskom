@extends('layouts.simulator')

@section('content')
{{-- Container utama yang memaksa latar belakang gelap dan font sans-serif bersih --}}
<div class="min-h-screen flex flex-col bg-slate-900 font-sans antialiased text-slate-200">
    
    <!-- Header Minimalis -->
    <header class="bg-slate-800/50 backdrop-blur-md border-b border-slate-700 p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold text-white tracking-tight leading-none">LogicGate <span class="text-blue-400">Sim</span></h1>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 mt-1">Lab Pemrograman & Komputasi Untan</p>
                </div>
            </div>
            <nav>
                <a href="/" class="group flex items-center space-x-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                    <span>Kembali ke Beranda</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </nav>
        </div>
    </header>

    <!-- Area Utama Simulator -->
    <main class="flex-grow flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-4xl mx-auto">
            
            <!-- Wrapper untuk Komponen Vue agar tidak melebar liar -->
            <div id="app" class="relative">
    {{-- Glow effect --}}
    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-20"></div>
    
    <div class="relative bg-slate-800 rounded-2xl shadow-2xl overflow-hidden border border-slate-700">
        
        <div class="flex border-b border-slate-700 bg-slate-900/50">
            <button 
                @click="activeTab = 'basic'" 
                :class="activeTab === 'basic' ? 'text-blue-400 border-b-2 border-blue-500 bg-slate-800' : 'text-slate-500 hover:text-slate-300'"
                class="px-6 py-3 text-xs font-bold uppercase tracking-widest transition-all outline-none">
                Single Gate Mode
            </button>
            <button 
                @click="activeTab = 'hbe'" 
                :class="activeTab === 'hbe' ? 'text-blue-400 border-b-2 border-blue-500 bg-slate-800' : 'text-slate-500 hover:text-slate-300'"
                class="px-6 py-3 text-xs font-bold uppercase tracking-widest transition-all outline-none">
                Combined Gate Mode
            </button>
        </div>

        <div class="p-4">
            <logic-gate v-if="activeTab === 'basic'"></logic-gate>
            <hbe-trainer v-if="activeTab === 'hbe'"></hbe-trainer>
        </div>

    </div>
</div>

            <!-- Area Instruksi Sederhana -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-800/40 p-6 rounded-xl border border-slate-700/50 hover:border-blue-500/50 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center mb-4 group-hover:bg-blue-500/20">
                        <span class="text-blue-400 font-bold">01</span>
                    </div>
                    <h3 class="text-white font-bold mb-2">Input Sinyal</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Klik tombol input untuk mengubah status biner. Hijau berarti <span class="text-green-400 font-mono">High (1)</span> dan Abu berarti <span class="text-slate-500 font-mono">Low (0)</span>.</p>
                </div>

                <div class="bg-slate-800/40 p-6 rounded-xl border border-slate-700/50 hover:border-purple-500/50 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center mb-4 group-hover:bg-purple-500/20">
                        <span class="text-purple-400 font-bold">02</span>
                    </div>
                    <h3 class="text-white font-bold mb-2">Proses Logika</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Gerbang memproses sinyal secara real-time. Perhatikan perubahan warna pada simbol gerbang saat input berubah.</p>
                </div>

                <div class="bg-slate-800/40 p-6 rounded-xl border border-slate-700/50 hover:border-green-500/50 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center mb-4 group-hover:bg-green-500/20">
                        <span class="text-green-400 font-bold">03</span>
                    </div>
                    <h3 class="text-white font-bold mb-2">Output Hasil</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Lampu indikator akan menyala jika kondisi gerbang terpenuhi sesuai dengan <span class="italic text-slate-300">Truth Table</span>.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 p-6 text-center">
        <div class="flex flex-col items-center justify-center space-y-2">
            <div class="flex space-x-4 mb-2">
                <div class="h-1 w-8 bg-blue-500 rounded-full"></div>
                <div class="h-1 w-8 bg-slate-700 rounded-full"></div>
                <div class="h-1 w-8 bg-slate-700 rounded-full"></div>
            </div>
            <p class="text-xs text-slate-500 font-medium">
                &copy; 2026 Programming & Computing Laboratory.
            </p>
            <p class="text-[10px] text-slate-600 uppercase tracking-[0.2em]">
                Universitas Tanjungpura
            </p>
        </div>
    </footer>
</div>
@endsection