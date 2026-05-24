<template>
    <AuthenticatedLayout>
        <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter text-slate-900 dark:text-white">
                    MANAJEMEN <span class="text-blue-600">PRESENSI</span>
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-black uppercase tracking-[0.3em] mt-1">
                    Kelola kelas praktikum dan mulai sesi kehadiran
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input v-model="filterForm.search" @input="applyFilter" type="text" placeholder="CARI MATA KULIAH..." 
                        class="pl-10 pr-4 py-2 bg-white dark:bg-railway-card border-slate-200 dark:border-railway-border rounded-xl text-[10px] font-bold uppercase italic text-slate-700 dark:text-slate-200 focus:ring-blue-500 w-48">
                </div>

                <select v-if="isManagement" v-model="filterForm.academic_year" @change="applyFilter"
                        class="bg-white dark:bg-railway-card border-slate-200 dark:border-railway-border rounded-xl text-xs font-bold uppercase italic text-slate-700 dark:text-slate-200 focus:ring-blue-500">
                    <option value="">Semua Tahun Akademik</option>
                    <option v-for="year in academicYears" :key="year" :value="year">{{ year }}</option>
                </select>

                <button v-if="isManagement" @click="openCreateModal" 
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase italic tracking-widest flex items-center gap-2 shadow-lg shadow-blue-500/20 transition-all">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in classes" :key="item.id" 
                 class="group relative bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] p-8 transition-all duration-300 hover:border-blue-500/50 hover:shadow-2xl hover:shadow-blue-500/10 flex flex-col h-full">
                
                <div class="flex justify-between items-start mb-6">
                    <div class="flex flex-col gap-2">
                        <span class="w-fit px-3 py-1 bg-slate-100 dark:bg-railway-dark text-[9px] font-black text-slate-500 dark:text-blue-400 rounded-lg uppercase tracking-tighter border border-slate-200 dark:border-railway-border">
                            {{ item.subject?.code }}
                        </span>
                        <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">
                            TA: {{ item.academic_year }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-emerald-500/10 px-3 py-1.5 rounded-full border border-emerald-500/20">
                        <i class="bi bi-people-fill text-emerald-600 text-[10px]"></i>
                        <span class="text-[10px] text-emerald-600 font-black italic">{{ item.students_count }} MHS</span>
                    </div>
                </div>

                <h3 class="text-xl font-black uppercase italic leading-tight text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors mb-1">
                    {{ item.subject?.name }}
                </h3>
                <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-6">
                    Kelas: <span class="text-slate-900 dark:text-white">{{ item.name }}</span>
                </p>

                <div class="space-y-3 mb-8 flex-grow">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-blue-600/10 rounded flex items-center justify-center text-blue-600 flex-shrink-0 text-xs">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[7px] font-black text-slate-400 uppercase leading-none mb-0.5">Dosen 1</span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ item.teacher?.name }}</span>
                        </div>
                    </div>

                    <div v-if="item.teacher2" class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-blue-600/10 rounded flex items-center justify-center text-blue-600 flex-shrink-0 text-xs">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[7px] font-black text-slate-400 uppercase leading-none mb-0.5">Dosen 2</span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ item.teacher2?.name }}</span>
                        </div>
                    </div>

                    <div v-if="item.assistant" class="flex items-center gap-3 pt-1 border-t border-slate-50 dark:border-white/5">
                        <div class="w-7 h-7 bg-amber-500/10 rounded flex items-center justify-center text-amber-500 flex-shrink-0 text-xs">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[7px] font-black text-slate-400 uppercase leading-none mb-0.5">Asisten 1</span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ item.assistant?.name }}</span>
                        </div>
                    </div>

                    <div v-if="item.assistant2" class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-amber-500/10 rounded flex items-center justify-center text-amber-500 flex-shrink-0 text-xs">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[7px] font-black text-slate-400 uppercase leading-none mb-0.5">Asisten 2</span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ item.assistant2?.name }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto space-y-4">
                    <div v-if="isManagement" class="flex items-center justify-start gap-4 px-1">
                        <button @click="openEditModal(item)" class="text-[9px] font-black uppercase tracking-tighter text-blue-600 hover:text-blue-700 transition-colors">
                            <i class="bi bi-pencil-square mr-1"></i> Edit Kelas
                        </button>
                        <button @click="deleteClass(item.id)" class="text-[9px] font-black uppercase tracking-tighter text-red-500 hover:text-red-600 transition-colors">
                            <i class="bi bi-trash mr-1"></i> Hapus
                        </button>
                    </div>

                    <div v-if="canImport" class="grid grid-cols-2 gap-2">
                        <button @click="downloadTemplate" class="py-2.5 bg-slate-50 dark:bg-railway-dark text-slate-500 border border-slate-200 dark:border-railway-border rounded-xl text-[9px] font-black uppercase hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-download"></i> Format
                        </button>
                        <button @click="openImportModal(item)" class="py-2.5 bg-emerald-600/10 text-emerald-600 border border-emerald-600/20 rounded-xl text-[9px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-upload"></i> Import MHS
                        </button>
                    </div>

                    <div class="grid grid-cols-5 gap-2">
                        <button @click="visitReport(item.id)" class="col-span-2 py-3.5 bg-slate-100 dark:bg-railway-dark hover:bg-slate-200 text-slate-600 rounded-2xl flex items-center justify-center gap-2 border border-slate-200 dark:border-railway-border transition-all">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span class="text-[9px] font-black uppercase italic">Rekap</span>
                        </button>
                        <button @click="visitSession(item.id)" class="col-span-3 py-3.5 bg-blue-600 text-white rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                            <span class="text-[10px] font-black uppercase italic tracking-widest">Mulai Sesi</span>
                            <i class="bi bi-arrow-right-short text-xl leading-none"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showCreateModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-railway-card w-full max-w-2xl rounded-[2.5rem] p-8 shadow-2xl border border-slate-200 dark:border-railway-border max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-black italic uppercase text-slate-900 dark:text-white tracking-tighter">
                        {{ isEditing ? 'Edit' : 'Tambah' }} <span class="text-blue-600">Kelas Praktikum</span>
                    </h2>
                    <button @click="showCreateModal = false" class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-400 hover:text-red-500 rounded-full transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form @submit.prevent="submitCreate" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Pilih Mata Kuliah</label>
                            <select v-model="createForm.subject_id" class="bg-slate-50 dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs font-bold uppercase" required>
                                <option value="">Cari Mata Kuliah...</option>
                                <option v-for="sub in subjects" :key="sub.id" :value="sub.id">[{{ sub.code }}] {{ sub.name }}</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Nama Kelas (Contoh: A)</label>
                            <input v-model="createForm.name" type="text" placeholder="MISAL: KELAS A" class="bg-slate-50 dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs font-bold uppercase" required>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Tahun Akademik</label>
                            <input v-model="createForm.academic_year" type="text" placeholder="2025/2026" class="bg-slate-50 dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs font-bold uppercase" required>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-railway-border">
                        <h4 class="text-[10px] font-black uppercase text-blue-600 mb-4 tracking-widest">Manajemen Pengajar</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase">Dosen 1 (Wajib)</label>
                                <select v-model="createForm.teacher_id" class="bg-white dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs" required>
                                    <option value="">Pilih Dosen Utama</option>
                                    <option v-for="d in teachers" :key="d.id" :value="d.id">{{ d.name }}</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase">Dosen 2 (Opsional)</label>
                                <select v-model="createForm.teacher2_id" class="bg-white dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs">
                                    <option value="">Tidak Ada</option>
                                    <option v-for="d in teachers" :key="d.id" :value="d.id">{{ d.name }}</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase">Asisten 1 (Opsional)</label>
                                <select v-model="createForm.assistant_id" class="bg-white dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs">
                                    <option value="">Pilih Asisten Utama</option>
                                    <option v-for="a in assistants" :key="a.id" :value="a.id">{{ a.name }}</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase">Asisten 2 (Opsional)</label>
                                <select v-model="createForm.assistant2_id" class="bg-white dark:bg-railway-dark border-slate-200 dark:border-railway-border rounded-xl text-xs">
                                    <option value="">Tidak Ada</option>
                                    <option v-for="a in assistants" :key="a.id" :value="a.id">{{ a.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button :disabled="createForm.processing" type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase italic tracking-widest disabled:opacity-50 hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20">
                        {{ createForm.processing ? 'Sedang Memproses...' : (isEditing ? 'Simpan Perubahan' : 'Buat Kelas Praktikum') }}
                    </button>
                </form>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-railway-card w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl border border-slate-200 dark:border-railway-border">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-black italic uppercase text-slate-900 dark:text-white tracking-tighter">
                        Import <span class="text-blue-600">Mahasiswa</span>
                    </h2>
                    <button @click="showModal = false" class="text-slate-400 hover:text-red-500 transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form @submit.prevent="submitImport" class="space-y-6">
                    <div class="border-2 border-dashed border-slate-200 dark:border-railway-border rounded-2xl p-10 flex flex-col items-center justify-center bg-slate-50 dark:bg-railway-dark/50 hover:bg-slate-100 transition-all cursor-pointer group">
                        <input type="file" @change="handleFileChange" class="hidden" id="fileInput" accept=".csv,.xlsx">
                        <label for="fileInput" class="cursor-pointer flex flex-col items-center text-center">
                            <i class="bi bi-file-earmark-excel text-4xl text-emerald-500 mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black uppercase text-slate-500 tracking-wider leading-relaxed">
                                {{ importForm.file ? importForm.file.name : 'Klik untuk Pilih File Excel/CSV' }}
                            </span>
                        </label>
                    </div>

                    <div class="flex flex-col gap-2">
                        <button :disabled="importForm.processing" type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase italic tracking-widest disabled:opacity-50 hover:bg-blue-700 transition-all">
                            Unggah Data
                        </button>
                        <p class="text-[8px] text-center text-slate-400 uppercase font-bold italic tracking-widest italic pt-2">Pastikan format file sesuai dengan template</p>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, reactive } from 'vue';

const props = defineProps({
    classes: { type: Array, default: () => [] },
    academicYears: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    subjects: { type: Array, default: () => [] },
    teachers: { type: Array, default: () => [] },
    assistants: { type: Array, default: () => [] },
    userStatus: { type: Object, default: () => ({}) }
});

const page = usePage();
const showModal = ref(false);
const showCreateModal = ref(false);
const selectedClass = ref(null);
const isEditing = ref(false);
const editId = ref(null);

const filterForm = reactive({
    academic_year: props.filters?.academic_year || '',
    search: props.filters?.search || ''
});

const importForm = useForm({
    file: null
});

const createForm = useForm({
    subject_id: '',
    name: '',
    academic_year: '2025/2026',
    teacher_id: '',
    teacher2_id: '',
    assistant_id: '',
    assistant2_id: ''
});

const isManagement = computed(() => {
    return props.userStatus?.isManagement || page.props.auth?.user?.is_admin;
});

const canImport = computed(() => {
    const user = page.props.auth?.user;
    if (!user) return false;
    return user.is_admin || (user.permissions && user.permissions.includes('view-all-attendance'));
});

const applyFilter = () => {
    router.get(route('admin.attendance.index'), filterForm, { 
        preserveState: true,
        replace: true,
        preserveScroll: true
    });
};

const openCreateModal = () => {
    isEditing.value = false;
    editId.value = null;
    createForm.reset();
    showCreateModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    editId.value = item.id;
    
    createForm.subject_id = item.subject_id;
    createForm.name = item.name;
    createForm.academic_year = item.academic_year;
    createForm.teacher_id = item.teacher_id;
    createForm.teacher2_id = item.teacher2_id || '';
    createForm.assistant_id = item.assistant_id || '';
    createForm.assistant2_id = item.assistant2_id || '';
    
    showCreateModal.value = true;
};

const openImportModal = (item) => {
    selectedClass.value = item;
    showModal.value = true;
};

const submitCreate = () => {
    if (isEditing.value) {
        createForm.put(route('admin.attendance.update-class', editId.value), {
            onSuccess: () => {
                showCreateModal.value = false;
                createForm.reset();
            }
        });
    } else {
        createForm.post(route('admin.attendance.store-class'), {
            onSuccess: () => {
                showCreateModal.value = false;
                createForm.reset();
            }
        });
    }
};

const deleteClass = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus kelas ini? Semua data sesi di dalamnya akan hilang.')) {
        router.delete(route('admin.attendance.destroy-class', id));
    }
};

const handleFileChange = (e) => {
    importForm.file = e.target.files[0];
};

const submitImport = () => {
    importForm.post(route('admin.attendance.import', selectedClass.value.id), {
        onSuccess: () => {
            showModal.value = false;
            importForm.reset();
        }
    });
};

const downloadTemplate = () => window.open(route('admin.attendance.export-template'), '_blank');
const visitSession = (id) => router.get(route('admin.attendance.session', id));
const visitReport = (id) => router.get(route('admin.attendance.report', id));
</script>