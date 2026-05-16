<template>
    <div class="bg-railway-card border border-railway-border rounded-[2rem] p-6 shadow-lg hover:border-railway-accent/50 transition-all group overflow-hidden relative">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-railway-accent/5 blur-[50px] rounded-full group-hover:bg-railway-accent/10 transition-colors"></div>

        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="w-12 h-12 bg-railway-accent/10 border border-railway-accent/20 rounded-2xl flex items-center justify-center text-railway-accent shadow-inner">
                <i class="bi bi-box-seam text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-railway-accent transition-colors">Inventory Health</span>
                <p class="text-[9px] font-mono text-emerald-500/80 leading-none mt-1 uppercase">● All Systems Nominal</p>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-baseline gap-2 mb-2">
                <div class="text-4xl font-black text-white italic tracking-tighter uppercase">98.2%</div>
                <div class="text-[10px] font-mono text-slate-500 uppercase">Aset Ready</div>
            </div>
            
            <p class="text-xs text-slate-500 italic mb-6 leading-relaxed">
                Seluruh aset di <span class="text-slate-300 font-bold">Laboratorium Pemrograman & Komputasi</span> dalam kondisi prima dan terverifikasi.
            </p>

            <div class="space-y-2 mb-6">
                <div class="flex justify-between text-[9px] font-black uppercase tracking-tighter">
                    <span class="text-slate-500">Maintenance Cycle</span>
                    <span class="text-railway-accent">4 Days Left</span>
                </div>
                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                    <div class="w-[98.2%] h-full bg-railway-accent rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                </div>
            </div>

            <button class="w-full py-4 bg-railway-accent hover:bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-railway-accent/20 transition-all active:scale-[0.98]">
                Kelola Inventaris
            </button>
        </div>
    </div>
</template>

<script setup>
// Nantinya props data inventaris real-time dari backend bisa dipassing di sini
</script>

<style scoped>
/* Memberikan efek font italic yang konsisten dengan desain Bapak */
.font-black {
    font-weight: 900;
}
</style>