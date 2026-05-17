<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue' // 1. TAMBAHKAN IMPOST LAYOUT
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
    posts: {
        type: Object, // Menerima objek pagination dari Laravel
        required: true
    }
})

// Fungsi format tanggal lokal Indonesia
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

// Fungsi hapus data artikel dengan konfirmasi bawaan browser
const deletePost = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        router.delete(route('admin.posts.destroy', id), {
            preserveScroll: true,
            onSuccess: () => alert('Artikel berhasil dihapus')
        })
    }
}

// Badge styling sesuai kategori baku blog
const getCategoryBadgeClass = (category) => {
    switch (category) {
        case 'Pengumuman':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800'
        case 'Tutorial':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800'
        case 'Kegiatan':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800'
        default: // Artikel
            return 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800/50 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700'
    }
}
</script>

<template>
    <Head title="Manajemen Blog - Admin Panel" />

    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50 dark:bg-railway-dark text-gray-900 dark:text-gray-100 font-sans transition-colors duration-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Manajemen Konten Blog</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola pengumuman, tutorial teknis, materi komputasi, dan dokumentasi kegiatan laboratorium.</p>
                </div>
                
                <Link 
                    :href="route('admin.posts.create')"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-railway-accent hover:bg-blue-600 rounded-lg shadow transition duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tulis Artikel Baru
                </Link>
            </div>

            <div class="bg-white dark:bg-railway-card border border-gray-200 dark:border-railway-border rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-zinc-900/50 border-b border-gray-200 dark:border-railway-border text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                <th class="px-6 py-4">Judul Artikel</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Penulis</th>
                                <th class="px-6 py-4">Statistik</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-railway-border text-sm">
                            <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50/70 dark:hover:bg-zinc-900/30 transition">
                                <td class="px-6 py-4 max-w-md">
                                    <div class="flex items-start gap-2">
                                        <span v-if="post.is_pinned" class="mt-0.5 text-amber-500" title="Disematkan di paling atas">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                                        </span>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white line-clamp-1">{{ post.title }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Dibuat pada {{ formatDate(post.created_at) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getCategoryBadgeClass(post.category)" class="px-2.5 py-1 text-xs font-medium rounded-md border">
                                        {{ post.category }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    {{ post.user ? post.user.name : 'Sistem/Admin' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>{{ post.views_count }} x dibaca</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="post.is_published" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                                        Publik
                                    </span>
                                    <span v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400">
                                        Draf
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <Link 
                                            :href="route('admin.posts.edit', post.id)" 
                                            class="text-railway-accent hover:text-blue-500 transition">
                                            Edit
                                        </Link>
                                        <button 
                                            type="button"
                                            @click="deletePost(post.id)" 
                                            class="text-red-600 hover:text-red-400 transition">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="posts.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-zinc-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v4a1 1 0 001 1h4"/></svg>
                                    <div class="text-base font-semibold">Belum Ada Artikel</div>
                                    <p class="text-xs mt-1">Silakan klik tombol "Tulis Artikel Baru" untuk membuat postingan pertama Anda.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="posts.links.length > 3" class="px-6 py-4 bg-gray-50 dark:bg-zinc-900/30 border-t border-gray-200 dark:border-railway-border flex justify-between items-center">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Menampilkan {{ posts.from ?? 0 }} sampai {{ posts.to ?? 0 }} dari {{ posts.total }} artikel
                    </div>
                    <div class="flex gap-1">
                        <component 
                            :is="link.url ? Link : 'span'"
                            v-for="(link, k) in posts.links" 
                            :key="k"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1.5 text-xs rounded border transition duration-150',
                                link.active 
                                    ? 'bg-railway-accent text-white border-railway-accent font-semibold' 
                                    : 'bg-white dark:bg-railway-card text-gray-700 dark:text-gray-300 border-gray-300 dark:border-railway-border hover:bg-gray-100 dark:hover:bg-zinc-800',
                                !link.url ? 'opacity-40 cursor-not-allowed' : ''
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>