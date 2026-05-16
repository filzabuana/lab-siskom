<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

const photoPreview = ref(null);

const form = useForm({
    kode_barang: '',
    nama_aset: '',
    kategori: 'Mikrokontroler',
    merk: '',
    nup: '',
    ruangan: 'Lab Komputasi',
    tahun_perolehan: new Date().getFullYear(),
    jumlah_stok: 0,
    jumlah_rusak: 0,
    kondisi: 'Baik',
    tipe_peminjaman: 'Bisa Dipinjam',
    deskripsi: '',
    catatan_lokasi: '',
    foto_barang: null,
});

const updatePhotoPreview = (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
        form.foto_barang = file;
    }
};

const submit = () => {
    form.post(route('admin.inventaris.store'), {
        forceFormData: true, 
        onSuccess: () => {
            form.reset();
            photoPreview.value = null;
        },
    });
};
</script>

<template>
    <Head title="Registrasi Aset" />

    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
            
            <div class="flex items-center gap-4 mb-8">
                <Link :href="route('admin.inventaris.index')" 
                   class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                    <i class="bi bi-arrow-left text-lg"></i>
                </Link>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic">Registrasi Aset</h2>
                    <p class="text-[10px] md:text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Pendaftaran Barang Baru Lab</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="p-6 md:p-10">
                    <form @submit.prevent="submit" class="space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Kode Barang (TIK)</label>
                                <input 
                                    v-model="form.kode_barang"
                                    type="text" 
                                    :class="{'border-red-500': form.errors.kode_barang}"
                                    class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all font-mono text-sm" 
                                    placeholder="Contoh: LAB-IOT-005"
                                >
                                <p v-if="form.errors.kode_barang" class="text-[9px] text-red-500 mt-2 ml-1 font-bold uppercase italic">{{ form.errors.kode_barang }}</p>
                            </div>
                            <div class="md:col-span-8">
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Nama Aset / Perangkat</label>
                                <input 
                                    v-model="form.nama_aset"
                                    type="text" 
                                    class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all outline-none" 
                                    placeholder="Masukkan nama lengkap barang"
                                >
                                <p v-if="form.errors.nama_aset" class="text-[9px] text-red-500 mt-2 ml-1 font-bold uppercase italic">{{ form.errors.nama_aset }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Kategori</label>
                                <select v-model="form.kategori" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none appearance-none">
                                    <option value="Mikrokontroler">Mikrokontroler</option>
                                    <option value="Sensor">Sensor/Module</option>
                                    <option value="Komputer">Komputer/Laptop</option>
                                    <option value="Trainer Kit">Trainer Kit</option>
                                    <option value="Alat Ukur">Alat Ukur</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Merk / Brand</label>
                                <input v-model="form.merk" type="text" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none" placeholder="Dell, Apple, Arduino, dll">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">NUP (Nomor Urut Pusat)</label>
                                <input v-model="form.nup" type="text" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none" placeholder="Jika ada">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-slate-50/50 dark:bg-slate-900/40 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                            <div class="col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 italic">Ruangan</label>
                                <input v-model="form.ruangan" type="text" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 text-sm outline-none focus:border-blue-600" placeholder="Lab Komputasi">
                            </div>
                            <div class="col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 italic">Tahun</label>
                                <input v-model="form.tahun_perolehan" type="number" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 text-sm outline-none focus:border-blue-600">
                            </div>
                            <div class="col-span-1">
                                <label class="block text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-2 italic">Stok Baik</label>
                                <input v-model="form.jumlah_stok" type="number" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-900 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 font-bold text-emerald-700 dark:text-emerald-400" min="0">
                            </div>
                            <div class="col-span-1">
                                <label class="block text-[10px] font-black text-red-600 dark:text-red-500 uppercase tracking-widest mb-2 italic">Jumlah Rusak</label>
                                <input v-model="form.jumlah_rusak" type="number" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-900 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500/20 font-bold text-red-700 dark:text-red-400" min="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Status Kondisi Umum</label>
                                <select v-model="form.kondisi" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-200 outline-none focus:border-blue-600 appearance-none">
                                    <option value="Baik">🟢 Baik (Siap Pakai)</option>
                                    <option value="Rusak Ringan">🟡 Rusak Ringan</option>
                                    <option value="Rusak Berat">🔴 Rusak Berat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1 italic">Kebijakan Peminjaman</label>
                                <select v-model="form.tipe_peminjaman" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-blue-100 dark:border-blue-900 rounded-2xl text-slate-800 dark:text-slate-200 outline-none focus:ring-2 focus:ring-blue-600/20 appearance-none font-bold">
                                    <option value="Hanya di Lab">Statis (Hanya di Lab)</option>
                                    <option value="Bisa Dipinjam">Mobile (Bisa Dipinjam)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Deskripsi & Fungsi Alat</label>
                                <textarea v-model="form.deskripsi" rows="4" class="w-full px-5 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-[1.5rem] text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 outline-none resize-y min-h-[120px] scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600" placeholder="Kegunaan alat untuk mahasiswa..."></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1 italic">Catatan Lokasi (Internal)</label>
                                <textarea v-model="form.catatan_lokasi" rows="4" class="w-full px-5 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-[1.5rem] text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-600/20 outline-none resize-y min-h-[120px] scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600" placeholder="Contoh: Lemari C, Laci 4..."></textarea>
                            </div>
                        </div>

                        <div class="p-8 bg-slate-50 dark:bg-slate-900/60 rounded-[2rem] border border-dashed border-slate-300 dark:border-slate-700 text-center">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4 italic">Unggah Foto Perangkat</label>
                            
                            <div v-if="photoPreview" class="flex justify-center mb-6">
                                <div class="relative w-32 h-32 md:w-40 md:h-40">
                                    <img :src="photoPreview" class="w-full h-full object-cover rounded-3xl shadow-xl border-4 border-white dark:border-slate-700 transition-transform hover:scale-105" />
                                </div>
                            </div>

                            <div class="max-w-xs mx-auto">
                                <input 
                                    type="file" 
                                    @change="updatePhotoPreview"
                                    class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-slate-900 dark:file:bg-slate-700 file:text-white hover:file:bg-slate-800 dark:hover:file:bg-slate-600 transition-all cursor-pointer shadow-lg shadow-slate-200 dark:shadow-none"
                                >
                                <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-4 italic">Format: JPG, PNG, atau WEBP. Maks 2MB.</p>
                                <p v-if="form.errors.foto_barang" class="text-[9px] text-red-500 mt-2 font-bold uppercase italic">{{ form.errors.foto_barang }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-end gap-4 pt-6 border-t border-slate-100 dark:border-slate-700">
                            <Link :href="route('admin.inventaris.index')" class="w-full md:w-auto text-center px-8 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] hover:text-slate-600 dark:hover:text-slate-300 transition-colors italic">Batalkan</Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full md:w-auto px-10 py-4 bg-blue-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl md:rounded-full shadow-xl shadow-slate-200 dark:shadow-none hover:-translate-y-1 active:scale-95 transition-all italic disabled:opacity-50"
                            >
                                <i class="bi bi-plus-circle-fill mr-2"></i> 
                                {{ form.processing ? 'Mendaftarkan...' : 'Daftarkan Aset Baru' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom scrollbar styling for textareas */
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