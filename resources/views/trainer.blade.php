@extends('layouts.simulator')

@section('content')
{{-- Container utama yang memaksa latar belakang gelap dan font sans-serif bersih --}}
<div class="min-h-screen flex flex-col bg-slate-900 font-sans antialiased text-slate-200">
    
    <header class="bg-slate-800/50 backdrop-blur-md border-b border-slate-700 p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold text-white tracking-tight leading-none">LogicGate <span class="text-blue-400">Trainer</span></h1>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 mt-1">Lab Pemrograman & Komputasi Untan</p>
                </div>
            </div>
            <nav>
                <a href="/apps" class="group flex items-center space-x-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                    <span>Kembali</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-6xl mx-auto">
            
            <div id="" class="relative">
                {{-- Glow effect --}}
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-20"></div>
                
                <div class="relative bg-slate-800 rounded-2xl shadow-2xl overflow-hidden border border-slate-700">
                    <div class="p-6">
                        <virtual-trainer></virtual-trainer>
                    </div>
                </div>
            </div>

        </div>
    </main>

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