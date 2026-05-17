<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue' // 1. Impor layout utama
import { useForm, Head, Link } from '@inertiajs/vue3'
import RichTextEditor from '@/components/RichTextEditor.vue' // 2. Sesuaikan ke huruf kecil 'components' jika perlu

const props = defineProps({
    categories: {
        type: Array,
        required: true
    }
})

// Inisialisasi form bawaan Inertia
const form = useForm({
    title: '',
    category: 'Artikel', // Default kategori sesuai kesepakatan
    content: '',
    image: null,
    is_published: false,
    is_pinned: false
})

// Fungsi untuk menangani input file gambar
const handleImageUpload = (event) => {
    form.image = event.target.files[0]
}

// Fungsi submit data menggunakan standard multipart form (karena ada upload file)
const submit = () => {
    form.post(route('admin.posts.store'), {
        forceFormData: true,
        onSuccess: () => form.reset()
    })
}
</script>

<template>
    <Head title="Tulis Artikel Baru - Admin Panel" />

    <AuthenticatedLayout>
        <div class="p-6 min-h-screen bg-gray-50 dark:bg-railway-dark text-gray-900 dark:text-gray-100 font-sans transition-colors duration-200">
            <div class="mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <Link :href="route('admin.posts.index')" class="hover:text-railway-accent transition">Blog</Link>
                    <span>/</span>
                    <span class="text-gray-700 dark:text-gray-200">Tulis Baru</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Tulis Artikel Baru</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat konten pengumuman resmi atau tutorial komputasi terstruktur untuk laboratorium.</p>
            </div>

            <div class="bg-white dark:bg-railway-card border border-gray-200 dark:border-railway-border rounded-xl shadow-sm p-6 max-w-5xl">
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100" for="title">Judul Artikel</label>
                        <input 
                            id="title"
                            v-model="form.title"
                            type="text"
                            placeholder="Masukkan judul artikel yang menarik..."
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
                            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100" for="image">Gambar Sampul (Thumbnail)</label>
                            <input 
                                id="image"
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                @change="handleImageUpload"
                                class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-railway-border bg-white dark:bg-railway-dark text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 dark:file:bg-zinc-800 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200 dark:hover:file:bg-zinc-700 text-gray-500 dark:text-gray-400 cursor-pointer outline-none transition"
                                :class="{ 'border-red-500': form.errors.image }"
                            />
                            <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-1">Format: WEBP, JPG, PNG. Maksimal ukuran file: 2MB.</p>
                            <div v-if="form.errors.image" class="text-xs text-red-500 mt-1">{{ form.errors.image }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-gray-100">Isi Konten</label>
                        <RichTextEditor 
                            v-model="form.content"
                            placeholder="Mulai ketik isi pengumuman atau materi praktikum di sini..."
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
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Konten' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>