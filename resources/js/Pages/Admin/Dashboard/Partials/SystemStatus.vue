<template>
    <div class="bg-railway-card border border-railway-border rounded-[2rem] p-6 shadow-lg hover:border-emerald-500/50 transition-all group overflow-hidden relative font-sans">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/5 blur-[50px] rounded-full group-hover:bg-emerald-500/10 transition-colors"></div>

        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner">
                <i class="bi bi-cpu text-2xl group-hover:rotate-90 transition-transform duration-700"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-emerald-500 transition-colors">Sistem Status</span>
                <p class="text-[9px] font-mono text-emerald-500 leading-none mt-1 uppercase italic animate-pulse">● Live Tracking</p>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-baseline gap-2 mb-2">
                <div class="text-4xl font-black text-emerald-500 italic tracking-tighter uppercase font-mono">Online</div>
            </div>
            
            <p class="text-xs text-slate-500 italic mb-6 leading-relaxed">
                Database tersinkronisasi. Respon server: <span class="text-emerald-500/80 font-mono">24ms</span>
            </p>

            <div class="space-y-4 mb-2">
                <div class="flex justify-between items-center text-[9px] font-black uppercase tracking-tighter text-slate-500">
                    <span>Server Load</span>
                    <span class="font-mono text-slate-400">12%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                    <div class="w-full h-full bg-emerald-500 animate-[pulse_2s_infinite]"></div>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <div class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_8px_#10b981]"></div>
                <span class="text-[9px] font-mono text-slate-600 uppercase tracking-[0.2em]">All Nodes Operational</span>
            </div>
        </div>
    </div>
</template>

<script setup>
// Status ini nantinya bisa dibuat reaktif menggunakan polling atau websockets
</script>

<style scoped>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scaleX(1); }
    50% { opacity: 0.6; transform: scaleX(0.98); }
}
</style>