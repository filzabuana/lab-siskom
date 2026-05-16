<template>
    <div class="bg-railway-card border border-railway-border rounded-[2rem] p-6 shadow-lg hover:border-indigo-500/50 transition-all group overflow-hidden relative font-sans">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 blur-[50px] rounded-full group-hover:bg-indigo-500/10 transition-colors"></div>

        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-500 shadow-inner">
                <i class="bi bi-mortarboard text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-500 transition-colors">Bimbingan Riset</span>
                <p class="text-[9px] font-mono text-indigo-400 leading-none mt-1 uppercase italic tracking-tighter">Research Monitoring</p>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-baseline gap-2 mb-2">
                <div class="text-4xl font-black text-white italic tracking-tighter uppercase">5 <span class="text-xs text-slate-500 not-italic">AKTIF</span></div>
            </div>
            
            <p class="text-xs text-slate-500 italic mb-6 leading-relaxed">
                Mahasiswa bimbingan Anda sedang melakukan pengambilan data/riset di laboratorium.
            </p>

            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Rata-rata Progres</span>
                    <span class="text-[10px] font-mono text-indigo-400 font-bold">65%</span>
                </div>
                <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                    <div class="w-[65%] h-full bg-indigo-500 rounded-full"></div>
                </div>
            </div>

            <button class="w-full py-4 bg-indigo-500/10 hover:bg-indigo-500 text-indigo-500 hover:text-white border border-indigo-500/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-[0.98]">
                Detail Mahasiswa
            </button>
        </div>
    </div>
</template>

<script setup>
// Nantinya data progres bimbingan bisa ditarik dari database relasi dosen-mahasiswa
</script>