<script>
// Kita gunakan Persistent Layout agar sidebar tidak hilang & tidak re-render
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    layout: AuthenticatedLayout,
}
</script>

<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash'; 

const props = defineProps({
    semuaInventaris: Object,
    filters: Object,
    listKategori: Array,
    listRuangan: Array,
    listLokasi: Array
});

// State untuk filter
const search = ref(props.filters.search || '');
const kategori = ref(props.filters.kategori ?? '');
const ruangan = ref(props.filters.ruangan ?? '');
const lokasi = ref(props.filters.lokasi ?? '');

// Fungsi Navigasi Manual (Solusi klik mobile untuk Link dan Button)
const navigateTo = (routeName, id = null) => {
    if (id) {
        router.visit(route(routeName, id));
    } else {
        router.visit(route(routeName));
    }
};

// Fungsi untuk hit API filter
const applyFilter = () => {
    router.get(route('admin.inventaris.index'), {
        search: search.value,
        kategori: kategori.value,
        ruangan: ruangan.value,
        lokasi: lokasi.value
    }, {
        preserveState: true,
        replace: true
    });
};

// Auto search saat ngetik (debounce 500ms)
watch(search, debounce(() => {
    applyFilter();
}, 500));

const destroy = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus aset ini secara permanen?')) {
        router.delete(route('admin.inventaris.destroy', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Inventaris Lab" />

    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="min-w-0">
                <h2 class="text-xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic flex items-center">
                    <span class="w-1.5 h-6 md:w-2 md:h-10 bg-blue-600 mr-3 rounded-full flex-shrink-0"></span>
                    <span class="truncate">Manajemen Inventaris</span>
                </h2>
                <p class="text-slate-500 dark:text-slate-400 font-bold text-[9px] md:text-xs mt-1 uppercase tracking-[0.2em]">
                    Unit Komputasi FMIPA Universitas Tanjungpura
                </p>
            </div>
            
            <button @click="navigateTo('admin.inventaris.create')" 
               class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-600/20 transition-all hover:-translate-y-1 active:scale-95">
                <i class="bi bi-plus-circle-fill me-2 text-base"></i> 
                Tambah Aset Baru
            </button>
        </div>

        <div class="mb-8 p-4 md:p-6 bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex flex-col gap-4">
                <div class="relative w-full group">
                    <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                    <input type="text" v-model="search" placeholder="CARI BERDASARKAN NAMA ATAU KODE ASET..." 
                           class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-blue-500 italic shadow-inner transition-all">
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:flex lg:flex-row gap-3">
                    <select v-model="kategori" @change="applyFilter" class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl text-[10px] font-black uppercase tracking-widest px-4 py-4 focus:ring-2 focus:ring-blue-500 italic text-slate-600 dark:text-slate-400 cursor-pointer shadow-sm">
                        <option value="">SEMUA KATEGORI</option>
                        <option v-for="cat in listKategori" :key="cat" :value="cat">{{ cat.toUpperCase() }}</option>
                    </select>

                    <select v-model="ruangan" @change="applyFilter" class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl text-[10px] font-black uppercase tracking-widest px-4 py-4 focus:ring-2 focus:ring-blue-500 italic text-slate-600 dark:text-slate-400 cursor-pointer shadow-sm">
                        <option value="">SEMUA RUANGAN</option>
                        <option v-for="ruang in listRuangan" :key="ruang" :value="ruang">{{ ruang.toUpperCase() }}</option>
                    </select>

                    <select v-model="lokasi" @change="applyFilter" class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl text-[10px] font-black uppercase tracking-widest px-4 py-4 focus:ring-2 focus:ring-blue-500 italic text-slate-600 dark:text-slate-400 cursor-pointer shadow-sm">
                        <option value="">SEMUA LOKASI</option>
                        <option v-for="lok in listLokasi" :key="lok" :value="lok">{{ lok.toUpperCase() }}</option>
                    </select>

                    <Link :href="route('admin.inventaris.index')" class="bg-slate-100 dark:bg-slate-900 rounded-xl flex items-center justify-center px-5 py-4 text-slate-500 hover:text-red-500 transition-all border border-transparent hover:border-red-500/20 shadow-sm" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise text-lg"></i>
                    </Link>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-[2.5rem] shadow-xl overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 text-left">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Informasi Aset</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Kategori & Ruang</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-center">Status Stok</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Posisi / Rak</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-right">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                        <tr v-for="item in semuaInventaris.data" :key="item.id" 
                            class="hover:bg-blue-50/30 dark:hover:bg-blue-500/5 transition-all group">
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                        <img v-if="item.foto_barang" :src="'/storage/inventaris/' + item.foto_barang" class="w-full h-full object-cover">
                                        <div v-else class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                            <i class="bi bi-box-seam text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[13px] font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight italic leading-tight truncate">
                                            {{ item.nama_aset }}
                                        </div>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="font-mono text-[9px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 px-2 py-0.5 rounded border border-blue-100 dark:border-blue-800/50 uppercase whitespace-nowrap">
                                                {{ item.kode_barang }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase italic">{{ item.kategori }}</div>
                                <div class="text-[9px] font-bold text-blue-500 uppercase tracking-tighter mt-1 flex items-center gap-1">
                                    <i class="bi bi-geo-alt-fill"></i>{{ item.ruangan }}
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <div class="text-xs font-black text-slate-800 dark:text-slate-100 italic tracking-tighter">{{ item.jumlah_stok }} UNITS</div>
                                <div class="mt-1">
                                    <span :class="item.jumlah_rusak > 0 ? 'text-red-500 bg-red-50 dark:bg-red-500/10 border-red-100 dark:border-red-500/20' : 'text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20'" 
                                          class="text-[8px] font-black uppercase italic px-2 py-0.5 rounded-md border whitespace-nowrap">
                                        {{ item.jumlah_rusak > 0 ? item.jumlah_rusak + ' Unit Rusak' : 'Kondisi Baik' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div class="font-mono text-[10px] text-blue-600 dark:text-blue-400 font-black italic bg-blue-50/50 dark:bg-blue-900/30 px-3 py-1.5 rounded-xl border border-blue-100/50 dark:border-blue-800/50 inline-block">
                                    {{ item.lokasi || item.catatan_lokasi || 'N/A' }}
                                </div>
                            </td>

                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="navigateTo('admin.inventaris.show', item.id)" 
                                          class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-900 rounded-xl text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                          title="Detail">
                                        <i class="bi bi-eye-fill text-sm"></i>
                                    </button>
                                    
                                    <button @click="navigateTo('admin.inventaris.edit', item.id)" 
                                          class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-900 rounded-xl text-amber-500 hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                          title="Edit">
                                        <i class="bi bi-pencil-fill text-sm"></i>
                                    </button>
                                    
                                    <button @click="destroy(item.id)" 
                                            class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-900 rounded-xl text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                            title="Hapus">
                                        <i class="bi bi-trash3-fill text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="semuaInventaris.data.length === 0">
                            <td colspan="5" class="py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                                        <i class="bi bi-search text-3xl text-slate-200"></i>
                                    </div>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Data inventaris tidak ditemukan</p>
                                    <button @click="search = ''; kategori = ''; ruangan = ''; lokasi = ''; applyFilter();" class="mt-4 text-blue-600 font-black text-[10px] uppercase underline underline-offset-4 tracking-widest">Bersihkan semua filter</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest">
                    Showing {{ semuaInventaris.from || 0 }} to {{ semuaInventaris.to || 0 }} of {{ semuaInventaris.total }} Assets
                </div>
                <div class="flex gap-1.5 overflow-x-auto max-w-full pb-2 md:pb-0">
                    <Link v-for="link in semuaInventaris.links" :key="link.label"
                          :href="link.url || '#'"
                          v-html="link.label"
                          class="px-4 py-2 text-[10px] font-black rounded-xl transition-all whitespace-nowrap"
                          :class="[
                              link.active ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'bg-white dark:bg-slate-900 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800',
                              !link.url ? 'opacity-30 cursor-not-allowed' : ''
                          ]"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
    animation: slideIn 0.4s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .overflow-x-auto::-webkit-scrollbar-thumb {
    background: #334155;
}
</style>