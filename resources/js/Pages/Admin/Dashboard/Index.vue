<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="transition-colors duration-500" v-if="auth.user">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <WelcomeCard :auth="auth" />

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <StatsMahasiswa v-if="auth.isMahasiswa" />

                    <StatsPlp v-if="auth.isPlp || auth.isAdmin" />

                    <StatsAsisten v-if="auth.isAsisten" />

                    <StatsDosen v-if="auth.isDosen" />

                    <StatsKalab v-if="auth.isKalab" />

                    <SystemStatus />
                </div>

                <ActivityLogs v-if="auth.isPlp || auth.isKalab || auth.isAdmin" :auth="auth" />
            </div>
        </div>

        <div v-else class="h-[60vh] flex items-center justify-center">
            <div class="flex flex-col items-center gap-4">
                <div class="w-12 h-12 border-4 border-railway-accent/20 border-t-railway-accent rounded-full animate-spin"></div>
                <span class="text-[10px] font-mono font-black text-slate-500 uppercase tracking-[0.5em]">Syncing Environment</span>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useAuthStore } from '@/Stores/useAuthStore';

// Import Partials - Pastikan path benar sesuai struktur folder
import WelcomeCard from './Partials/WelcomeCard.vue';
import StatsMahasiswa from './Partials/StatsMahasiswa.vue';
import StatsPlp from './Partials/StatsPlp.vue';
import StatsAsisten from './Partials/StatsAsisten.vue';
import StatsDosen from './Partials/StatsDosen.vue';
import StatsKalab from './Partials/StatsKalab.vue';
import SystemStatus from './Partials/SystemStatus.vue';
import ActivityLogs from './Partials/ActivityLogs.vue';

const auth = useAuthStore();
</script>

<style>
/* CSS di sini tetap bagus untuk global style dashboard */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
}

/* Scrollbar kustom yang serasi dengan tema Lab Komputasi */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: transparent; /* Biarkan mengikuti background layout */
}
::-webkit-scrollbar-thumb {
    background: #1f2937;
    border-radius: 10px;
    border: 2px solid transparent; /* Memberikan efek padding pada thumb */
    background-clip: content-box;
}
::-webkit-scrollbar-thumb:hover {
    background: #3b82f6;
    background-clip: content-box;
}
</style>