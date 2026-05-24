<template>
    <AuthenticatedLayout>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-20">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] p-8 shadow-2xl dark:shadow-blue-900/10 text-center overflow-hidden relative">
                    <div class="mb-6 text-left relative z-10">
                        <h1 class="text-2xl font-black italic uppercase text-slate-900 dark:text-white tracking-tighter leading-none">
                            {{ courseClass.subject.name }}
                        </h1>
                        <p class="text-blue-600 dark:text-blue-500 font-bold text-xs tracking-[0.2em] uppercase mt-2">
                            {{ courseClass.name }} • PERTEMUAN KE-{{ sessionNumber }}
                        </p>
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[10px] uppercase tracking-[0.1em] mt-1">
                            {{ today }}
                        </p>
                    </div>

                    <div v-if="session" class="space-y-8 relative z-10">
                        
                        <div class="relative group inline-block">
                            <div v-if="qrCode" class="bg-white p-6 rounded-[2rem] shadow-inner ring-8 ring-blue-600/10 transition-all duration-500" v-html="qrCode"></div>
                            
                            <div v-else class="bg-slate-50 dark:bg-railway-dark/90 backdrop-blur-md p-12 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-railway-border flex flex-col items-center justify-center w-[348px] h-[348px]">
                                <i class="bi bi-eye-slash-fill text-5xl text-slate-300 dark:text-slate-700 mb-4"></i>
                                <p class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest leading-relaxed">
                                    QR CODE DISEMBUNYIKAN<br>Presensi ditutup sementara
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div :class="session.is_active ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-red-500/10 border-red-500/20'" 
                                 class="border p-4 rounded-2xl transition-colors duration-500">
                                <p class="text-[9px] font-black uppercase text-slate-500 dark:text-slate-400 tracking-widest mb-1">Status Kehadiran</p>
                                <div class="flex items-center justify-center gap-2" :class="session.is_active ? 'text-emerald-500' : 'text-red-500'">
                                    <span v-if="session.is_active" class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span class="font-black text-xs uppercase italic tracking-wider">
                                        {{ session.is_active ? 'Menerima Presensi...' : 'Sesi Terkunci' }}
                                    </span>
                                </div>
                            </div>

                            <button @click="toggleSession" 
                                    class="w-full py-4 rounded-2xl font-black uppercase italic tracking-widest text-[11px] transition-all active:scale-95 flex items-center justify-center gap-2 shadow-xl"
                                    :class="session.is_active ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:opacity-90' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-600/20'">
                                <i :class="session.is_active ? 'bi bi-pause-fill' : 'bi bi-play-fill'" class="text-lg"></i>
                                {{ session.is_active ? 'Tutup Presensi Sementara' : 'Buka / Munculkan QR' }}
                            </button>
                        </div>
                    </div>

                    <div v-else class="py-20 border-2 border-dashed border-slate-200 dark:border-railway-border rounded-[3rem] bg-slate-50 dark:bg-railway-dark/20">
                        <div class="w-20 h-20 bg-white dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-200 dark:border-railway-border shadow-sm">
                            <i class="bi bi-qr-code text-3xl text-slate-400 dark:text-slate-600"></i>
                        </div>
                        <h4 class="text-slate-900 dark:text-white font-black italic uppercase tracking-tighter mb-2">Sesi Hari Ini Belum Ada</h4>
                        <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-8">Klik tombol di bawah untuk memulai</p>
                        <button @click="startSession" 
                                class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase italic tracking-widest rounded-2xl transition-all active:scale-95 shadow-2xl shadow-blue-600/30">
                            Mulai Praktikum
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] overflow-hidden shadow-2xl dark:shadow-blue-900/5 flex flex-col h-full max-h-[750px]">
                    <div class="p-6 border-b border-slate-100 dark:border-railway-border flex justify-between items-center bg-slate-50/50 dark:bg-railway-dark/30">
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white italic uppercase tracking-tight leading-none">Daftar Kehadiran</h3>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 uppercase font-bold mt-1 tracking-widest">Real-time update setiap 5 detik</p>
                        </div>
                        <span class="px-4 py-2 bg-blue-600/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 text-[10px] font-black rounded-xl">
                            {{ attendances.length }} MAHASISWA
                        </span>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-3 custom-scrollbar bg-transparent">
                        <TransitionGroup name="list">
                            <div v-for="item in attendances" :key="item.id" 
                                 class="flex items-center justify-between p-4 bg-white dark:bg-railway-dark/50 border border-slate-100 dark:border-railway-border rounded-2xl hover:border-blue-500/50 transition-all duration-300 shadow-sm group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-railway-dark border border-slate-200 dark:border-railway-border flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform">
                                        <img v-if="item.student.avatar" :src="item.student.avatar" class="w-full h-full object-cover" />
                                        <span v-else class="text-xs font-black text-blue-600 dark:text-blue-500 italic">{{ item.student.name.charAt(0) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase text-slate-900 dark:text-white leading-none group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ item.student.name }}</p>
                                        <p class="text-[9px] font-mono text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-tighter">{{ item.student.nim_nip }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[11px] font-black text-emerald-600 dark:text-emerald-400 italic tracking-tighter">{{ formatTime(item.logged_at) }}</p>
                                    <div class="flex items-center gap-1 justify-end text-slate-400 dark:text-slate-600">
                                        <i class="bi bi-shield-check text-[10px]"></i>
                                        <p class="text-[8px] font-bold uppercase">Verified</p>
                                    </div>
                                </div>
                            </div>
                        </TransitionGroup>

                        <div v-if="attendances.length === 0" class="text-center py-24">
                            <div class="animate-pulse mb-4">
                                <i class="bi bi-person-badge text-5xl text-slate-200 dark:text-slate-800"></i>
                            </div>
                            <p class="text-slate-400 dark:text-slate-600 font-black italic uppercase text-xs tracking-[0.3em]">Menunggu Mahasiswa Scan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    courseClass: Object,
    session: Object,
    attendances: Array,
    qrCode: String,
    sessionNumber: Number,
    today: String
});

const startSession = () => {
    router.post(route('admin.attendance.start', props.courseClass.id), {}, {
        preserveScroll: true
    });
};

const toggleSession = () => {
    if (!props.session) return;
    router.patch(route('admin.attendance.toggle', props.session.id), {}, {
        preserveScroll: true
    });
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

let pollingTimer = null;

const startPolling = () => {
    pollingTimer = setInterval(() => {
        router.reload({ 
            only: ['attendances', 'session', 'qrCode'], // Ditambah session & qrCode agar status is_active ikut terupdate
            preserveScroll: true,
            preserveState: true 
        });
    }, 5000);
};

onMounted(() => {
    if (props.session) {
        startPolling();
    }
});

onUnmounted(() => {
    if (pollingTimer) clearInterval(pollingTimer);
});
</script>

<style scoped>
.list-enter-active, .list-leave-active { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
.list-enter-from { opacity: 0; transform: translateY(20px) scale(0.95); }
.list-leave-to { opacity: 0; transform: scale(0.9); }

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
</style>