<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    requests: Object,
});

const selectedRequest = ref(null);
const showReasonInput = ref(false); 

// Modifikasi formStatus agar membawa data barcode unit dinamis terindeks
const formStatus = useForm({
    status: '',
    catatan: '', 
    selected_barcodes: {}, // Format data: { 'detail_id-1': 'BARCODE_A', 'detail_id-2': 'BARCODE_B' }
});

// Helper untuk merekam barcode yang dipilih ke dalam objek formStatus berdasarkan indeks unit fisik
const handleBarcodeChange = (uniqueKey, barcode) => {
    formStatus.selected_barcodes[uniqueKey] = barcode;
};

const updateStatus = (id, newStatus) => {
    // 1. Logika untuk status yang butuh alasan (Ditolak atau Dibatalkan)
    if (['Ditolak', 'Dibatalkan'].includes(newStatus) && !showReasonInput.value) {
        showReasonInput.value = true;
        formStatus.status = newStatus;
        return;
    }

    // 2. Validasi Barcode untuk Alat is_serialized = 1 saat disetujui / diserahkan
    if (['Disetujui', 'Sedang Dipinjam'].includes(newStatus)) {
        let allBarcodesSelected = true;
        
        selectedRequest.value.details.forEach(dt => {
            if (dt.inventaris.is_serialized == 1) {
                // Cek setiap unit dari 1 sampai sejumlah dt.jumlah
                for (let n = 1; n <= dt.jumlah; n++) {
                    const uniqueKey = `${dt.id}-${n}`;
                    const currentBarcode = formStatus.selected_barcodes[uniqueKey] || (dt.barcodes_terpilih ? dt.barcodes_terpilih[n-1] : null);
                    if (!currentBarcode) {
                        allBarcodesSelected = false;
                    }
                }
            }
        });

        if (!allBarcodesSelected) {
            alert('Mohon pilih semua Barcode Aset terlebih dahulu untuk semua jumlah unit yang wajib dilacak (is_serialized).');
            return;
        }
    }

    const messages = {
        'Disetujui': 'Setujui peminjaman ini? Fisik barcode alat yang dipilih akan dikunci.',
        'Sedang Dipinjam': 'Konfirmasi alat sudah diambil oleh mahasiswa?',
        'Selesai': 'Konfirmasi pengembalian alat? Stok unit akan dikembalikan ke laboratorium.'
    };

    // Validasi catatan jika menolak atau membatalkan
    if (['Ditolak', 'Dibatalkan'].includes(newStatus) && !formStatus.catatan) {
        alert('Mohon isi alasan/catatan terlebih dahulu.');
        return;
    }

    // Eksekusi update
    if (['Ditolak', 'Dibatalkan'].includes(newStatus) || confirm(messages[newStatus] || `Ubah status ke ${newStatus}?`)) {
        formStatus.status = newStatus;
        formStatus.patch(route('admin.peminjaman.update-status', id), {
            preserveScroll: true,
            onSuccess: () => {
                selectedRequest.value = null;
                showReasonInput.value = false;
                formStatus.reset();
            },
        });
    }
};

const deleteDetail = (detailId) => {
    if (confirm('Coret item ini dari daftar?')) {
        formStatus.delete(route('admin.peminjaman.destroy-detail', detailId), {
            preserveScroll: true,
            onSuccess: () => {
                if (selectedRequest.value) {
                    selectedRequest.value.details = selectedRequest.value.details.filter(d => d.id !== detailId);
                    if (selectedRequest.value.details.length === 0) selectedRequest.value = null;
                }
            }
        });
    }
};

const getStatusBadge = (status) => {
    if (!status) return 'bg-zinc-100 dark:bg-railway-border text-zinc-800 dark:text-zinc-400';
    const s = status.toLowerCase();
    if (s === 'pending') return 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400 border-yellow-200 dark:border-yellow-900/50';
    if (s === 'disetujui') return 'bg-railway-accent/10 text-railway-accent border-railway-accent/20 animate-pulse';
    if (s === 'sedang dipinjam' || s === 'dipinjam') return 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 border-green-200 dark:border-green-900/50';
    if (s === 'selesai') return 'bg-zinc-100 dark:bg-railway-card text-zinc-500 border-zinc-200 dark:border-railway-border';
    if (s === 'ditolak' || s === 'dibatalkan') return 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400 border-red-200 dark:border-red-900/50';
    return 'bg-zinc-100 text-zinc-800';
};
</script>

<template>
    <Head title="Manajemen Peminjaman" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-100 leading-tight">Daftar Peminjaman Lab</h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-railway-card shadow-sm rounded-xl border border-zinc-200 dark:border-railway-border overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-railway-border table-auto">
                            <thead class="bg-zinc-50 dark:bg-railway-dark/50 font-sans text-left">
                                <tr>
                                    <th class="px-4 sm:px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Mahasiswa</th>
                                    <th class="px-4 sm:px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Item</th>
                                    <th class="px-4 sm:px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest font-mono">Tgl Pinjam</th>
                                    <th class="px-4 sm:px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Status</th>
                                    <th class="px-4 sm:px-6 py-4 text-right text-[10px] font-black text-zinc-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-railway-border text-sm">
                                <tr v-for="req in requests.data" :key="req.id" class="hover:bg-railway-accent/5 transition-colors group">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="font-bold text-zinc-900 dark:text-zinc-100 group-hover:text-railway-accent font-sans whitespace-nowrap">{{ req.user.name }}</div>
                                        <div class="text-[10px] text-zinc-400 font-mono">{{ req.user.email }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-railway-accent/10 text-railway-accent text-[11px] font-black border border-railway-accent/20 whitespace-nowrap">
                                            {{ req.details.length }} Alat
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-zinc-600 dark:text-zinc-400 font-mono text-xs whitespace-nowrap">{{ req.tgl_pinjam }}</td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <span :class="['px-2 py-1 rounded text-[10px] border font-black uppercase tracking-tighter whitespace-nowrap', getStatusBadge(req.status)]">
                                            {{ req.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right">
                                        <button @click="selectedRequest = req" 
                                            class="bg-white dark:bg-railway-dark border border-zinc-300 dark:border-railway-border px-3 py-2 rounded-lg shadow-sm font-bold text-[11px] text-zinc-700 dark:text-zinc-300 whitespace-nowrap active:scale-95">
                                            Detail & Proses
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="requests.links.length > 3" class="p-4 border-t dark:border-railway-border bg-zinc-50 dark:bg-railway-dark/50 flex flex-wrap justify-center gap-1">
                        <Link v-for="(link, k) in requests.links" :key="k" 
                            :href="link.url || '#'" 
                            v-html="link.label"
                            class="px-3 py-1 border rounded-lg text-[10px] font-bold transition-all font-mono"
                            :class="{'bg-railway-accent text-white border-railway-accent': link.active, 'bg-white dark:bg-railway-card text-zinc-500 dark:text-zinc-400 border-zinc-300 dark:border-railway-border': !link.active, 'opacity-50 cursor-not-allowed': !link.url}"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div v-if="selectedRequest" class="fixed inset-0 z-50 flex justify-end">
            <div @click="selectedRequest = null; showReasonInput = false" class="absolute inset-0 bg-railway-dark/60 backdrop-blur-sm"></div>
            
            <div class="relative w-[90%] sm:w-full sm:max-w-md bg-white dark:bg-railway-card h-full shadow-2xl flex flex-col animate-slide-in border-l dark:border-railway-border overflow-hidden">
                
                <div class="p-5 border-b dark:border-railway-border flex justify-between items-center bg-zinc-50 dark:bg-railway-dark">
                    <div>
                        <h3 class="font-black text-sm sm:text-lg text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter">Peminjaman #{{ selectedRequest.id }}</h3>
                        <p class="text-[10px] text-railway-accent font-black tracking-widest uppercase truncate max-w-[200px]">{{ selectedRequest.user.name }}</p>
                    </div>
                    <button @click="selectedRequest = null; showReasonInput = false" class="text-zinc-400 hover:text-railway-accent p-2 rounded-xl font-bold">✕</button>
                </div>
                
                <div class="p-5 flex-1 overflow-y-auto space-y-6">
                    <section>
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Keperluan</label>
                        <div class="mt-2 p-3 bg-railway-accent/5 border-l-4 border-railway-accent rounded-r-lg">
                            <p class="text-xs sm:text-sm text-zinc-800 dark:text-zinc-200 italic font-medium leading-relaxed font-sans">"{{ selectedRequest.keperluan }}"</p>
                        </div>
                    </section>

                    <section>
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-3 block">Alat Dipesan</label>
                        <div class="space-y-2 font-mono">
                            <div v-for="dt in selectedRequest.details" :key="dt.id" 
                                class="p-3 border dark:border-railway-border rounded-xl bg-white dark:bg-railway-dark/50 shadow-sm space-y-3">
                                
                                <div class="flex justify-between items-center">
                                    <div class="min-w-0">
                                        <p class="font-bold text-xs text-zinc-900 dark:text-zinc-100 truncate">{{ dt.inventaris.nama_aset }}</p>
                                        <p class="text-[9px] text-zinc-500 truncate">{{ dt.inventaris.kode_barang }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="text-[10px] font-black text-railway-accent bg-railway-accent/10 px-2 py-0.5 rounded border border-railway-accent/20">{{ dt.jumlah }}</span>
                                        <button v-if="selectedRequest.status.toLowerCase() === 'pending'"
                                            @click="deleteDetail(dt.id)"
                                            class="text-zinc-400 hover:text-red-500 p-1 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div v-if="dt.inventaris.is_serialized == 1" class="pt-2 border-t border-zinc-100 dark:border-railway-border/50 space-y-3">
                                    <div v-for="n in dt.jumlah" :key="n" class="space-y-1">
                                        <label class="text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase block">
                                            Pilih Barcode Unit ke-{{ n }}:
                                        </label>
                                        
                                        <div v-if="selectedRequest.status.toLowerCase() === 'pending'">
                                            <select 
                                                :value="formStatus.selected_barcodes[`${dt.id}-${n}`] || ''"
                                                @change="handleBarcodeChange(`${dt.id}-${n}`, $event.target.value)"
                                                class="w-full text-xs rounded-lg border-zinc-300 dark:border-railway-border bg-zinc-50 dark:bg-railway-dark font-mono text-zinc-800 dark:text-zinc-200 focus:ring-railway-accent">
                                                
                                                <option value="">-- Pilih Barcode Unit ke-{{ n }} --</option>
                                                
                                                <option v-for="item in dt.inventaris?.items" :key="item.id" :value="item.barcode_aset">
                                                    {{ item.barcode_aset }} (Status: {{ item.status }})
                                                </option>
                                            </select>
                                        </div>

                                        <div v-else class="p-2 bg-zinc-100 dark:bg-railway-dark rounded-lg text-xs text-zinc-700 dark:text-zinc-300 border dark:border-railway-border flex items-center justify-between">
                                            <span>🔒 Terkunci (Unit {{ n }}):</span>
                                            <span class="font-black text-railway-accent font-mono">
                                                {{ (dt.barcodes_terpilih ? dt.barcodes_terpilih[n-1] : '') || 'Belum di-set' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </div>

                <div class="p-5 bg-zinc-50 dark:bg-railway-dark border-t dark:border-railway-border">
                    <div v-if="showReasonInput" class="space-y-3">
                        <label class="text-[10px] font-bold text-red-600 uppercase">Alasan {{ formStatus.status }}</label>
                        <textarea v-model="formStatus.catatan" 
                            class="w-full rounded-xl border-red-200 dark:border-red-900/50 bg-white dark:bg-railway-card text-xs focus:ring-red-500 dark:text-zinc-200" 
                            rows="3" :placeholder="'Tulis alasan kenapa ' + formStatus.status.toLowerCase() + '...'"></textarea>
                        <div class="flex gap-2">
                            <button @click="showReasonInput = false" class="flex-1 py-3 text-xs font-bold text-zinc-500 bg-white border dark:bg-railway-card dark:border-railway-border rounded-xl">Batal</button>
                            <button @click="updateStatus(selectedRequest.id, formStatus.status)" 
                                class="flex-1 py-3 text-xs font-bold text-white bg-red-600 rounded-xl active:scale-95 transition-transform">
                                Kirim & {{ formStatus.status }}
                            </button>
                        </div>
                    </div>

                    <div v-else class="flex flex-col gap-2">
                        <template v-if="selectedRequest.status.toLowerCase() === 'pending'">
                            <button @click="updateStatus(selectedRequest.id, 'Disetujui')" 
                                class="w-full py-4 bg-railway-accent text-white font-black text-[11px] uppercase rounded-xl shadow-lg shadow-railway-accent/20 active:scale-95">
                                Setujui Sekarang
                            </button>
                            <button @click="updateStatus(selectedRequest.id, 'Ditolak')" 
                                class="w-full py-2 text-red-500 font-bold text-[10px] uppercase">
                                Tolak Permintaan
                            </button>
                        </template>

                        <template v-if="selectedRequest.status.toLowerCase() === 'disetujui'">
                            <button @click="updateStatus(selectedRequest.id, 'Sedang Dipinjam')" 
                                class="w-full py-4 bg-green-600 text-white font-black text-[11px] uppercase rounded-xl shadow-lg shadow-green-900/20 active:scale-95">
                                Konfirmasi Serah Terima Alat
                            </button>
                            <button @click="updateStatus(selectedRequest.id, 'Dibatalkan')" 
                                class="w-full py-2 text-red-500 font-bold text-[10px] uppercase">
                                Batalkan Peminjaman
                            </button>
                        </template>

                        <button v-if="['sedang dipinjam', 'dipinjam'].includes(selectedRequest.status.toLowerCase())"
                            @click="updateStatus(selectedRequest.id, 'Selesai')" 
                            class="w-full py-4 bg-zinc-900 text-white dark:bg-zinc-100 dark:text-railway-dark font-black text-[11px] uppercase rounded-xl active:scale-95">
                            Konfirmasi Pengembalian Alat
                        </button>

                        <div v-if="selectedRequest.status.toLowerCase() === 'selesai'" class="text-center py-2 italic text-[10px] text-zinc-500">
                            Peminjaman Selesai & Diarsipkan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-sans { font-family: 'Inter', sans-serif; }
.font-mono { font-family: 'Fira Code', monospace; }
.animate-slide-in { animation: slideIn 0.3s cubic-bezier(0, 0, 0.2, 1); }
@keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }
</style>