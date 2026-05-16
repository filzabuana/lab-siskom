<template>
    <div class="bg-railway-card border border-railway-border rounded-[2rem] p-6 shadow-lg hover:border-purple-500/50 transition-all group overflow-hidden relative font-sans">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/5 blur-[50px] rounded-full group-hover:bg-purple-500/10 transition-colors"></div>

        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-500 shadow-inner">
                <i class="bi bi-clipboard-check text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-purple-500 transition-colors">Validasi Presensi</span>
                <p class="text-[9px] font-mono text-purple-400 leading-none mt-1 uppercase italic tracking-tighter">Queue: High Priority</p>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-baseline gap-2 mb-2">
                <div class="text-4xl font-black text-white italic tracking-tighter uppercase">12 <span class="text-xs text-slate-500 not-italic">MHS</span></div>
            </div>
            
            <p class="text-xs text-slate-500 italic mb-6 leading-relaxed">
                Ada antrean mahasiswa yang menunggu persetujuan kehadiran praktikum hari ini.
            </p>

            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-tight">Praktikum Struktur Data</span>
                </div>
                <div class="flex items-center gap-3 opacity-50">
                    <div class="w-1.5 h-1.5 bg-slate-600 rounded-full"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Komputasi Numerik (Done)</span>
                </div>
            </div>

            <button class="w-full py-4 bg-purple-500/10 hover:bg-purple-500 text-purple-500 hover:text-white border border-purple-500/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-[0.98]">
                Buka Panel Asisten
            </button>
        </div>
    </div>
</template>

<script setup>
// Props untuk jumlah mahasiswa dan list praktikum bisa dikirim dari controller nanti
</script>