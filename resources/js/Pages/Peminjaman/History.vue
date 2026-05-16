<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    riwayat: {
        type: Array,
        default: () => []
    },
});

const selectedPeminjaman = ref(null);
const isModalOpen = ref(false);

// Statistik Ringkas
const stats = computed(() => {
    const total = props.riwayat.length;
    const pending = props.riwayat.filter(item => item.status.toLowerCase() === 'pending').length;
    // Update: 'disetujui' juga masuk kategori aktif karena proses sedang berjalan
    const dipinjam = props.riwayat.filter(item => ['sedang dipinjam', 'disetujui'].includes(item.status.toLowerCase())).length;
    return { total, pending, dipinjam };
});

const openDetail = (item) => {
    selectedPeminjaman.value = item;
    isModalOpen.value = true;
};

const getStatusClass = (status) => {
    if (!status) return 'bg-zinc-100 dark:bg-railway-border text-zinc-800 dark:text-zinc-400';
    const s = status.toLowerCase();
    if (s === 'pending') return 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400 border-yellow-200 dark:border-yellow-900/50';
    
    // Warna untuk status disetujui (Biru/Railway Accent)
    if (s === 'disetujui') return 'bg-railway-accent/10 text-railway-accent border-railway-accent/20';
    
    // Warna untuk status sedang dipinjam (Hijau)
    if (s === 'sedang dipinjam') return 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 border-green-200 dark:border-green-900/50';
    
    if (s === 'selesai') return 'bg-zinc-100 dark:bg-railway-dark text-zinc-500 border-zinc-200 dark:border-railway-border';
    if (s === 'ditolak') return 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400 border-red-200 dark:border-red-900/50';
    return 'bg-zinc-100 text-zinc-800';
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Riwayat Peminjaman" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-zinc-800 dark:text-zinc-100 leading-tight italic uppercase tracking-tighter">
                Riwayat <span class="text-railway-accent">Peminjaman</span>
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div v-if="riwayat.length > 0" class="mb-10">
                    <div class="flex items-center gap-3 mb-4">
                        <h3 class="text-[11px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em] italic">Aktivitas Saya</h3>
                        <div class="h-[1px] flex-1 bg-zinc-100 dark:bg-railway-border"></div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white dark:bg-railway-card p-4 sm:p-5 rounded-2xl border border-zinc-200 dark:border-railway-border shadow-sm">
                            <p class="text-[9px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Total</p>
                            <p class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-zinc-100 font-mono">{{ stats.total }}</p>
                        </div>
                        <div class="bg-white dark:bg-railway-card p-4 sm:p-5 rounded-2xl border border-zinc-200 dark:border-railway-border shadow-sm">
                            <p class="text-[9px] font-black text-yellow-500 uppercase tracking-widest mb-1">Pending</p>
                            <p class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-zinc-100 font-mono">{{ stats.pending }}</p>
                        </div>
                        <div class="bg-white dark:bg-railway-card p-4 sm:p-5 rounded-2xl border border-zinc-200 dark:border-railway-border shadow-sm">
                            <p class="text-[9px] font-black text-railway-accent uppercase tracking-widest mb-1">Aktif</p>
                            <p class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-zinc-100 font-mono">{{ stats.dipinjam }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="!riwayat || riwayat.length === 0" 
                    class="bg-white dark:bg-railway-card overflow-hidden shadow-sm rounded-3xl p-12 text-center border border-zinc-200 dark:border-railway-border">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-zinc-50 dark:bg-railway-dark rounded-full flex items-center justify-center mb-4 text-zinc-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <p class="text-zinc-500 dark:text-zinc-400 font-mono italic text-sm">Belum ada catatan aktivitas...</p>
                        <Link :href="route('peminjaman.katalog')" class="mt-6 px-8 py-3 bg-railway-accent text-white rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-railway-accent/20 transition-transform active:scale-95">
                            Eksplor Katalog Alat
                        </Link>
                    </div>
                </div>

                <div v-else class="space-y-4">
                    <div class="flex items-center gap-3 mb-2 px-1">
                        <h3 class="text-[11px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em] italic">Riwayat Permohonan</h3>
                        <div class="h-[1px] flex-1 bg-zinc-100 dark:bg-railway-border"></div>
                    </div>

                    <div v-for="item in riwayat" :key="item.id" 
                        class="bg-white dark:bg-railway-card overflow-hidden shadow-sm rounded-2xl border border-zinc-200 dark:border-railway-border hover:border-railway-accent/50 transition-all cursor-pointer group"
                        @click="openDetail(item)">
                        
                        <div class="p-4 sm:p-5">
                            <div class="flex justify-between items-start mb-3">
                                <div class="min-w-0 flex-1 pr-4">
                                    <p class="text-[10px] text-zinc-400 dark:text-zinc-500 font-mono font-bold tracking-tighter">REQID_{{ item.id }}</p>
                                    <h3 class="text-sm sm:text-base font-black text-zinc-900 dark:text-zinc-100 mt-0.5 truncate uppercase italic">
                                        {{ item.details?.[0]?.inventaris?.nama_aset || 'Alat tidak diketahui' }} 
                                        <span v-if="item.details?.length > 1" class="text-[10px] font-normal text-railway-accent lowercase not-italic">
                                            +{{ item.details.length - 1 }} item
                                        </span>
                                    </h3>
                                </div>
                                <span :class="['px-2 py-1 rounded text-[10px] font-black border uppercase tracking-tighter shrink-0', getStatusClass(item.status)]">
                                    {{ item.status }}
                                </span>
                            </div>

                            <div v-if="item.status.toLowerCase() === 'disetujui'" 
                                class="mb-3 p-2 bg-emerald-50 dark:bg-emerald-900/20 border-l-2 border-emerald-500 rounded-r flex items-center gap-2 animate-pulse">
                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                <p class="text-[10px] text-emerald-700 dark:text-emerald-400 font-bold uppercase tracking-tight">
                                    Siap diambil di Lab.
                                </p>
                            </div>

                            <div v-if="item.status.toLowerCase() === 'ditolak' && item.catatan" class="mb-3 p-2 bg-red-50 dark:bg-red-900/10 border-l-2 border-red-500 rounded-r">
                                <p class="text-[10px] text-red-700 dark:text-red-400 italic line-clamp-1">"{{ item.catatan }}"</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 py-3 border-t border-zinc-50 dark:border-railway-dark mb-3">
                                <div>
                                    <p class="text-zinc-400 dark:text-zinc-500 text-[9px] uppercase font-black tracking-widest">Pinjam</p>
                                    <p class="font-mono text-xs text-zinc-700 dark:text-zinc-300 font-bold tracking-tighter">{{ formatDate(item.tgl_pinjam) }}</p>
                                </div>
                                <div>
                                    <p class="text-zinc-400 dark:text-zinc-500 text-[9px] uppercase font-black tracking-widest">Kembali</p>
                                    <p class="font-mono text-xs text-zinc-700 dark:text-zinc-300 font-bold tracking-tighter">{{ formatDate(item.tgl_kembali_rencana) }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-railway-accent font-black uppercase tracking-widest group-hover:translate-x-1 transition-transform inline-flex items-center">
                                    Detail <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                                <span class="text-[9px] text-zinc-400 font-mono italic opacity-70">{{ formatDate(item.created_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen && selectedPeminjaman" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/80 backdrop-blur-sm"
            @click.self="isModalOpen = false">
            <div class="bg-white dark:bg-railway-card rounded-3xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-hidden flex flex-col border dark:border-railway-border animate-in fade-in zoom-in duration-200">
                
                <div class="p-6 overflow-y-auto">
                    <div class="flex justify-between items-center mb-6 border-b dark:border-railway-border pb-4">
                        <h3 class="text-lg font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter italic">Peminjaman #{{ selectedPeminjaman.id }}</h3>
                        <span :class="['px-2 py-1 rounded text-[10px] font-black border uppercase tracking-tighter', getStatusClass(selectedPeminjaman.status)]">
                            {{ selectedPeminjaman.status }}
                        </span>
                    </div>
                    
                    <div class="space-y-6 font-sans">
                        <div v-if="selectedPeminjaman.status.toLowerCase() === 'disetujui'" 
                            class="p-4 rounded-2xl border-l-4 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-500 shadow-sm animate-pulse">
                            <p class="text-[10px] font-black uppercase mb-1 text-emerald-600 dark:text-emerald-400 tracking-widest">
                                <i class="bi bi-info-circle-fill mr-1"></i> Langkah Selanjutnya:
                            </p>
                            <p class="text-sm italic font-black text-emerald-800 dark:text-emerald-200">
                                "Permohonan disetujui. Silakan ambil alat di lab."
                            </p>
                        </div>

                        <div v-if="selectedPeminjaman.catatan" 
                            class="p-4 rounded-2xl border-l-4" 
                            :class="selectedPeminjaman.status.toLowerCase() === 'ditolak' ? 'bg-red-50 dark:bg-red-900/10 border-red-500' : 'bg-railway-accent/5 border-railway-accent'">
                            <p class="text-[9px] font-black uppercase mb-1" 
                                :class="selectedPeminjaman.status.toLowerCase() === 'ditolak' ? 'text-red-600' : 'text-railway-accent'">
                                Respon Laboratorium:
                            </p>
                            <p class="text-sm italic font-medium text-zinc-800 dark:text-zinc-200">
                                "{{ selectedPeminjaman.catatan }}"
                            </p>
                        </div>

                        <div>
                            <p class="text-[9px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Keperluan:</p>
                            <div class="bg-zinc-50 dark:bg-railway-dark p-3 rounded-xl border dark:border-railway-border italic">
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                                    "{{ selectedPeminjaman.keperluan }}"
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[9px] font-black text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Item Pinjaman:</p>
                            <div class="space-y-2">
                                <div v-for="detail in selectedPeminjaman.details" :key="detail.id" 
                                    class="flex justify-between items-center bg-white dark:bg-railway-dark p-3 rounded-2xl border border-zinc-100 dark:border-railway-border shadow-sm">
                                    <div class="min-w-0 pr-4">
                                        <p class="text-xs font-black text-zinc-800 dark:text-zinc-100 uppercase italic truncate">{{ detail.inventaris?.nama_aset }}</p>
                                        <p class="text-[9px] text-zinc-500 font-mono tracking-tighter opacity-80">{{ detail.inventaris?.kode_barang }}</p>
                                    </div>
                                    <div class="shrink-0 bg-railway-accent/10 px-3 py-1 rounded-lg">
                                        <span class="text-xs font-black text-railway-accent">{{ detail.jumlah }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-zinc-50 dark:bg-railway-dark flex justify-end border-t dark:border-railway-border">
                    <button @click="isModalOpen = false" 
                        class="w-full sm:w-auto bg-white dark:bg-railway-card border border-zinc-300 dark:border-railway-border px-8 py-3 rounded-2xl font-black text-xs text-zinc-700 dark:text-zinc-300 uppercase italic active:scale-95 transition-all shadow-sm">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-mono { font-family: 'Fira Code', 'Cascadia Code', monospace; }
.font-sans { font-family: 'Inter', 'Figtree', sans-serif; }
</style>