<template>
    <Head title="Otoritas Pengguna" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-300">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none text-slate-900 dark:text-white">
                        Otoritas Pengguna<span class="text-railway-accent">.</span>
                    </h1>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-railway-accent mt-2 italic">
                        Sistem Manajemen Hak Akses Laboratorium
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button 
                        v-if="auth.isAdmin"
                        @click="navigateTo('admin.users.create')"
                        class="bg-railway-accent hover:bg-blue-600 text-white h-[56px] rounded-2xl font-black italic tracking-widest text-[10px] px-8 shadow-xl shadow-blue-600/20 transition-all flex items-center gap-2"
                    >
                        <i class="mdi mdi-account-plus text-base"></i>
                        REGISTER AKUN
                    </button>

                    <div class="px-6 py-3 border rounded-xl hidden sm:block bg-white border-slate-200 dark:bg-railway-card dark:border-railway-border">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                            Total Basis Data: <span class="text-railway-accent">{{ users.length }} User</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="hidden md:block rounded-[2.5rem] overflow-hidden shadow-2xl border transition-all duration-300 bg-white border-slate-200 dark:bg-railway-card dark:border-railway-border">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b transition-colors bg-slate-50 border-slate-200 dark:bg-white/5 dark:border-railway-border">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] italic text-slate-500 dark:text-slate-400">Identitas Pengguna</th>
                            <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.2em] italic text-center text-slate-500 dark:text-slate-400">Otoritas / Role</th>
                            <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.2em] italic text-center text-red-500">Alat Aktif</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] italic text-right text-slate-500 dark:text-slate-400">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-railway-border">
                        <tr v-for="user in users" :key="user.id" class="group transition-all hover:bg-railway-accent/[0.03]">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div 
                                        class="w-12 h-12 flex items-center justify-center rounded-xl font-black text-sm border shadow-sm transition-transform group-hover:scale-110 overflow-hidden bg-slate-100 border-slate-200 dark:bg-zinc-950 dark:border-railway-border"
                                        :style="{ backgroundColor: getAvatarBg(user) }"
                                    >
                                        <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover" />
                                        <i v-else-if="user.is_admin" class="mdi mdi-shield-check text-railway-accent"></i>
                                        <i v-else-if="user.roles_list?.includes('plp')" class="mdi mdi-account-badge text-purple-500"></i>
                                        <span v-else class="text-railway-accent">{{ user.name.substring(0, 2).toUpperCase() }}</span>
                                    </div>
                                    
                                    <div>
                                        <div class="text-sm font-black uppercase tracking-tight italic flex items-center gap-2 text-slate-900 dark:text-white">
                                            {{ user.name }}
                                            <span v-if="user.is_admin" class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)] animate-pulse"></span>
                                        </div>
                                        <div class="text-[9px] font-mono uppercase tracking-[0.2em] mt-0.5 text-slate-500 dark:text-slate-400">
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex flex-wrap justify-center gap-1 max-w-[200px] mx-auto">
                                    <span v-if="user.is_admin" class="bg-amber-500/10 text-amber-500 text-[8px] font-black italic px-2 py-0.5 rounded border border-amber-500/20">SUPERADMIN</span>
                                    <span v-for="role in user.roles_list" :key="role" class="border text-[7px] font-black italic px-2 py-0.5 rounded uppercase bg-slate-50 border-slate-200 text-slate-500 dark:bg-transparent dark:border-railway-border dark:text-slate-400">
                                        {{ role.replace('_', ' ') }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-5 text-center">
                                <div v-if="user.peminjamans_count > 0" class="inline-flex flex-col">
                                    <span class="text-lg font-black text-red-600 dark:text-red-500 leading-none italic">{{ user.peminjamans_count }}</span>
                                    <span class="text-[8px] font-black text-red-400 uppercase tracking-tighter">Items</span>
                                </div>
                                <i v-else class="mdi mdi-check-all text-green-500 text-xl"></i>
                            </td>

                            <td class="px-8 py-5 text-right">
                                <button 
                                    @click="navigateTo('admin.users.show', user.id)" 
                                    class="p-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all inline-flex items-center justify-center"
                                    title="Manage Authority"
                                >
                                    <i class="mdi mdi-shield-account text-xl"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 gap-6 md:hidden">
                <div v-for="user in users" :key="user.id" class="relative rounded-[2.5rem] border p-6 overflow-hidden shadow-xl transition-all duration-300 bg-white border-slate-200 dark:bg-railway-card dark:border-railway-border">
                    <div v-if="user.is_admin" class="absolute top-0 right-0 px-4 py-1 bg-amber-500 text-white text-[8px] font-black uppercase italic rounded-bl-2xl shadow-lg">Admin</div>

                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-16 h-16 flex items-center justify-center rounded-xl border shadow-inner overflow-hidden bg-slate-50 border-slate-200 dark:bg-zinc-950 dark:border-railway-border">
                            <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover" />
                            <i v-else :class="['mdi text-2xl', user.is_admin ? 'text-amber-500 mdi-shield-lock' : 'text-railway-accent mdi-account']"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-black uppercase italic tracking-tighter truncate leading-none mb-1 text-slate-900 dark:text-white">{{ user.name }}</h3>
                            <p class="text-[9px] font-mono uppercase tracking-widest truncate mb-2 text-slate-500 dark:text-slate-400">{{ user.email }}</p>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="role in user.roles_list" :key="role" class="bg-railway-accent/10 text-railway-accent font-black italic px-2 py-0.5 rounded text-[7px] border border-railway-accent/20 uppercase">
                                    {{ role.replace('_', ' ') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="h-px w-full mb-4 bg-slate-200 dark:bg-railway-border opacity-50"></div>

                    <div class="flex items-center justify-between">
                        <div class="flex gap-4">
                            <div class="text-center">
                                <p class="text-[8px] font-black uppercase mb-0.5 text-slate-500 dark:text-slate-400">Alat</p>
                                <p class="text-xs font-black italic" :class="user.peminjamans_count > 0 ? 'text-red-500' : 'text-slate-400'">{{ user.peminjamans_count }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[8px] font-black uppercase mb-0.5 text-slate-500 dark:text-slate-400">Bebas Lab</p>
                                <i :class="['mdi text-xs', user.bebas_lab ? 'text-green-500 mdi-check-circle' : 'text-slate-400 mdi-minus-circle-outline']"></i>
                            </div>
                        </div>

                        <button 
                            @click="navigateTo('admin.users.show', user.id)"
                            class="bg-slate-900 text-white dark:bg-white dark:text-zinc-950 rounded-2xl font-black italic tracking-widest text-[10px] px-6 py-3 transition-all active:scale-95 shadow-lg"
                        >MANAGE USER</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useAuthStore } from '@/Stores/useAuthStore';

// Props dari Controller
const props = defineProps({
    users: Array
});

const auth = useAuthStore();

// Fungsi Navigasi Manual (Solusi klik mobile)
const navigateTo = (routeName, id = null) => {
    if (id) {
        router.visit(route(routeName, id));
    } else {
        router.visit(route(routeName));
    }
};

const getAvatarBg = (user) => {
    if (user.is_admin) return 'rgba(245, 158, 11, 0.1)'; 
    if (user.roles_list?.includes('plp')) return 'rgba(168, 85, 247, 0.1)'; 
    return 'rgba(59, 130, 246, 0.1)'; 
};
</script>