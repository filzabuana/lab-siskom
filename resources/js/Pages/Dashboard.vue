<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 py-8 space-y-8" v-if="auth.user">
            
            <!-- SECTION 1: WELCOME BENTO -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Welcome Card -->
                <div class="lg:col-span-8 relative group overflow-hidden bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 md:p-12 shadow-2xl transition-all hover:border-blue-500/50">
                    <!-- Background Decoration / Image -->
                    <div class="absolute top-0 right-0 w-1/2 h-full opacity-20 pointer-events-none group-hover:opacity-30 transition-opacity">
                        <img src="https://illustrations.popsy.co/white/data-analysis.svg" alt="Lab Illustration" class="w-full h-full object-contain object-right" />
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-full text-[10px] font-black text-blue-500 uppercase tracking-[0.3em]">
                                {{ auth.displayRole }} • FMIPA UNTAN
                            </span>
                        </div>
                        
                        <h1 class="text-4xl md:text-6xl font-black text-white italic tracking-tighter mb-4 leading-none">
                            SISTEM INFORMASI <span class="text-blue-500 not-italic">LAB</span>
                        </h1>
                        
                        <p class="text-slate-400 text-sm md:text-base max-w-lg leading-relaxed">
                            Selamat bekerja, <span class="text-white font-bold">{{ auth.user?.name }}</span>. 
                            Unit Komputasi siap digunakan untuk monitoring aset dan layanan mahasiswa secara real-time.
                        </p>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="lg:col-span-4 bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 flex flex-col items-center justify-center text-center shadow-2xl hover:border-blue-500/50 transition-all">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-blue-500 to-indigo-600 p-1 rotate-3 group-hover:rotate-6 transition-transform shadow-xl shadow-blue-500/20">
                            <div class="w-full h-full bg-slate-900 rounded-[1.8rem] flex items-center justify-center overflow-hidden">
                                <img v-if="auth.user?.avatar" :src="auth.user.avatar" class="w-full h-full object-cover" />
                                <i v-else class="bi bi-person-badge text-4xl text-blue-500"></i>
                            </div>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 border-4 border-slate-900 rounded-full"></div>
                    </div>
                    
                    <h3 class="text-xl font-black text-white uppercase italic tracking-tight italic">{{ auth.user?.name }}</h3>
                    <p class="text-[10px] font-mono text-slate-500 uppercase tracking-widest mt-1">{{ auth.user?.email }}</p>
                </div>
            </div>

            <!-- SECTION 2: DINAMIS BERDASARKAN ROLE -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Widget Khusus Mahasiswa -->
                <div v-if="auth.isMahasiswa" class="bg-slate-900 border border-slate-800 rounded-[2rem] p-6 shadow-lg hover:border-amber-500/50 transition-all group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center justify-center text-amber-500">
                            <i class="bi bi-file-earmark-check text-2xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-amber-500 transition-colors">Status Bebas Lab</span>
                    </div>
                    <div class="text-4xl font-black text-white italic tracking-tighter mb-2 uppercase">Tertunda</div>
                    <p class="text-xs text-slate-500 italic mb-6">Selesaikan tanggungan alat untuk verifikasi kelulusan.</p>
                    <button class="w-full py-4 bg-amber-500/10 hover:bg-amber-500 text-amber-500 hover:text-white border border-amber-500/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Cek Inventori
                    </button>
                </div>

                <!-- Widget Khusus PLP/Admin -->
                <div v-if="auth.isAdmin" class="bg-slate-900 border border-slate-800 rounded-[2rem] p-6 shadow-lg hover:border-blue-500/50 transition-all group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-500">
                            <i class="bi bi-box-seam text-2xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-blue-500 transition-colors">Inventory Health</span>
                    </div>
                    <div class="text-4xl font-black text-white italic tracking-tighter mb-2 uppercase">98.2%</div>
                    <p class="text-xs text-slate-500 italic mb-6">Seluruh aset laboratorium dalam kondisi layak pakai.</p>
                    <button class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all">
                        Kelola Aset
                    </button>
                </div>

                <!-- Statistik Umum: Sistem Status -->
                <div class="bg-slate-900 border border-slate-800 rounded-[2rem] p-6 shadow-lg hover:border-emerald-500/50 transition-all group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500">
                            <i class="bi bi-cpu text-2xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-emerald-500 transition-colors">Sistem Status</span>
                    </div>
                    <div class="text-4xl font-black text-emerald-500 italic tracking-tighter mb-2 font-mono uppercase">Online</div>
                    <p class="text-xs text-slate-500 italic mb-6">Database disinkronkan: 2 menit yang lalu.</p>
                    
                    <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                        <div class="w-full h-full bg-emerald-500 animate-[pulse_2s_infinite]"></div>
                    </div>
                </div>

                <!-- Widget Khusus Asisten -->
                <div v-if="auth.isAsisten" class="bg-slate-900 border border-slate-800 rounded-[2rem] p-6 shadow-lg hover:border-purple-500/50 transition-all group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-500">
                            <i class="bi bi-clipboard-check text-2xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-purple-500 transition-colors">Validasi Presensi</span>
                    </div>
                    <div class="text-4xl font-black text-white italic tracking-tighter mb-2 uppercase">12 <span class="text-xs text-slate-500">MHS</span></div>
                    <p class="text-xs text-slate-500 italic mb-6">Menunggu persetujuan kehadiran hari ini.</p>
                    <button class="w-full py-4 bg-purple-500/10 hover:bg-purple-500 text-purple-500 hover:text-white border border-purple-500/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Lihat Daftar
                    </button>
                </div>
            </div>

            <!-- SECTION 3: SYSTEM LOGS -->
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 shadow-2xl">
                <div class="flex items-center gap-3 mb-8">
                    <i class="bi bi-terminal text-blue-500 text-2xl"></i>
                    <h3 class="text-white font-black uppercase tracking-tighter italic">Aktivitas Sistem Terbaru</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left font-mono text-[11px]">
                        <tbody class="divide-y divide-slate-800">
                            <tr class="text-slate-500 group hover:bg-white/5 transition-colors">
                                <td class="py-4 px-2">[INFO] Sync database laboratorium berhasil</td>
                                <td class="py-4 px-2 text-right">Today, 08:45</td>
                            </tr>
                            <tr class="text-blue-400 group hover:bg-white/5 transition-colors italic font-bold">
                                <td class="py-4 px-2">[AUTH] Login berhasil: {{ auth.user?.name }} ({{ auth.displayRole }})</td>
                                <td class="py-4 px-2 text-right">Today, 09:12</td>
                            </tr>
                            <tr class="text-slate-500 group hover:bg-white/5 transition-colors">
                                <td class="py-4 px-2">[APP] Surat Bebas Lab diproses untuk 12 Mahasiswa</td>
                                <td class="py-4 px-2 text-right">Yesterday, 23:59</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- LOADING STATE -->
        <div v-else class="min-h-screen flex items-center justify-center bg-slate-950">
            <div class="flex flex-col items-center gap-4">
                <div class="w-12 h-12 border-4 border-blue-500/20 border-t-blue-500 rounded-full animate-spin"></div>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.5em]">Initializing</span>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useAuthStore } from '@/Stores/useAuthStore';

const auth = useAuthStore();
</script>

<style scoped>
/* Animasi custom untuk pulse bar */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Custom shadow untuk efek glow */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}
</style>