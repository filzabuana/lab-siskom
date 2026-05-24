<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black italic uppercase text-slate-900 dark:text-white tracking-tighter">
                        REKAPITULASI <span class="text-blue-600">PRESENSI</span>
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-bold text-xs tracking-widest uppercase mt-1">
                        {{ courseClass.subject.name }} • {{ courseClass.name }}
                    </p>
                </div>
                <button @click="printReport" class="px-6 py-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-black uppercase italic text-xs rounded-xl transition-all flex items-center gap-2 border border-slate-200 dark:border-railway-border shadow-sm">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
            </div>

            <div class="bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] overflow-hidden shadow-2xl dark:shadow-blue-900/5">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-railway-dark/50 border-b border-slate-200 dark:border-railway-border">
                                <th class="p-6 text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 tracking-widest sticky left-0 bg-slate-50 dark:bg-railway-card z-10 border-r border-slate-200 dark:border-railway-border/50">
                                    Mahasiswa
                                </th>
                                <th v-for="(session, index) in sessions" :key="session.id" class="p-4 text-center min-w-[100px]">
                                    <p class="text-[10px] font-black uppercase text-blue-600 dark:text-blue-500 leading-none">P-{{ index + 1 }}</p>
                                    <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 mt-1 uppercase">{{ formatDate(session.created_at) }}</p>
                                </th>
                                <th class="p-6 text-center text-[10px] font-black uppercase text-emerald-600 dark:text-emerald-500 tracking-widest">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-railway-border">
                            <tr v-for="student in reportData" :key="student.id" class="hover:bg-blue-600/5 dark:hover:bg-blue-600/5 transition-colors group">
                                <td class="p-6 sticky left-0 bg-white dark:bg-railway-card group-hover:bg-slate-50 dark:group-hover:bg-slate-900/50 z-10 border-r border-slate-200 dark:border-railway-border/50">
                                    <p class="text-xs font-black uppercase text-slate-900 dark:text-white leading-none group-hover:text-blue-600 transition-colors">{{ student.name }}</p>
                                    <p class="text-[9px] font-mono text-slate-500 dark:text-slate-400 mt-1">{{ student.nim }}</p>
                                </td>
                                <td v-for="(attendance, index) in student.presence" :key="index" class="p-4 text-center">
                                    <div v-if="attendance" class="flex flex-col items-center justify-center">
                                        <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
                                        <span class="text-[7px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1">{{ formatTime(attendance.logged_at) }}</span>
                                    </div>
                                    <i v-else class="bi bi-x-circle-fill text-red-500/10 dark:text-red-500/20 text-lg"></i>
                                </td>
                                <td class="p-6 text-center">
                                    <span class="px-3 py-1 bg-blue-600/10 text-blue-600 dark:text-blue-400 text-xs font-black rounded-lg border border-blue-500/20">
                                        {{ calculateTotal(student.presence) }}/{{ sessions.length }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border p-6 rounded-3xl shadow-sm">
                    <p class="text-[9px] font-black uppercase text-slate-500 dark:text-slate-400 tracking-widest mb-1">Total Pertemuan</p>
                    <p class="text-2xl font-black italic text-slate-900 dark:text-white leading-none">
                        {{ sessions.length }} <span class="text-xs text-slate-400 dark:text-slate-600 uppercase italic">Sesi</span>
                    </p>
                </div>
                <div class="bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border p-6 rounded-3xl shadow-sm">
                    <p class="text-[9px] font-black uppercase text-slate-500 dark:text-slate-400 tracking-widest mb-1">Rata-rata Kehadiran</p>
                    <p class="text-2xl font-black italic text-emerald-500 dark:text-emerald-400 leading-none">85%</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    courseClass: Object,
    sessions: Array,
    reportData: Array
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
};

const formatTime = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

const calculateTotal = (presenceArray) => {
    return presenceArray.filter(p => p !== null).length;
};

const printReport = () => {
    window.print();
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { height: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { 
    background: #e2e8f0; 
    border-radius: 10px; 
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb { 
    background: #1e293b; 
}

@media print {
    /* Sembunyikan elemen layout admin saat print jika perlu */
    nav, aside, button { display: none !important; }
    .max-w-7xl { max-width: 100% !important; width: 100% !important; padding: 0 !important; }
    .bg-white, .dark\:bg-railway-card { background: white !important; border: none !important; }
    .text-slate-900, .dark\:text-white { color: black !important; }
    .sticky { position: static !important; }
}
</style>