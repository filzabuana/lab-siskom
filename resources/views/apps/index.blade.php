<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual Lab - Lab Pemrograman & Komputasi</title>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    {{-- Tetap panggil Vite untuk Tailwind-nya --}}
    @vite(['resources/css/app.css', 'resources/js/app-blade.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #020617; 
            margin: 0; 
            overflow-x: hidden; 
        }

        /* Menghilangkan scrollbar tapi tetap bisa scroll */
        ::-webkit-scrollbar { width: 0px; }

        /* Memastikan Canvas benar-benar di dasar paling bawah */
        #starCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background-color: #020617;
            pointer-events: none;
        }

        /* Overlay Gradasi di atas canvas tapi di bawah konten */
        .bg-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to bottom, transparent, rgba(2, 6, 23, 0.3), #020617);
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 10; 
        }

        /* --- STYLES UNTUK ROBOT FOLO --- */
        .robot-float { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .eye-blink { transform-origin: center; animation: blink 4s infinite; }
        @keyframes blink {
            0%, 45%, 47%, 100% { transform: scaleY(1); }
            46% { transform: scaleY(0.1); }
        }

        @keyframes waving {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-35deg); }
            75% { transform: rotate(10deg); }
        }

        .is-waving #left-arm { animation: waving 0.5s infinite ease-in-out !important; }

        /* --- FIXED SPEECH BUBBLE --- */
        #speech-bubble {
    position: absolute;
    bottom: 115%; /* Sedikit lebih tinggi agar tidak menempel kepala */
    left: 50%;
    transform: translateX(-50%) scale(0);
    transform-origin: bottom center;
    
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    
    padding: 12px 16px; /* Padding lebih lega */
    
    /* --- PERUBAHAN UTAMA DI SINI --- */
    width: 240px; /* Ukuran lebar tetap yang ideal */
    max-width: 80vw; /* Agar tidak meluap di layar HP yang sangat kecil */
    /* ------------------------------- */
    
    border-radius: 12px 20px 12px 20px;
    border: 1px solid rgba(56, 189, 248, 0.5);
    
    font-size: 12px; /* Sedikit lebih besar agar enak dibaca */
    line-height: 1.5;
    font-weight: 600;
    color: #f1f5f9;
    text-align: center;
    
    white-space: normal; 
    word-wrap: break-word;
    
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 50;
    pointer-events: none;
}

/* Penyesuaian untuk Mobile agar tidak terlalu lebar */
@media (max-width: 768px) {
    #speech-bubble {
        width: 180px; 
        font-size: 11px;
        bottom: 110%;
    }
}

        /* Dekorasi Siku Biru */
        #speech-bubble::before {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            width: 6px;
            height: 6px;
            border-top: 1.5px solid #38bdf8;
            border-left: 1.5px solid #38bdf8;
        }

        /* Panah Bawah Futuristik */
        #speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid rgba(56, 189, 248, 0.5);
        }

        #speech-bubble.active {
            transform: translateX(-50%) scale(1);
        }

        /* Animasi teks berkedip halus saat muncul */
        #speech-bubble.active span {
            animation: text-flicker 2s linear infinite;
        }

        @keyframes text-flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        #typed-text {
    display: inline-block;
    white-space: normal; /* Sangat penting agar teks tetap turun ke bawah */
    word-break: break-word;
}

/* Hilangkan animasi flicker manual kita tadi karena sudah diganti efek mengetik */
#speech-bubble.active span {
    animation: none;
}
    </style>
</head>
<body class="antialiased text-slate-200">

    {{-- 1. Layer Background --}}
    <canvas id="starCanvas"></canvas>
    <div class="bg-overlay"></div>

    {{-- 2. Layer Konten --}}
    <div class="content-wrapper flex flex-col min-h-screen">
        
        {{-- Header --}}
        <header class="p-6 md:p-10">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-blue-600 rounded-2xl rotate-3 flex items-center justify-center shadow-[0_0_25px_rgba(37,99,235,0.4)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white -rotate-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m0 0l-2-1m2 1v2.5M14 4l-2 1m0 0l-2-1m2 1V7M4 7l2 1m0 0l2-1m2 1v2.5M4 14l2 1m0 0l-2-1m2 1v2.5M14 19l-2 1m0 0l-2-1m2 1v2.5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tighter text-white italic leading-none">Prog<span class="text-blue-500">Comp</span></h1>
                        <p class="text-[9px] tracking-[0.3em] text-blue-400 font-bold uppercase italic mt-1">Interactive Simulation Hub</p>
                    </div>
                </div>
                
                <a href="/" class="group flex items-center gap-3 px-6 py-2.5 rounded-full border border-slate-700 text-[10px] font-black tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    Ke Portal Utama
                </a>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-grow flex items-center px-6 py-10 md:py-12">
            <div class="container mx-auto">
                <div class="max-w-6xl mx-auto">
                    
                    {{-- Hero Section --}}
                    <div class="mb-12">
                        <h2 class="text-5xl md:text-7xl font-black text-white leading-tight tracking-tighter mb-6">
                            Virtual <br>
                            <span class="text-blue-600 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-400 italic">
                                Laboratory.
                            </span>
                        </h2>
                        
                        <div class="flex flex-col md:flex-row md:items-center gap-6">
                            <p class="text-slate-500 text-sm md:text-base max-w-lg font-medium leading-relaxed tracking-tight">
                                Akses lingkungan simulasi sistem komputer, arsitektur logika, dan pengujian perangkat digital secara virtual untuk praktikum Laboratorium Pemrograman dan Komputasi FMIPA UNTAN.
                            </p>
                            <span class="hidden md:block h-8 w-[1px] bg-slate-800"></span>
                            <div class="flex items-center gap-2 text-slate-400 font-bold text-[10px] tracking-widest italic uppercase bg-slate-900/50 px-3 py-1.5 rounded-lg border border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                PC Recommended
                            </div>

                            <!-- CONTAINER ROBOT DI TENGAH (MOBILE) & KANAN ATAS (PC) -->
                    <div class="flex justify-center items-center py-8 relative md:static">
                        <div id="robot-container" class="w-[120px] md:w-[220px] md:absolute md:top-[220px] md:right-[20%] relative cursor-pointer" onclick="handleSpeechAction()">
                           <div id="speech-bubble">
                                <span id="typed-text"></span>
                            </div>
                            <div class="robot-float">
                                <svg viewBox="0 0 800 1000" class="w-full h-auto drop-shadow-2xl">
                                    <g id="head">
                                        <rect x="180" y="80" width="440" height="300" rx="110" fill="#FACC15" stroke="#111" stroke-width="12"/>
                                        <rect x="220" y="120" width="360" height="220" rx="70" fill="#111827"/>
                                        <g class="eye-blink">
                                            <ellipse cx="330" cy="230" rx="45" ry="65" fill="#60A5FA" />
                                            <ellipse cx="470" cy="230" rx="45" ry="65" fill="#60A5FA" />
                                        </g>
                                    </g>
                                    <rect x="270" y="370" width="260" height="220" rx="40" fill="#FACC15" stroke="#111" stroke-width="10"/>
                                    <g id="left-arm" style="transform-origin: 270px 420px;">
                                        <circle cx="270" cy="420" r="35" fill="#111"/>
                                        <rect x="130" y="395" width="140" height="45" rx="22" fill="#FACC15" stroke="#111" stroke-width="8"/>
                                    </g>
                                    <g style="transform-origin: 530px 420px;">
                                        <circle cx="530" cy="420" r="35" fill="#111"/>
                                        <rect x="530" y="395" width="140" height="45" rx="22" fill="#FACC15" stroke="#111" stroke-width="8" transform="rotate(15 530 395)"/>
                                    </g>
                                    <g id="legs">
                                        <rect x="230" y="590" width="100" height="200" rx="50" fill="#FACC15" stroke="#111" stroke-width="10"/>
                                        <rect x="470" y="590" width="100" height="200" rx="50" fill="#FACC15" stroke="#111" stroke-width="10"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>

                        </div>
                    </div>


                    {{-- Grid Modul --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
                        {{-- Modul 1: Gerbang Logika --}}
                        <a href="/simulasigerbanglogika" class="group relative block">
                            <div class="relative overflow-hidden rounded-[2rem] bg-slate-900/40 backdrop-blur-3xl border border-white/5 p-2 transition-all duration-500 hover:border-blue-500/50 hover:shadow-[0_0_40px_rgba(37,99,235,0.15)] group-hover:-translate-y-2">
                                <div class="relative h-56 overflow-hidden rounded-[1.7rem] bg-slate-950">
                                    <img src="{{ asset('images/logic-gate.png') }}" class="w-full h-full object-cover opacity-40 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700" alt="Preview Gerbang Logika">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                                    <div class="absolute bottom-5 left-6">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping"></span>
                                            <span class="text-blue-400 text-[9px] font-bold uppercase tracking-widest">Sistem Komputer</span>
                                        </div>
                                        <h3 class="text-3xl font-black text-white italic tracking-tighter uppercase">Gerbang Logika</h3>
                                    </div>
                                </div>
                                <div class="p-6 flex justify-between items-center">
                                    <div class="space-y-0.5">
                                        <p class="text-white text-xs font-bold italic">Logic Gate Designer</p>
                                        <p class="text-slate-500 text-[9px] font-semibold tracking-wide uppercase">Simulasi Arsitektur Digital</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full border border-slate-700 flex items-center justify-center group-hover:bg-blue-600 group-hover:border-blue-600 transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        {{-- Modul 2: Trainer Digital --}}
                        <a href="/trainer-digital" class="group relative block">
                            <div class="relative overflow-hidden rounded-[2rem] bg-slate-900/40 backdrop-blur-3xl border border-white/5 p-2 transition-all duration-500 hover:border-emerald-500/50 hover:shadow-[0_0_40px_rgba(16,185,129,0.15)] group-hover:-translate-y-2">
                                <div class="relative h-56 overflow-hidden rounded-[1.7rem] bg-slate-950">
                                    <img src="{{ asset('images/trainer-digital.png') }}" class="w-full h-full object-cover opacity-40 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700" alt="Preview Trainer">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                                    <div class="absolute bottom-5 left-6">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            <span class="text-emerald-400 text-[9px] font-bold uppercase tracking-widest">Hardware Simulation</span>
                                        </div>
                                        <h3 class="text-3xl font-black text-white italic tracking-tighter uppercase">Virtual Trainer</h3>
                                    </div>
                                </div>
                                <div class="p-6 flex justify-between items-center">
                                    <div class="space-y-0.5">
                                        <p class="text-white text-xs font-bold italic">HBE-Combo Virtual</p>
                                        <p class="text-slate-500 text-[9px] font-semibold tracking-wide uppercase">Integrasi Komponen Praktikum</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full border border-slate-700 flex items-center justify-center group-hover:bg-emerald-600 group-hover:border-emerald-600 transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        {{-- 3. Section Logo (Instansi & Tech Stack) --}}
        <section class="pt-20 pb-10">
            <div class="container mx-auto px-6">
                <div class="max-w-5xl mx-auto flex flex-col items-center">
                    {{-- Instansi --}}
                    <div class="flex flex-wrap justify-center items-center gap-10 md:gap-20 mb-14 grayscale opacity-60 hover:opacity-100 transition-all duration-700">
                        <img src="{{ asset('images/untan.svg') }}" alt="UNTAN" class="h-16 w-auto">
                        <img src="{{ asset('images/diktisaintek.svg') }}" alt="dikti" class="h-16 w-auto">
                        <img src="{{ asset('images/tut.svg') }}" alt="Tut Wuri Handayani" class="h-16 w-auto">
                    </div>

                    <div class="w-32 h-[1px] bg-gradient-to-r from-transparent via-slate-800 to-transparent mb-14"></div>

                    {{-- Tech Stack --}}
                    <div class="flex flex-wrap justify-center items-center gap-12 md:gap-16 grayscale opacity-40 hover:opacity-100 transition-all duration-700">
                        <div class="flex items-center gap-3">
                            <svg class="h-7 w-auto text-white" viewBox="0 0 50 52" xmlns="http://www.w3.org/2000/svg">
                                <title>Logomark</title>
                                <path d="M49.626 11.564a.809.809 0 0 1 .028.209v10.972a.801.801 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 0 1 0 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216l17.62-10.144zM1.602 7.719v31.068L19.22 48.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965 9.654 1.602 7.72zm8.81-5.994L2.405 6.334l8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764l4.645-2.674V7.719l-3.363 1.936-4.646 2.675v20.096l3.364-1.937zM39.243 7.164l-8.006 4.609 8.006 4.609 8.005-4.61-8.005-4.608zm-.801 10.605l-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937v-9.124zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833 7.993 4.524z" fill="currentColor" fill-rule="evenodd"/>
                            </svg>
                            <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase">Laravel</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-auto" viewBox="0 0 261.76 226.69" xmlns="http://www.w3.org/2000/svg">
                                <g transform="matrix(1.3333 0 0 -1.3333 -76.311 313.34)">
                                    <g transform="translate(178.06 235.01)">
                                        <path d="m0 0-22.669-39.264-22.669 39.264h-75.491l98.16-170.02 98.16 170.02z" 
                                              fill="#FFFFFF"/>
                                    </g>
                                    <g transform="translate(178.06 235.01)">
                                        <path d="m0 0-22.669-39.264-22.669 39.264h-36.227l58.896-102.01 58.896 102.01z" 
                                              fill="#475569"/>
                                    </g>
                                </g>
                            </svg>
                            <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase">Vue.js</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-auto text-[#38BDF8]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8 1.123.28 1.926 1.096 2.814 1.998C14.457 11.263 15.823 12.65 19.2 12.65c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-1.123-.28-1.926-1.096-2.814-1.998-1.442-1.464-2.808-2.852-6.185-2.852zm-7.2 7.85c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8 1.123.28 1.926 1.096 2.814 1.998 1.442 1.464 2.808 2.852 6.185 2.852 3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-1.123-.28-1.926-1.096-2.814-1.998-1.442-1.464-2.808-2.852-6.185-2.852z"/>
                            </svg>
                            <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase">Tailwind</span>
                        </div>
                        {{-- Vite --}}
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-auto" viewBox="0 0 256 257" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                                <defs>
                                    <linearGradient x1="12.052%" y1="18.591%" x2="100%" y2="100%" id="viteGradient">
                                        <stop stop-color="#41D1FF" offset="0%"/>
                                        <stop stop-color="#BD34FE" offset="100%"/>
                                    </linearGradient>
                                    <linearGradient x1="19.502%" y1="53.161%" x2="65.304%" y2="58.217%" id="viteBolt">
                                        <stop stop-color="#FFEA83" offset="0%"/>
                                        <stop stop-color="#FFDD35" offset="100%"/>
                                    </linearGradient>
                                </defs>
                                {{-- Bentuk V --}}
                                <path d="M255.859 37.818l-90.871 213.125a12.8 12.8 0 01-23.702.046L51.059 37.82a12.8 12.8 0 0118.583-15.704l50.324 29.356a12.8 12.8 0 0015.748-1.226L185.908 4.75a12.8 12.8 0 0121.225 11.233l-5.65 31.831a12.8 12.8 0 0010.59 14.862l32.227 5.176a12.8 12.8 0 0111.56 10.166z" fill="url(#viteGradient)"/>
                                {{-- Simbol Petir --}}
                                <path d="M152.074 7.53L115.55 77.53a6.4 6.4 0 005.666 9.356h24.542a6.4 6.4 0 015.526 9.626L102.53 175.5a6.4 6.4 0 0010.556 7.426l82.124-105a6.4 6.4 0 00-5.042-10.344h-24.542a6.4 6.4 0 01-5.526-9.626l47.412-90.86a6.4 6.4 0 00-10.438-7.566z" fill="url(#viteBolt)"/>
                            </svg>
                            <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase">Vite</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="p-12 border-t border-white/5 bg-slate-950/40 backdrop-blur-md">
            <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[9px] text-slate-600 font-black uppercase tracking-[0.5em]">Lab Pemrograman & Komputasi &bull; FMIPA UNTAN</p>
                <div class="flex gap-6">
                    <span class="text-[9px] text-blue-900 font-bold tracking-widest uppercase italic">Virtual Laboratory Hub</span>
                    <span class="text-[9px] text-slate-700 font-bold tracking-widest uppercase">Versi 1.0.4-Build</span>
                </div>
            </div>
        </footer>
    </div> {{-- Penutup content-wrapper --}}

    <script>
    // --- KONFIGURASI CANVAS ---
    const canvas = document.getElementById('starCanvas');
    const ctx = canvas.getContext('2d');
    let particles = [];
    
    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        initParticles();
    }

    function initParticles() {
        particles = [];
        const count = Math.floor((canvas.width * canvas.height) / 12000); 
        for (let i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 1.5 + 0.5,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4,
                opacity: Math.random(),
                blinkSpeed: Math.random() * 0.02 + 0.005
            });
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach((p, i) => {
            p.x += p.vx;
            p.y += p.vy;
            p.opacity += p.blinkSpeed;
            if (p.opacity > 1 || p.opacity < 0.1) p.blinkSpeed *= -1;
            if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
            if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${Math.abs(p.opacity) * 0.5})`;
            ctx.fill();

            for (let j = i + 1; j < particles.length; j++) {
                const p2 = particles[j];
                const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                if (dist < 150) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(37, 99, 235, ${0.15 * (1 - dist / 150)})`;
                    ctx.lineWidth = 0.5;
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.stroke();
                }
            }
        });
        requestAnimationFrame(animate);
    }

    // --- KONFIGURASI ROBOT & TYPED.JS ---
    const robotContainer = document.getElementById('robot-container');
    const bubble = document.getElementById('speech-bubble');
    let typedInstance = null;
    let idleTimer = null;

    const welcomeMessages = [
        "Selamat datang di Lab Virtual!",
        "Gunakan PC agar simulasi lancar.",
        "Silakan pilih simulasi di bawah.",
        "Have fun!"
    ];

    const idleMessages = [
    "Tahukah kamu? Gerbang AND hanya aktif jika semua input bernilai 1.",
    "Logika digital adalah dasar dari semua komputer modern.",
    "Sudah mencoba simulasi Gerbang Logika di bawah?",
    "Laboratorium ini dikembangkan untuk simulasi interaktif.",
    "Istilah 'Bug' pertama kali digunakan karena ada serangga asli di dalam komputer.",
    "IPv6 diciptakan karena dunia kehabisan alamat IP dari sistem IPv4.",
    "Arduino pertama kali dikembangkan di Italia untuk membantu desain interaksi mahasiswa.",
    "RAM adalah memori volatil, artinya data akan hilang saat listrik dimatikan.",
    "Protokol HTTP adalah bahasa utama yang digunakan browser untuk meminta data web.",
    "Sistem biner hanya menggunakan angka 0 dan 1 untuk memproses semua data digital.",
    "IoT memungkinkan benda mati seperti lampu rumah berbicara satu sama lain via internet.",
    "Linux adalah jantung dari hampir semua server cloud dan superkomputer di dunia.",
    "Raspberry Pi awalnya dibuat untuk meningkatkan minat anak sekolah pada ilmu komputer.",
    "Kabel serat optik mengirimkan data menggunakan pulsa cahaya secepat kilat.",
    "Sistem Tertanam (Embedded System) biasanya dirancang untuk satu tugas spesifik saja.",
    "Sensor ultrasonik bekerja dengan memantulkan gelombang suara, mirip seperti kelelawar.",
    "DNS sering disebut sebagai 'buku telepon' untuk internet.",
    "Kernel adalah bagian inti dari sistem operasi yang mengelola hardware.",
    "Firmware adalah software yang 'ditanam' permanen di dalam hardware.",
    "Teknologi 5G dirancang untuk latensi sangat rendah, penting untuk kendali jarak jauh.",
    "Kriptografi kunci publik menggunakan matematika kompleks untuk menjaga keamanan pesan.",
    "Mikrokontroler ESP32 sangat populer untuk IoT karena sudah punya Wi-Fi dan Bluetooth.",
    "Compiler mengubah bahasa pemrograman yang kita tulis menjadi bahasa mesin.",
    "Prinsip 'Open Source' memungkinkan siapa saja melihat dan memperbaiki kode program.",
    "Satu Gigabyte setara dengan sekitar 1.000 Megabyte data.",
    "Routing adalah proses menentukan jalur terbaik untuk paket data di jaringan.",
    "Bahasa pemrograman C sering disebut sebagai 'Ibu' dari banyak bahasa modern.",
    "Artificial Intelligence membutuhkan jutaan data untuk belajar mengenali pola.",
    "Internet dan World Wide Web (WWW) adalah dua hal yang berbeda!"
];

    function handleSpeechAction(mode = 'random') {
    // Pastikan animasi lambaian ter-trigger
    robotContainer.classList.remove('is-waving');
    void robotContainer.offsetWidth; 
    robotContainer.classList.add('is-waving');
    
    if(bubble) {
        bubble.classList.add('active');
        
        if (typedInstance) {
            typedInstance.destroy();
        }

        let stringsToPlay;
        if (mode === 'welcome') {
            stringsToPlay = welcomeMessages.map(m => ` ${m}`);
        } else {
            // Pilih SATU pesan random saja tiap kali muncul
            const randomMsg = idleMessages[Math.floor(Math.random() * idleMessages.length)];
            stringsToPlay = [` ${randomMsg}`];
        }

        typedInstance = new Typed('#typed-text', {
            strings: stringsToPlay,
            typeSpeed: 30,
            backSpeed: 20,
            backDelay: 1500,
            loop: false, // Kita matikan loop bawaan agar kita bisa kontrol jedanya manual
            smartBackspace: true,
            showCursor: false,
            onComplete: () => {
                // Tunggu sebentar agar user selesai membaca kalimat terakhir
                setTimeout(() => {
                    bubble.classList.remove('active');
                    robotContainer.classList.remove('is-waving');
                    
                    // Setelah bubble tertutup, baru jalankan timer untuk info selanjutnya
                    // Ini yang akan memberikan jeda "diam" bagi si robot
                    resetIdleTimer(); 
                }, 3000); // Bubble terbuka selama 3 detik setelah teks selesai diketik
            }
        });
    }
}

    function resetIdleTimer() {
    if (idleTimer) clearTimeout(idleTimer);
    
    // Robot akan diam selama 20 detik sebelum memberikan info random baru
    idleTimer = setTimeout(() => {
        // Hanya munculkan info jika chat AI sedang tidak dibuka
        // (Asumsi chat container memiliki id 'chat-container')
        const chatBox = document.getElementById('chat-container');
        const isChatOpen = chatBox && chatBox.style.display === 'flex';
        
        if (!isChatOpen) {
            handleSpeechAction('random');
        }
    }, 5000); // 20 detik waktu diam
}

    // --- INITIALIZATION ---
    window.addEventListener('load', () => {
        resize();
        animate();

        // Trigger sapaan awal
        setTimeout(() => {
            handleSpeechAction('welcome');
        }, 1000);
    });

    window.addEventListener('resize', resize);

    // Event Klik Robot
    robotContainer.addEventListener('click', () => {
        handleSpeechAction('random');
        resetIdleTimer();
    });
</script>
</body>
</html>