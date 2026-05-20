<template>
    <div class="fixed inset-0 flex items-center justify-center p-4 z-[9999] pointer-events-none">
        <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="visible && $page.props.flash?.message" 
                 class="pointer-events-auto bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-2xl max-w-sm w-full text-center relative overflow-hidden"
            >
                <div class="w-16 h-16 bg-emerald-500/10 text-emerald-500 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4 border border-emerald-500/20">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase italic tracking-wider">BERHASIL!</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium mt-2 leading-relaxed">
                    {{ $page.props.flash.message }}
                </p>

                <div class="mt-5">
                    <button 
                        @click="closeNotification"
                        class="w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white dark:hover:text-white transition-all active:scale-95 shadow-lg"
                    >
                        OKE, DIMENGERTI
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const visible = ref(false);

// Intip setiap kali ada flash message baru masuk dari backend
watch(() => page.props.flash?.message, (newMessage) => {
    if (newMessage) {
        visible.value = true;
    }
}, { immediate: true });

const closeNotification = () => {
    visible.value = false;
    // Bersihkan session flash di state Inertia agar tidak memicu render ulang saat pindah halaman
    setTimeout(() => {
        if (page.props.flash) {
            page.props.flash.message = null;
        }
    }, 200);
};
</script>