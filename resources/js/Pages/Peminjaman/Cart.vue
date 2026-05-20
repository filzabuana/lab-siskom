<script setup>
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    keranjang: Array,
});

const form = useForm({
    keperluan: '',
    tgl_pinjam: '',
    tgl_kembali_rencana: '',
});

const totalItems = computed(() => props.keranjang.length);

const loanDuration = computed(() => {
    if (!form.tgl_pinjam || !form.tgl_kembali_rencana) return 0;
    const start = new Date(form.tgl_pinjam);
    const end = new Date(form.tgl_kembali_rencana);
    const diffTime = end - start;
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
});

const isDurationInvalid = computed(() => loanDuration.value > 30 || loanDuration.value < 0);

const updateQuantity = (id, newQty, stock) => {
    // Sanitasi input: pastikan minimal 1 dan maksimal sesuai stok
    const sanitizedQty = Math.max(1, Math.min(newQty, stock));
    
    router.patch(route('peminjaman.cart.update', id), {
        jumlah: sanitizedQty
    }, { preserveScroll: true });
};

const submitCheckout = () => {
    if (isDurationInvalid.value) {
        alert('Durasi peminjaman maksimal adalah 30 hari.');
        return;
    }
    form.post(route('peminjaman.checkout'));
};

const removeFromCart = (id) => {
    if (confirm('Hapus alat ini dari keranjang?')) {
        router.delete(route('peminjaman.cart.destroy', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Keranjang Peminjaman" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-black text-xl text-slate-800 dark:text-white leading-tight italic uppercase tracking-tighter">
                Konfirmasi Peminjaman Alat
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <div class="w-full lg:w-2/3">
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                            <div class="p-4 sm:p-6 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <h3 class="font-black text-sm sm:text-base text-slate-900 dark:text-white uppercase italic">Daftar Alat ({{ totalItems }})</h3>
                                <Link :href="route('peminjaman.katalog')" class="text-[10px] sm:text-xs text-blue-600 dark:text-blue-400 font-black uppercase tracking-widest hover:underline">
                                    + Tambah
                                </Link>
                            </div>

                            <div v-if="keranjang.length > 0">
                                <div v-for="item in keranjang" :key="item.id" 
                                     class="p-4 sm:p-6 border-b border-slate-50 dark:border-slate-700 last:border-0 flex items-start sm:items-center gap-4 sm:gap-6">
                                    
                                    <img :src="`/storage/inventaris/${item.inventaris.foto_barang}`" 
                                         class="w-16 h-16 sm:w-24 sm:h-24 object-cover rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-slate-700 flex-shrink-0">
                                    
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-black text-xs sm:text-base text-slate-900 dark:text-white uppercase italic leading-tight truncate">
                                            {{ item.inventaris.nama_aset }}
                                        </h4>
                                        <p class="text-[9px] sm:text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-widest truncate">
                                            {{ item.inventaris.kode_barang }}
                                        </p>
                                        
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-3">
                                            <div class="flex items-center w-fit bg-slate-100 dark:bg-slate-900 rounded-lg p-0.5 border border-slate-200 dark:border-slate-700">
                                                <button @click="updateQuantity(item.id, item.jumlah - 1, item.inventaris.jumlah_stok)" 
                                                        :disabled="item.jumlah <= 1"
                                                        class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-white dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 disabled:opacity-30 transition-all">
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                                
                                                <input type="number" 
                                                       :value="item.jumlah"
                                                       @change="(e) => updateQuantity(item.id, Number(e.target.value), item.inventaris.jumlah_stok)"
                                                       class="w-12 text-center font-black text-xs bg-transparent border-none focus:ring-0 p-0 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                                                
                                                <button @click="updateQuantity(item.id, item.jumlah + 1, item.inventaris.jumlah_stok)" 
                                                        :disabled="item.jumlah >= item.inventaris.jumlah_stok"
                                                        class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-white dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 disabled:opacity-30 transition-all">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                            
                                            <span class="text-[9px] font-bold text-slate-400 uppercase italic">
                                                Tersedia: {{ item.inventaris.jumlah_stok }}
                                            </span>
                                        </div>
                                    </div>

                                    <button @click="removeFromCart(item.id)" 
                                            class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 flex items-center justify-center rounded-lg sm:rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="bi bi-trash3-fill text-xs sm:text-base"></i>
                                    </button>
                                </div>
                            </div>

                            <div v-else class="p-16 text-center">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-cart-x text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 italic text-sm">Keranjang kosong.</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/3">
                        <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-6 border border-blue-50 dark:border-slate-700 lg:sticky lg:top-24">
                            <h3 class="font-black text-slate-900 dark:text-white uppercase italic mb-6 border-b dark:border-slate-700 pb-4 text-sm sm:text-base">Konfirmasi Pinjam</h3>
                            
                            <form @submit.prevent="submitCheckout" class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase italic mb-1 tracking-widest">Tujuan / Keperluan</label>
                                    <textarea v-model="form.keperluan" rows="3" required
                                              placeholder="Contoh: Pengambilan data skripsi..."
                                              class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 placeholder:italic"></textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase italic mb-1 tracking-widest">Mulai Pinjam</label>
                                        <input type="date" v-model="form.tgl_pinjam" required
                                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white text-sm focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase italic mb-1 tracking-widest">Rencana Kembali</label>
                                        <input type="date" v-model="form.tgl_kembali_rencana" required
                                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white text-sm focus:ring-blue-500">
                                    </div>
                                </div>

                                <Transition name="fade">
                                    <div v-if="loanDuration > 0" 
                                         :class="isDurationInvalid ? 'bg-red-50 dark:bg-red-500/10 border-red-200 text-red-600' : 'bg-blue-50 dark:bg-blue-500/10 border-blue-200 text-blue-600'"
                                         class="p-4 rounded-xl border text-[11px] font-bold italic leading-relaxed">
                                        <i class="bi" :class="isDurationInvalid ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill'"></i>
                                        Durasi: {{ loanDuration }} hari.
                                        <span v-if="isDurationInvalid" class="block mt-1 uppercase font-black tracking-tighter">Maksimal 30 hari!</span>
                                    </div>
                                </Transition>

                                <div class="pt-2">
                                    <button type="submit" 
                                            :disabled="form.processing || keranjang.length === 0 || isDurationInvalid"
                                            class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black uppercase italic tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 dark:shadow-none disabled:bg-slate-300 dark:disabled:bg-slate-700 flex items-center justify-center gap-2">
                                        <i class="bi bi-send-fill" v-if="!form.processing"></i>
                                        {{ form.processing ? 'Mengirim...' : 'Kirim Permintaan' }}
                                    </button>
                                    <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-4 text-center leading-tight uppercase font-bold tracking-tighter italic">
                                        Data akan diperiksa oleh PLP LAB SISKOM.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input[type="date"]::-webkit-calendar-picker-indicator {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/></svg>');
    filter: invert(0.4);
    cursor: pointer;
}
.dark input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(100%);
}
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>