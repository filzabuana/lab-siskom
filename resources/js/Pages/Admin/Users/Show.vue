<template>
    <Head title="Manajemen Otoritas" />

    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <Link 
                        :href="route('admin.users.index')" 
                        class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                    >
                        <i class="mdi mdi-arrow-left text-xl"></i>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-none">
                            Manajemen Otoritas
                        </h1>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mt-1">
                            Sistem Informasi Laboratorium FMIPA UNTAN
                        </p>
                    </div>
                </div>

                <div v-if="$page.props.auth.user.is_admin">
                    <form @submit.prevent="handleImpersonate">
                        <button 
                            type="submit" 
                            class="group flex items-center gap-3 px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl shadow-xl hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all active:scale-95"
                        >
                            <i class="mdi mdi-incognito text-xl"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Login Sebagai User</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-2xl relative overflow-hidden">
                        <div class="text-center mb-8 relative">
                            <div class="w-28 h-28 rounded-[2.5rem] bg-gradient-to-br from-blue-500 to-indigo-600 text-white mx-auto flex items-center justify-center text-4xl font-black mb-4 border-8 border-slate-50 dark:border-slate-900 shadow-2xl overflow-hidden">
                                <img v-if="user.avatar" :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                <template v-else>{{ getInitials(user.name) }}</template>
                            </div>
                            
                            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase italic tracking-tight">{{ user.name }}</h2>
                            <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest mt-1">{{ user.email }}</p>
                            
                            <div class="mt-4 flex flex-wrap justify-center gap-2">
                                <span v-if="user.is_admin" class="px-3 py-1 bg-amber-500 text-white text-[9px] font-black uppercase rounded-full italic shadow-sm">Superadmin</span>
                                
                                <template v-if="user.roles_list && user.roles_list.length > 0">
                                    <span 
                                        v-for="role in user.roles_list" 
                                        :key="role"
                                        class="px-3 py-1 bg-blue-100 dark:bg-blue-500/10 text-blue-600 text-[9px] font-black uppercase rounded-full italic border border-blue-200"
                                    >
                                        {{ role.replace('_', ' ') }}
                                    </span>
                                </template>
                            </div>
                        </div>

                        <div v-if="$page.props.auth.user.is_admin" class="pt-6 border-t border-slate-50 dark:border-white/5">
                            <form @submit.prevent="updateOtoritas" class="space-y-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Konfigurasi Otoritas</label>
                                
                                <div class="space-y-2">
                                    <label 
                                        v-for="roleOption in availableRoles" 
                                        :key="roleOption"
                                        class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl cursor-pointer hover:bg-blue-50 transition-all border border-slate-100"
                                    >
                                        <input 
                                            type="checkbox" 
                                            v-model="form.roles" 
                                            :value="roleOption"
                                            class="w-5 h-5 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500/20"
                                        >
                                        <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase italic">
                                            {{ roleOption.replace('_', ' ') }}
                                        </span>
                                    </label>
                                </div>

                                <div class="my-6 h-px bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent"></div>

                                <label class="flex items-center gap-3 p-4 bg-amber-500/5 rounded-2xl cursor-pointer border border-amber-500/20">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.is_admin" 
                                        class="w-5 h-5 rounded-lg border-amber-500/30 text-amber-500 focus:ring-amber-500/20"
                                    >
                                    <span class="text-[10px] font-black text-amber-600 uppercase italic">Hak Akses Admin Utama</span>
                                </label>

                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="w-full py-4 bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-lg hover:bg-blue-700 transition-all italic"
                                >
                                    {{ form.processing ? 'Updating...' : 'Update Otoritas' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                            <span class="text-[10px] font-black text-slate-400 uppercase block mb-2">Pinjaman Aktif</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-slate-800 dark:text-white italic">{{ user.peminjamans_count || 0 }}</span>
                                <span class="text-xs font-black text-slate-400 uppercase italic">Item Alat</span>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                            <span class="text-[10px] font-black text-slate-400 uppercase block mb-2">Status Bebas Lab</span>
                            <span class="text-3xl font-black italic" :class="user.bebas_lab ? 'text-emerald-500' : 'text-amber-500'">
                                {{ user.bebas_lab ? 'CLEARED' : 'ON PROGRESS' }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                        <div class="px-8 py-6 bg-slate-50/50 dark:bg-white/5 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-xs font-black uppercase italic tracking-widest text-slate-800 dark:text-white">Informasi Lab & Inventori</h3>
                            <span v-if="isPLP" class="text-[9px] bg-emerald-500 text-white px-3 py-1 rounded-full font-black italic uppercase">Petugas Lab</span>
                        </div>
                        <div class="p-8">
                            <div v-if="isPLP || user.is_admin" class="p-6 bg-blue-500/5 rounded-3xl border border-blue-500/10">
                                <p class="text-sm text-slate-600 italic">User memiliki hak akses pengelolaan lab.</p>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-xs font-black text-slate-400 uppercase italic">Akses Manajemen Inventori Terbatas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    availableRoles: Array // Dikirim dari controller
});

// Sync data dengan roles_list dari Controller
const form = useForm({
    roles: props.user.roles_list || [], 
    is_admin: !!props.user.is_admin,
});

const getInitials = (name) => name ? name.substring(0, 2).toUpperCase() : '??';

// Fix: Cek isPLP lewat roles_list
const isPLP = computed(() => props.user.roles_list?.includes('plp'));

const updateOtoritas = () => {
    form.patch(route('admin.users.update-role', props.user.id));
};

const handleImpersonate = () => {
    form.post(route('admin.users.impersonate', props.user.id));
};
</script>