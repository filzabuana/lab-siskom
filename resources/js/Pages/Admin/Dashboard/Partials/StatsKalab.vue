<template>
    <div class="bg-railway-card border border-railway-border rounded-[2rem] p-6 shadow-lg hover:border-rose-500/50 transition-all group overflow-hidden relative font-sans">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-500/5 blur-[50px] rounded-full group-hover:bg-rose-500/10 transition-colors"></div>

        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="w-12 h-12 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center justify-center text-rose-500 shadow-inner">
                <i class="bi bi-shield-check text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-rose-500 transition-colors">Otoritas Kalab</span>
                <p class="text-[9px] font-mono text-rose-400 leading-none mt-1 uppercase italic tracking-tighter">Needs Action</p>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-baseline gap-2 mb-2">
                <div class="text-4xl font-black text-white italic tracking-tighter uppercase">2 <span class="text-xs text-slate-500 not-italic">PERLU APPROVAL</span></div>
            </div>
            
            <p class="text-xs text-slate-500 italic mb-6 leading-relaxed">
                Terdapat permintaan pengadaan alat atau penghapusan aset yang menunggu validasi Anda.
            </p>

            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between p-2 bg-rose-500/5 border border-rose-500/10 rounded-xl">
                    <span class="text-[10px] font-bold text-rose-200 uppercase tracking-tight">Pengadaan PC</span>
                    <span class="text-[9px] font-mono text-slate-500 uppercase">Baru</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-slate-800/50 border border-slate-800 rounded-xl">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Hibah Monitor</span>
                    <span class="text-[9px] font-mono text-slate-500 uppercase">Asset</span>
                </div>
            </div>

            <button class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 transition-all active:scale-[0.98]">
                Panel Otoritas
            </button>
        </div>
    </div>
</template>

<script setup>
// Otoritas ini biasanya berhubungan dengan perubahan status inventaris di database
</script>