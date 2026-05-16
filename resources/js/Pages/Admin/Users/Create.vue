<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    roles: Array // Menerima: ['mahasiswa', 'asisten_praktikum', 'plp', 'dosen', 'kalab']
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: [],
    is_admin: false, // Default false, terikat ke toggle amber
});

const submit = () => {
    form.post(route('admin.users.store'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {
            // Opsional: tambahkan notifikasi sukses di sini
        }
    });
};
</script>

<template>
    <Head title="Registrasi User" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto px-4 py-10">
            <div class="mb-8 flex items-center gap-4">
                <Link :href="route('admin.users.index')" 
                    class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                    <i class="mdi mdi-arrow-left text-xl"></i>
                </Link>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-none">Registrasi User</h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic mt-1">Sistem Manajemen Identitas Laboratorium FMIPA</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 md:p-12 border border-slate-100 dark:border-slate-700 shadow-2xl shadow-slate-200/50 dark:shadow-none relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl"></div>

                <form @submit.prevent="submit" class="space-y-8 relative">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Nama Lengkap</label>
                            <input v-model="form.name" type="text" required placeholder="Nama Lengkap User"
                                class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 dark:text-white transition-all outline-none">
                            <div v-if="form.errors.name" class="text-[10px] text-rose-500 font-bold italic ml-2">{{ form.errors.name }}</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Email Institusi</label>
                            <input v-model="form.email" type="email" required placeholder="username@untan.ac.id"
                                class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 dark:text-white transition-all outline-none">
                            <div v-if="form.errors.email" class="text-[10px] text-rose-500 font-bold italic ml-2">{{ form.errors.email }}</div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Password Keamanan</label>
                        <input v-model="form.password" type="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 dark:text-white transition-all outline-none">
                        <div v-if="form.errors.password" class="text-[10px] text-rose-500 font-bold italic ml-2">{{ form.errors.password }}</div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic ml-1">Role Laboratorium</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <label v-for="role in roles" :key="role" 
                                class="group flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-500/5 transition-all">
                                <input type="checkbox" :value="role" v-model="form.roles"
                                       class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500/20 transition-all cursor-pointer bg-white dark:bg-slate-800">
                                <span class="text-[11px] font-black text-slate-600 dark:text-slate-300 uppercase italic group-hover:text-blue-600">
                                    {{ role.replace('_', ' ') }}
                                </span>
                            </label>
                        </div>
                        <div v-if="form.errors.roles" class="text-[10px] text-rose-500 font-bold italic ml-2">{{ form.errors.roles }}</div>
                    </div>

                    <div class="p-6 bg-amber-500/5 border border-amber-500/10 rounded-[2rem] group transition-all hover:bg-amber-500/10">
                        <label for="is_admin" class="flex items-center gap-4 cursor-pointer">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" v-model="form.is_admin" id="is_admin" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500 shadow-inner"></div>
                            </div>
                            <div class="flex flex-col flex-1">
                                <span class="text-[10px] font-black text-amber-600 uppercase italic tracking-widest group-hover:tracking-[0.15em] transition-all leading-tight">Hak Akses Superadmin</span>
                                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mt-0.5 italic">Aktivasi akses administrator utama (Bypass sistem keamanan).</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            :disabled="form.processing"
                            class="w-full py-5 bg-slate-900 dark:bg-blue-600 text-white text-xs font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl hover:bg-blue-600 transition-all italic active:scale-95 shadow-blue-500/20 disabled:opacity-50 flex items-center justify-center gap-3">
                            <i v-if="form.processing" class="mdi mdi-loading mdi-spin text-lg"></i>
                            <span>{{ form.processing ? 'MEMPROSES...' : 'SIMPAN DATA PENGGUNA' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>