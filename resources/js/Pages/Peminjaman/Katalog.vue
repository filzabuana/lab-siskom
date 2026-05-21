<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, Link, Head, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    katalog: Object,
    keranjang: Array,
    filters: Object,
    kategoriList: Array,
});

// State reaktif untuk filter pencarian dan kategori
const search = ref(props.filters?.search || '');
const selectedKategori = ref(props.filters?.kategori || '');

// State internal untuk notifikasi sukses
const showToast = ref(false);
const toastMessage = ref('');

const form = useForm({
    inventaris_id: null,
    jumlah: 1,
});

// Fungsi utama mengirim request filter ke server
const handleSearchFilter = () => {
    router.get(
        route('peminjaman.katalog'), 
        { 
            search: search.value, 
            kategori: selectedKategori.value 
        }, 
        { 
            preserveState: true, 
            preserveScroll: true,
            replace: true // Mencegah tumpukan history browser yang terlalu banyak saat mengetik
        }
    );
};

// Variable penampung timer debounce pencarian otomatis
let debounceTimer = null;

// Watcher untuk mendeteksi perubahan input pencarian secara real-time (Seamless Search)
watch(search, (newVal) => {
    // Bersihkan timer sebelumnya jika user masih aktif mengetik
    clearTimeout(debounceTimer);
    
    // Berikan jeda 400ms setelah ketikan terakhir berhenti, baru kirim request ke server
    debounceTimer = setTimeout(() => {
        handleSearchFilter();
    }, 400);
});

// Watcher untuk kategori (Langsung filter saat dropdown berubah)
watch(selectedKategori, () => {
    handleSearchFilter();
});

// Reset pencarian dan filter ke kondisi awal
const resetFilter = () => {
    search.value = '';
    selectedKategori.value = '';
};

const addToCart = (item) => {
    form.inventaris_id = item.id;
    form.jumlah = 1;
    
    form.post(route('peminjaman.cart.add'), {
        preserveScroll: true,
        onSuccess: () => {
            const flashMessage = usePage().props.flash?.message;
            toastMessage.value = flashMessage || `Berhasil menambahkan ${item.nama_aset} ke keranjang!`;
            showToast.value = true;
            
            setTimeout(() => {
                showToast.value = false;
            }, 3500);
        },
    });
};

const totalCartItems = computed(() => props.keranjang ? props.keranjang.length : 0);

const daftarKategori = computed(() => {
    if (props.kategoriList && props.kategoriList.length > 0) return props.kategoriList;
    return ['Hardware', 'Software', 'Mikrokontroler', 'IoT', 'Modul', 'Kabel', 'Lainnya'];
});
</script>

<template>
    <Head title="Katalog Alat" />

    <AuthenticatedLayout>
        <Transition name="toast-fade">
            <div v-if="showToast" 
                 class="fixed top-20 right-4 z-[999] max-w-sm w-full bg-zinc-900/90 dark:bg-railway-card/95 backdrop-blur border-l-4 border-railway-accent text-zinc-100 p-4 rounded-xl shadow-2xl flex items-center gap-3 font-mono border border-zinc-800 dark:border-railway-border">
                <div class="w-7 h-7 shrink-0 rounded-lg bg-railway-accent/10 flex items-center justify-center text-railway-accent animate-pulse">
                    <i class="bi bi-cart-check-fill text-base"></i>
                </div>
                <div class="flex-grow text-xs font-bold uppercase tracking-tight leading-snug">
                    {{ toastMessage }}
                </div>
                <button @click="showToast = false" class="text-zinc-500 hover:text-zinc-200 transition-colors">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
        </Transition>

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-lg sm:text-xl text-zinc-800 dark:text-zinc-100 leading-tight italic tracking-tighter uppercase">
                    Katalog <span class="text-railway-accent">Alat</span>
                </h2>
                
                <Link :href="route('peminjaman.cart.view')" 
                    class="relative p-2 text-zinc-600 dark:text-zinc-400 hover:text-railway-accent dark:hover:text-railway-accent transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span v-if="totalCartItems > 0" 
                        class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-black leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-railway-accent rounded-full border-2 border-white dark:border-railway-dark">
                        {{ totalCartItems }}
                    </span>
                </Link>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tight italic">
                        Daftar Inventaris <span class="text-railway-accent">Lab</span> Berstatus Available
                    </h1>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 font-mono mt-1">
                        Pilih alat praktek atau komponen mikro yang Anda butuhkan untuk operasional riset.
                    </p>
                </div>
                
                <div class="mb-8 bg-white dark:bg-railway-card p-4 rounded-2xl border border-zinc-100 dark:border-railway-border shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
                    <div class="w-full flex flex-col sm:flex-row gap-2 flex-grow max-w-3xl">
                        <div class="relative flex-grow">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-zinc-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <input v-model="search" type="text" placeholder="Ketik untuk mencari (contoh: atmega, sensor)..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border-zinc-200 dark:border-railway-border bg-zinc-50/50 dark:bg-railway-dark text-zinc-900 dark:text-zinc-100 text-sm focus:ring-railway-accent focus:border-railway-accent shadow-sm font-mono">
                        </div>

                        <div class="w-full sm:w-52 shrink-0">
                            <select v-model="selectedKategori"
                                    class="w-full px-3 py-2.5 rounded-xl border-zinc-200 dark:border-railway-border bg-zinc-50/50 dark:bg-railway-dark text-zinc-900 dark:text-zinc-100 text-sm focus:ring-railway-accent focus:border-railway-accent shadow-sm font-mono uppercase tracking-tighter text-xs font-black">
                                <option value="">Semua Kategori</option>
                                <option v-for="cat in daftarKategori" :key="cat" :value="cat">{{ cat }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex gap-2 shrink-0 justify-end">
                        <button v-if="search || selectedKategori" @click="resetFilter"
                                class="px-5 py-2.5 rounded-xl border border-zinc-200 dark:border-railway-border text-zinc-500 hover:text-red-500 font-mono text-xs uppercase font-black transition-all flex items-center gap-1.5 bg-zinc-50 dark:bg-railway-dark/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M4 7h16" />
                            </svg>
                            Clear Filter
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                    <div v-for="item in katalog.data" :key="item.id" 
                        class="bg-white dark:bg-railway-card overflow-hidden shadow-sm rounded-2xl border border-zinc-100 dark:border-railway-border flex flex-col transition-all hover:border-railway-accent/50 group"
                        :class="{'opacity-50 grayscale': item.jumlah_stok <= 0}">
                        
                        <div class="h-32 sm:h-48 overflow-hidden bg-zinc-100 dark:bg-railway-dark relative">
                            <Link :href="route('peminjaman.show', { id: item.id })" class="block w-full h-full">
                                <img v-if="item.foto_barang" :src="`/storage/inventaris/${item.foto_barang}`" 
                                    :alt="item.nama_aset" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div v-else class="flex items-center justify-center h-full text-zinc-400 text-[10px] font-mono italic">
                                    [no_img]
                                </div>
                            </Link>
                            
                            <div class="absolute bottom-2 right-2">
                                <span :class="item.jumlah_stok > 0 ? 'bg-green-500/90' : 'bg-red-500/90'" 
                                    class="text-[9px] sm:text-xs text-white px-2 py-0.5 rounded-full font-black backdrop-blur-sm">
                                    {{ item.jumlah_stok > 0 ? item.jumlah_stok + ' Ready' : 'Empty' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 flex-grow">
                            <div class="mb-1">
                                <span class="text-[9px] sm:text-xs font-black text-railway-accent uppercase tracking-tighter">{{ item.kategori }}</span>
                            </div>
                            <Link :href="route('peminjaman.show', { id: item.id })" class="group/title block">
                                <h3 class="text-sm sm:text-lg font-black text-zinc-900 dark:text-zinc-100 leading-tight mb-1 italic uppercase truncate group-hover/title:text-railway-accent transition-colors">
                                    {{ item.nama_aset }}
                                </h3>
                            </Link>
                            <p class="text-[10px] sm:text-sm text-zinc-500 dark:text-zinc-400 line-clamp-1 font-mono">
                                {{ item.merk || 'No Brand' }}
                            </p>
                        </div>

                        <div class="p-3 sm:p-4 pt-0">
                            <button @click="addToCart(item)" 
                                :disabled="item.jumlah_stok <= 0 || form.processing"
                                class="w-full py-2.5 sm:py-3 px-2 rounded-xl font-black text-[10px] sm:text-xs transition-all italic uppercase tracking-tighter shadow-lg active:scale-95"
                                :class="item.jumlah_stok > 0 
                                    ? 'bg-railway-accent text-white shadow-railway-accent/20 hover:brightness-110' 
                                    : 'bg-zinc-200 dark:bg-railway-dark text-zinc-400 cursor-not-allowed shadow-none'">
                                {{ item.jumlah_stok > 0 ? '+ Keranjang' : 'Habis' }}
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="katalog.data.length === 0" class="text-center py-20">
                    <p class="font-mono text-zinc-500 text-sm italic uppercase tracking-widest">Alat tidak ditemukan...</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-mono {
    font-family: 'Fira Code', 'Cascadia Code', monospace;
}
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
/* Transisi Animasi Toast Fade */
.toast-fade-enter-active, .toast-fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.toast-fade-enter-from {
    transform: translateX(100%) scale(0.9);
    opacity: 0;
}
.toast-fade-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}
</style>