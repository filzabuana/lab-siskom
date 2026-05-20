<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; 
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { marked } from 'marked';
import Swal from 'sweetalert2';

const props = defineProps({
    item: Object,
    stok_tersedia: Number,
    totalDipinjam: Number,
    isKatalog: Boolean
});

// Ambil data Auth dari Global Props Inertia
const auth = computed(() => usePage().props.auth);
const user = computed(() => auth.value?.user);

/**
 * ELEMEN PERMISSION-BASED (Murni berbasis Permission, Bukan Role/is_admin)
 */
const canModifyOrDelete = computed(() => {
    return user.value?.permissions?.includes('*') ||
           user.value?.permissions?.includes('manage-inventaris');
});

// Permission untuk melihat daftar fisik unit barcode dan pelacakan peminjam
const canViewUnitDetails = computed(() => {
    return user.value?.permissions?.includes('*') ||
           user.value?.permissions?.includes('view-inventaris') ||
           user.value?.permissions?.includes('manage-inventaris');
});

const isLoggedIn = computed(() => !!user.value);

// Konversi Markdown ke HTML
const renderedMarkdown = computed(() => {
    return props.item.deskripsi 
        ? marked(props.item.deskripsi) 
        : '<i>Belum ada deskripsi spesifik untuk alat ini.</i>';
});

// Logic Hapus
const formDelete = useForm({});
const confirmDelete = (id, nama) => {
    Swal.fire({
        title: 'Hapus Aset?',
        text: `Apakah Anda yakin ingin menghapus ${nama}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        fontFamily: 'Inter',
    }).then((result) => {
        if (result.isConfirmed) {
            formDelete.delete(route('admin.inventaris.destroy', id), {
                onSuccess: () => Swal.fire('Terhapus!', 'Data berhasil dibuang.', 'success')
            });
        }
    });
};

// Format Tanggal
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};
</script>

<template>
    <Head :title="`Detail - ${item.nama_aset}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-black text-xl text-slate-800 dark:text-white uppercase tracking-tighter italic">
                Detail Inventaris
            </h2>
        </template>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl py-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <Link :href="isKatalog ? route('katalog.index') : route('admin.inventaris.index')" 
                   class="group flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-blue-600 transition-colors italic">
                    <i class="bi bi-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
                </Link>

                <div v-if="!isKatalog && canModifyOrDelete" class="flex items-center bg-white dark:bg-slate-800 rounded-2xl p-1 shadow-sm border border-slate-200 dark:border-slate-700">
                    <Link :href="route('admin.inventaris.edit', item.id)" 
                       class="flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all italic">
                        <i class="bi bi-pencil mr-2"></i> Edit Data
                    </Link>
                    
                    <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                    
                    <button @click="confirmDelete(item.id, item.nama_aset)"
                            class="flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all italic">
                        <i class="bi bi-trash mr-2"></i> Hapus
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            <div class="md:w-5/12 relative bg-slate-100 dark:bg-slate-900">
                                <img v-if="item.foto_barang" :src="`/storage/inventaris/${item.foto_barang}`" 
                                     class="h-full w-full object-cover min-h-[300px]" 
                                     :alt="item.nama_aset">
                                <div v-else class="h-full w-full min-h-[300px] flex flex-col items-center justify-center text-slate-400 space-y-3">
                                    <i class="bi bi-image text-5xl opacity-20"></i>
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] italic">No Image Available</span>
                                </div>
                            </div>

                            <div class="md:w-7/12 p-8 md:p-10">
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded-full italic">
                                        {{ item.kategori }}
                                    </span>
                                    <span v-if="item.tipe_peminjaman === 'Bisa Dipinjam'" class="px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-full italic border border-emerald-100 dark:border-emerald-800">
                                        <i class="bi bi-house-door mr-1"></i> Mobile
                                    </span>
                                    <span v-else class="px-4 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-full italic">
                                        <i class="bi bi-lock-fill mr-1"></i> Statis
                                    </span>
                                </div>

                                <h1 class="text-3xl font-black text-slate-900 dark:text-white leading-tight mb-2 uppercase italic tracking-tighter">
                                    {{ item.nama_aset }}
                                </h1>
                                
                                <p class="font-mono text-xs text-slate-400 mb-8 tracking-widest">
                                    {{ item.kode_barang }} <span v-if="!isKatalog" class="mx-2">/</span> <span v-if="!isKatalog">NUP: {{ item.nup ?? '-' }}</span>
                                </p>

                                <div class="grid grid-cols-2 gap-y-6 gap-x-4 border-t border-slate-50 dark:border-slate-700 pt-8">
                                    <div>
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Merk / Brand</label>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase">{{ item.merk ?? 'Generic' }}</span>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Tahun Perolehan</label>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ item.tahun_perolehan }}</span>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Ruangan / Lokasi</label>
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase italic">
                                            <i class="bi bi-geo-alt mr-1"></i>{{ item.ruangan }}
                                        </span>
                                    </div>
                                    <div v-if="!isKatalog">
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Sumber Dana</label>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase">{{ item.sumber_dana ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!!item.is_serialized && canViewUnitDetails" class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div>
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] flex items-center italic">
                                    <i class="bi bi-qr-code-scan mr-3 text-blue-600"></i> Manajemen Unit Serialisasi
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1 italic">Daftar fisik spesifik per unit laboratorium</p>
                            </div>
                            <span class="w-fit px-3 py-1 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded-lg italic">
                                Total: {{ item.items?.length ?? 0 }} Unit
                            </span>
                        </div>

                        <div v-if="!item.items || item.items.length === 0" class="p-8 text-center bg-slate-50 dark:bg-slate-900 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                            <i class="bi bi-box-seam text-3xl text-slate-300 block mb-2"></i>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Belum ada data unit fisik tunggal yang digenerasi.</p>
                        </div>

                        <div v-else class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-700">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-[9px] font-black text-slate-400 uppercase tracking-widest italic border-b border-slate-100 dark:border-slate-700">
                                        <th class="px-6 py-4">No.</th>
                                        <th class="px-6 py-4">Nomor Aset</th>
                                        <th class="px-6 py-4">Status Fisik</th>
                                        <th class="px-6 py-4">Peminjam Saat Ini</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <tr v-for="(unit, index) in item.items" :key="unit.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20 transition-colors">
                                        <td class="px-6 py-4 font-mono text-slate-400">{{ index + 1 }}</td>
                                        
                                        <td class="px-6 py-4 font-mono text-blue-600 dark:text-blue-400 tracking-wider uppercase">
                                            <i class="bi bi-barcode mr-1.5 opacity-60"></i>{{ unit.barcode_aset ?? `UNIT-${unit.id}` }}
                                        </td>
                                        
                                        <td class="px-6 py-4">
                                            <span v-if="unit.status === 'tersedia'" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-md italic">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                            </span>
                                            <span v-else-if="unit.status === 'dipinjam'" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded-md italic">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Dipinjam
                                            </span>
                                            <span v-else-if="unit.status === 'dipakai_di_lab'" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-md italic">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Di Lab
                                            </span>
                                            <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[9px] font-black uppercase tracking-widest rounded-md italic">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> {{ unit.status }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 italic font-medium">
                                            <div v-if="(['dipinjam', 'dipakai_di_lab'].includes(unit.status)) && unit.peminjam_aktif" 
                                                class="text-slate-800 dark:text-slate-200 font-bold uppercase flex items-center gap-2">
                                                <i class="bi bi-person-circle text-sm text-slate-400"></i>
                                                {{ unit.peminjam_aktif }}
                                            </div>
                                            <div v-else class="text-slate-400 text-[10px] tracking-wide uppercase font-bold">
                                                —
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-6 flex items-center italic">
                            <i class="bi bi-card-text mr-3 text-blue-600"></i> Deskripsi & Kegunaan
                        </h3>
                        <div class="prose dark:prose-invert prose-sm max-w-none text-slate-500 dark:text-slate-400 leading-relaxed italic" v-html="renderedMarkdown"></div>
                    </div>

                    <div v-if="!isKatalog && canModifyOrDelete" class="bg-slate-900 dark:bg-slate-950 rounded-[2rem] p-8 border border-slate-800 shadow-inner">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-4 flex items-center italic">
                            <i class="bi bi-info-circle mr-3 text-amber-500"></i> Catatan Internal Lab
                        </h3>
                        <p class="font-mono text-xs text-amber-500/80 bg-amber-500/5 p-4 rounded-xl border border-amber-500/10 italic leading-loose">
                            {{ item.catatan_lokasi ?? 'Tidak ada catatan spesifik lokasi penyimpanan.' }}
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div v-if="canViewUnitDetails" class="bg-amber-50 dark:bg-amber-900/10 rounded-[2rem] p-6 border border-amber-100 dark:border-amber-900/30">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600">
                                <i class="bi bi-person-walking text-2xl"></i>
                            </div>
                            <div>
                                <h6 class="text-[10px] font-black text-amber-800 dark:text-amber-500 uppercase tracking-widest italic">Status Keluar</h6>
                                <p class="text-xs text-amber-700 dark:text-amber-600 italic">
                                    <b class="text-lg">{{ totalDipinjam }}</b> Unit sedang di luar
                                </p>
                            </div>
                        </div>
                    </div>

                    <div :class="isLoggedIn ? 'bg-blue-600 shadow-blue-200' : 'bg-slate-100 dark:bg-slate-800'" class="rounded-[2.5rem] p-8 text-center shadow-xl dark:shadow-none transition-all">
                        <h6 class="text-[10px] font-black uppercase tracking-[0.2em] mb-2" :class="isLoggedIn ? 'text-blue-100' : 'text-slate-400'">Stok Tersedia</h6>
                        <template v-if="isLoggedIn">
                            <div class="text-6xl font-black text-white italic tracking-tighter mb-1">{{ stok_tersedia }}</div>
                            <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest italic">Unit Siap Pakai</p>
                        </template>
                        <template v-else>
                            <div class="py-6 flex flex-col items-center gap-4">
                                <i class="bi bi-shield-lock text-3xl text-slate-300"></i>
                                <Link :href="route('login')" class="text-[10px] font-black uppercase tracking-widest px-6 py-3 bg-white text-slate-900 rounded-full shadow-sm hover:bg-slate-50 transition-all italic">Login untuk Cek Stok</Link>
                            </div>
                        </template>
                    </div>

                    <div v-if="isKatalog && item.tipe_peminjaman === 'Bisa Dipinjam'">
                        <template v-if="isLoggedIn">
                            <Link :disabled="stok_tersedia <= 0"
                                  :href="stok_tersedia > 0 ? route('peminjaman.create', { inventaris_id: item.id }) : '#'"
                                  as="button"
                                  class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white rounded-[1.5rem] shadow-lg shadow-emerald-200 dark:shadow-none font-black text-xs uppercase tracking-[0.2em] transition-all hover:-translate-y-1 italic flex items-center justify-center gap-3">
                                <i class="bi bi-plus-circle text-lg"></i> Ajukan Peminjaman
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" 
                               class="w-full py-5 bg-slate-900 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] transition-all hover:bg-black italic flex items-center justify-center gap-3">
                                <i class="bi bi-box-arrow-in-right text-lg"></i> Login Meminjam
                            </Link>
                        </template>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic ml-1">Kondisi Alat</h6>
                        <div v-if="isLoggedIn">
                            <div v-if="item.kondisi === 'Baik'" class="flex items-center gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800/30">
                                <i class="bi bi-check-circle-fill text-2xl text-emerald-500"></i>
                                <div>
                                    <p class="text-xs font-black text-emerald-800 dark:text-emerald-400 uppercase italic">Kondisi Baik</p>
                                    <p class="text-[10px] text-emerald-600/70 italic leading-none">Siap Dioperasikan</p>
                                </div>
                            </div>
                            <div v-else class="flex items-center gap-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-800/30">
                                <i class="bi bi-exclamation-triangle-fill text-2xl text-amber-500"></i>
                                <div>
                                    <p class="text-xs font-black text-amber-800 dark:text-amber-400 uppercase italic">{{ item.kondisi }}</p>
                                    <p class="text-[10px] text-amber-600/70 italic leading-none">Segera Hubungi Teknisi</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl text-center" v-else>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic leading-relaxed">
                                <i class="bi bi-shield-lock mr-1"></i> Detail Kondisi Diproteksi
                            </p>
                        </div>
                    </div>

                    <div v-if="isLoggedIn" class="flex items-center justify-between px-6 py-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Update Terakhir</span>
                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 italic">{{ formatDate(item.updated_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
textarea::-webkit-scrollbar {
    width: 6px;
}
textarea::-webkit-scrollbar-track {
    background: transparent;
}
textarea::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.dark textarea::-webkit-scrollbar-thumb {
    background: #475569;
}
</style>