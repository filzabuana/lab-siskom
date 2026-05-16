
<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Kita ambil data user langsung dari Inertia Shared Props (Fresh dari Laravel)
const page = usePage();
const currentUser = computed(() => page.props.auth.user);

// Kita tetap sediakan prop auth untuk kebutuhan logic role di template jika diperlukan
const props = defineProps({
    auth: Object,
});
</script>

<template>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 relative group overflow-hidden bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] p-8 md:p-12 shadow-xl dark:shadow-2xl transition-all hover:border-railway-accent/30 flex flex-col justify-center">
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-railway-accent/10 border border-railway-accent/20 rounded-full text-[10px] font-black text-railway-accent uppercase tracking-[0.3em]">
                        {{ auth.displayRole || 'User Access' }} • FMIPA UNTAN
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white italic tracking-tighter mb-4 leading-none uppercase">
                    Sistem Informasi <span class="text-railway-accent not-italic">Lab</span>
                </h1>
                
                <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base max-w-lg leading-relaxed font-sans font-medium">
                    Selamat bekerja, <span class="text-slate-900 dark:text-white font-black">{{ currentUser?.name }}</span>. 
                    Unit Komputasi siap digunakan untuk monitoring aset dan layanan mahasiswa secara real-time.
                </p>
            </div>
        </div>

        <div class="lg:col-span-4 bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] p-8 flex flex-col items-center justify-center text-center shadow-xl dark:shadow-2xl hover:border-railway-accent/30 transition-all group">
            <div class="relative mb-6">
                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-railway-accent to-indigo-600 p-1 rotate-3 group-hover:rotate-6 transition-transform shadow-xl shadow-railway-accent/20">
                    <div class="w-full h-full bg-slate-100 dark:bg-railway-dark rounded-[2.2rem] flex items-center justify-center overflow-hidden">
                        
                        <template v-if="currentUser?.avatar">
                            <img 
                                :src="currentUser.avatar" 
                                class="w-full h-full object-cover shadow-inner" 
                                alt="Profile Photo" 
                            />
                        </template>
                        
                        <div v-else class="flex flex-col items-center">
                            <span class="text-3xl font-black text-railway-accent italic uppercase">
                                {{ currentUser?.name ? currentUser.name.charAt(0) : '?' }}
                            </span>
                        </div>

                    </div>
                </div>
                <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-emerald-500 border-4 border-white dark:border-railway-card rounded-full shadow-lg"></div>
            </div>
            
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tight leading-tight">
                {{ currentUser?.name }}
            </h3>
            <p class="text-[10px] font-mono text-slate-500 dark:text-slate-500 uppercase tracking-widest mt-2 px-4 truncate w-full">
                {{ currentUser?.email }}
            </p>

            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-railway-border/50 w-full">
                <span class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em] group-hover:text-railway-accent transition-colors">
                    ID: {{ currentUser?.id?.toString().padStart(4, '0') }} • Verifikasi Aman
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transisi masuk yang halus untuk card */
.lg\:col-span-8, .lg\:col-span-4 {
    animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>