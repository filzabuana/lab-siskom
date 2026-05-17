<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue' // 1. Impor layout utama
import { useForm, Head, Link } from '@inertiajs/vue3'
import RichTextEditor from '@/components/RichTextEditor.vue' // 2. Sesuaikan ke huruf kecil 'components'

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    }
})

// Inisialisasi form dengan memuat data lama dari props 'post'
const form = useForm({
    _method: 'PUT', // Trik Laravel + Inertia agar bisa upload file saat update data
    title: props.post.title,
    category: props.post.category,
    content: props.post.content,
    image: null, // Tetap null kecuali admin memilih file baru
    is_published: props.post.is_published,
    is_pinned: props.post.is_pinned
})

// Menangani input file gambar baru
const handleImageUpload = (event) => {
    form.image = event.target.files[0]
}

// Proses submit pembaruan data
const submit = () => {
    // Kita gunakan method 'post' karena membawa data biner file gambar, 
    // namun Laravel akan membacanya sebagai 'PUT' berkat properti '_method' di atas.
    form.post(route('admin.posts.update', props.post.id), {
        forceFormData: true,
    })
}
</script>

<template>
    <Head :title="`Edit Artikel: ${post.title} - Admin Panel`" />

    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50 dark:bg-railway-dark text-gray-900 dark:text-gray-100 font-sans transition-colors duration-200">
            <div class="mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <Link :href="route('admin.posts.index')" class="hover:text-railway-accent transition">Blog</Link>
                    <span>/</span>
                    <span class="text-gray-700 dark:text-gray-200">Edit Artikel</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Edit Artikel</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui informasi, sesuaikan kategori, atau ubah status sematan artikel laboratorium.</p>
            </div>

            <div class="bg-white dark:bg-railway-card border border-gray-200 dark:border-railway-border rounded-xl shadow-sm p-6 max-w-5xl">
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100" for="title">Judul Artikel</label>
                        <input 
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-railway-border bg-white dark:bg-railway-dark text-gray-900 dark:text-white focus:ring-1 focus:ring-railway-accent focus:border-railway-accent outline-none transition"
                            :class="{ 'border-red-500 focus:ring-red-500': form.errors.title }"
                        />
                        <div v-if="form.errors.title" class="text-xs text-red-500 mt-1">{{ form.errors.title }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100" for="category">Kategori</label>
                            <select 
                                id="category"
                                v-model="form.category"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-railway-border bg-white dark:bg-railway-dark text-gray-900 dark:text-white focus:ring-1 focus:ring-railway-accent focus:border-railway-accent outline-none transition"
                                :class="{ 'border-red-500 focus:ring-red-500': form.errors.category }">
                                <option v-for="cat in categories" :key="cat" :value="cat" class="text-gray-900 dark:text-white bg-white dark:bg-railway-dark">{{ cat }}</option>
                            </select>
                            <div v-if="form.errors.category" class="text-xs text-red-500 mt-1">{{ form.errors.category }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100" for="image">Ganti Gambar Sampul (Opsional)</label>
                            <input 
                                id="image"
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                @change="handleImageUpload"
                                class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-railway-border bg-white dark:bg-railway-dark text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 dark:file:bg-zinc-800 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200 dark:hover:file:bg-zinc-700 text-gray-500 dark:text-gray-400 cursor-pointer outline-none transition"
                                :class="{ 'border-red-500': form.errors.image }"
                            />
                            <p v-if="post.image" class="text-[11px] text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Artikel ini sudah memiliki gambar sampul aktif. Upload jika ingin mengganti.
                            </p>
                            <div v-if="form.errors.image" class="text-xs text-red-500 mt-1">{{ form.errors.image }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100">Isi Konten</label>
                        <RichTextEditor 
                            v-model="form.content"
                            placeholder="Edit isi konten artikel..."
                        />
                        <div v-if="form.errors.content" class="text-xs text-red-500 mt-1">{{ form.errors.content }}</div>
                    </div>

                    <div class="flex flex-wrap gap-6 p-4 bg-gray-50 dark:bg-railway-dark/50 border border-gray-200 dark:border-railway-border rounded-xl">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                v-model="form.is_published"
                                class="w-4 h-4 rounded text-railway-accent border-gray-300 dark:border-railway-border focus:ring-railway-accent bg-white dark:bg-railway-dark"
                            />
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Terbitkan Artikel</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Centang agar langsung muncul di halaman utama blog publik mahasiswa.</div>
                            </div>
                        </label>

                        <div class="hidden md:block w-px bg-gray-200 dark:bg-railway-border"></div>

                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                v-model="form.is_pinned"
                                class="w-4 h-4 rounded text-railway-accent border-gray-300 dark:border-railway-border focus:ring-railway-accent bg-white dark:bg-railway-dark"
                            />
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Sematkan Postingan (Pin)</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Posisikan artikel ini agar selalu berada di baris paling atas halaman blog.</div>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-railway-border">
                        <Link 
                            :href="route('admin.posts.index')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-railway-dark border border-gray-300 dark:border-railway-border rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                            Batal
                        </Link>
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 text-sm font-semibold text-white bg-railway-accent hover:bg-blue-600 rounded-lg shadow disabled:opacity-50 disabled:cursor-not-allowed transition">
                            {{ form.processing ? 'Menyimpan...' : 'Perbarui Artikel' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>