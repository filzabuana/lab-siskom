<script setup>
defineProps({
    auth: Object,
});
</script>

<template>
    <div class="bg-railway-card border border-railway-border rounded-[2.5rem] p-8 shadow-2xl transition-all hover:border-railway-accent/30 group">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-railway-accent/10 border border-railway-accent/20 rounded-xl flex items-center justify-center text-railway-accent">
                    <i class="bi bi-terminal text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-black uppercase tracking-tighter italic leading-none">System Logs</h3>
                    <p class="text-[9px] font-mono text-slate-500 uppercase tracking-widest mt-1">Real-time Activity Stream</p>
                </div>
            </div>
            
            <div class="flex gap-1.5 px-3 py-1.5 bg-black/20 rounded-full border border-white/5">
                <div class="w-2 h-2 rounded-full bg-rose-500/50"></div>
                <div class="w-2 h-2 rounded-full bg-amber-500/50"></div>
                <div class="w-2 h-2 rounded-full bg-emerald-500/50"></div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left font-mono text-[11px] leading-relaxed">
                <tbody class="divide-y divide-railway-border/50">
                    <tr class="text-slate-500 group/row hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-2 tracking-tighter">
                            <span class="text-emerald-500 mr-2">[SUCCESS]</span>
                            Sync database laboratorium FMIPA UNTAN berhasil dilakukan.
                        </td>
                        <td class="py-4 px-2 text-right opacity-40 font-bold group-hover/row:opacity-100 transition-opacity">Today, 08:45:12</td>
                    </tr>

                    <tr class="text-railway-accent group/row hover:bg-white/[0.02] transition-colors italic font-bold">
                        <td class="py-4 px-2 tracking-tighter">
                            <span class="text-railway-accent mr-2">[AUTH]</span>
                            Login detected: {{ auth.user?.name }} ({{ auth.displayRole }})
                        </td>
                        <td class="py-4 px-2 text-right opacity-40 group-hover/row:opacity-100 transition-opacity uppercase">Now</td>
                    </tr>

                    <tr class="text-slate-500 group/row hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-2 tracking-tighter">
                            <span class="text-amber-500 mr-2">[PROC]</span>
                            Batch processing: Surat Bebas Lab untuk 12 Mahasiswa.
                        </td>
                        <td class="py-4 px-2 text-right opacity-40 font-bold group-hover/row:opacity-100 transition-opacity">Yesterday, 23:59:01</td>
                    </tr>

                    <tr class="text-slate-500 group/row hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-2 tracking-tighter">
                            <span class="text-blue-500 mr-2">[SYS]</span>
                            Automated backup to local server: Complete.
                        </td>
                        <td class="py-4 px-2 text-right opacity-40 font-bold group-hover/row:opacity-100 transition-opacity">14 May 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-8 pt-4 border-t border-railway-border/30 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-railway-accent rounded-full animate-ping"></span>
                <span class="text-[9px] font-mono text-slate-600 uppercase tracking-[0.2em]">Listening for system events...</span>
            </div>
            <button class="text-[9px] font-black text-railway-accent uppercase hover:underline">View Full Logs</button>
        </div>
    </div>
</template>

<style scoped>
/* Menghaluskan tampilan font mono pada background gelap */
td {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>