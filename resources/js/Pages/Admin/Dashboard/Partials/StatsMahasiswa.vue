<template>
    <div class="bg-railway-card border border-railway-border rounded-[2rem] p-6 shadow-lg hover:border-amber-500/50 transition-all group overflow-hidden relative font-sans">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/5 blur-[50px] rounded-full group-hover:bg-amber-500/10 transition-colors"></div>

        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="w-12 h-12 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center justify-center text-amber-500 shadow-inner">
                <i class="bi bi-file-earmark-check text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-amber-500 transition-colors">Status Bebas Lab</span>
                <p class="text-[9px] font-mono text-amber-500/80 leading-none mt-1 uppercase">● Verification Active</p>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-baseline gap-2 mb-2">
                <div class="text-4xl font-black text-white italic tracking-tighter uppercase">Tertunda</div>
            </div>
            
            <p class="text-xs text-slate-500 italic mb-6 leading-relaxed">
                Harap segera selesaikan tanggungan alat atau administrasi untuk verifikasi kelulusan Anda.
            </p>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-white/5 border border-white/5 rounded-2xl">
                    <div class="text-[9px] font-black text-slate-500 uppercase mb-1">Tanggungan</div>
                    <div class="text-sm font-bold text-white uppercase italic">2 Item</div>
                </div>
                <div class="p-3 bg-white/5 border border-white/5 rounded-2xl">
                    <div class="text-[9px] font-black text-slate-500 uppercase mb-1">Lokasi</div>
                    <div class="text-sm font-bold text-white uppercase italic">Lab PK</div>
                </div>
            </div>

            <button class="w-full py-4 bg-amber-500/10 hover:bg-amber-500 text-amber-500 hover:text-white border border-amber-500/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-[0.98]">
                Cek Inventori Alat
            </button>
        </div>
    </div>
</template>

<script setup>
// Props untuk status dan jumlah tanggungan bisa ditambahkan di sini nanti
</script>