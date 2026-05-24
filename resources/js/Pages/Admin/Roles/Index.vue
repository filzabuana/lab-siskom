<template>
  <Head title="Manajemen Role & Akses" />

  <AuthenticatedLayout>
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-railway-card p-6 rounded-3xl border border-slate-200 dark:border-railway-border shadow-sm">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-600/10 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
            <i class="bi bi-shield-lock-fill"></i>
          </div>
          <div>
            <h1 class="text-lg font-black uppercase tracking-tight text-slate-900 dark:text-white leading-none">
              Manajemen Role
            </h1>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1.5">
              Mengatur hak akses pengguna sistem komputer &amp; komputasi
            </p>
          </div>
        </div>

        <button 
          @click="openAddModal"
          class="sm:self-center px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-600/20 active:scale-95 flex items-center justify-center gap-2"
        >
          <i class="bi bi-plus-lg text-sm"></i>
          Tambah Role Baru
        </button>
      </div>

      <div class="bg-white dark:bg-railway-card rounded-3xl border border-slate-200 dark:border-railway-border shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-railway-border">
          <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 italic">
            Daftar Role Terdaftar
          </h2>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/70 dark:bg-railway-dark/40 border-b border-slate-100 dark:border-railway-border text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 italic">
                <th class="py-4 px-6 w-16 text-center">No</th>
                <th class="py-4 px-6 w-1/4">Nama Role</th>
                <th class="py-4 px-6">Hak Akses (Permissions)</th>
                <th class="py-4 px-6 w-32 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-railway-border/60 text-xs font-bold text-slate-700 dark:text-slate-300">
              <tr 
                v-for="(role, index) in roles" 
                :key="role.id"
                class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors"
              >
                <td class="py-4 px-6 text-center font-mono text-slate-400">{{ index + 1 }}</td>
                <td class="py-4 px-6">
                  <span class="font-black uppercase tracking-wide text-slate-900 dark:text-white block">
                    {{ role.name.replace('_', ' ') }}
                  </span>
                  <span class="block text-[9px] font-medium text-slate-400 dark:text-slate-500 tracking-normal mt-0.5 italic">
                    {{ role.description || 'Tidak ada deskripsi role.' }}
                  </span>
                </td>
                <td class="py-4 px-6">
                  <div class="flex flex-wrap gap-1.5">
                    <span 
                      v-for="perm in role.permissions" 
                      :key="perm.id"
                      class="px-2.5 py-1 bg-slate-100 dark:bg-railway-dark text-slate-600 dark:text-slate-400 text-[10px] font-mono rounded-lg border border-slate-200/60 dark:border-railway-border/60 uppercase"
                      :title="perm.description"
                    >
                      {{ perm.name }}
                    </span>
                    <span 
                      v-if="role.permissions.length === 0" 
                      class="text-[10px] italic text-slate-400 dark:text-slate-500 font-medium"
                    >
                      Belum memiliki hak akses khusus
                    </span>
                  </div>
                </td>
                <td class="py-4 px-6 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button 
                      @click="openEditModal(role)"
                      class="p-2 bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-white rounded-xl transition-all active:scale-90"
                      title="Edit Role & Permission"
                    >
                      <i class="bi bi-pencil-square text-base"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Transition name="fade">
      <div 
        v-if="modalOpen" 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
        @click.self="closeModal"
      >
        <div class="bg-white dark:bg-railway-card w-full max-w-2xl rounded-3xl border border-slate-200 dark:border-railway-border shadow-2xl flex flex-col max-h-[90vh] animate-dropdown">
          
          <div class="p-6 border-b border-slate-100 dark:border-railway-border flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
              <i class="bi bi-gear-wide-connected text-xl text-blue-600"></i>
              <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white">
                {{ isEditMode ? 'Modifikasi Role' : 'Tambah Role Baru' }}
              </h3>
            </div>
            <button @click="closeModal" class="text-slate-400 hover:text-red-500 transition-colors">
              <i class="bi bi-x-lg text-lg"></i>
            </button>
          </div>

          <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 italic block">
                Nama Role (Slug / Huruf Kecil)
              </label>
              <input 
                v-model="form.name"
                type="text"
                placeholder="misal: dosen_luar, asisten_lab"
                :disabled="isEditMode"
                class="w-full bg-slate-50 dark:bg-railway-dark/60 border border-slate-200 dark:border-railway-border rounded-xl px-4 py-3 text-xs font-mono tracking-wide placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all disabled:opacity-60"
              />
              <span v-if="form.errors.name" class="text-[10px] text-red-500 font-bold block mt-1">
                {{ form.errors.name }}
              </span>
            </div>

            <div class="space-y-3">
              <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 italic block">
                Pilih Hak Akses (Permissions Mapping)
              </label>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-railway-dark/30 border border-slate-200 dark:border-railway-border/60 rounded-2xl p-4">
                <div 
                  v-for="perm in permissions" 
                  :key="perm.id"
                  class="flex items-start gap-3 p-2 hover:bg-slate-100/50 dark:hover:bg-white/[0.02] rounded-xl transition-all cursor-pointer"
                  @click="togglePermission(perm.name)"
                >
                  <div class="flex items-center h-5">
                    <input
                      type="checkbox"
                      :id="'perm-' + perm.id"
                      :checked="form.permissions.includes(perm.name)"
                      @click.stop="togglePermission(perm.name)"
                      class="w-4 h-4 text-blue-600 bg-white dark:bg-railway-dark border-slate-300 dark:border-railway-border rounded focus:ring-blue-500 dark:focus:ring-offset-railway-card"
                    />
                  </div>
                  <div class="text-[11px]">
                    <label :for="'perm-' + perm.id" class="font-mono text-slate-800 dark:text-slate-200 cursor-pointer block leading-none">
                      {{ perm.name }}
                    </label>
                    <span class="text-[9px] text-slate-400 font-medium block mt-1 leading-normal">
                      {{ perm.description || 'Hak akses fungsional sistem laboratorium.' }}
                    </span>
                  </div>
                </div>
              </div>
              <span v-if="form.errors.permissions" class="text-[10px] text-red-500 font-bold block mt-1">
                {{ form.errors.permissions }}
              </span>
            </div>
          </form>

          <div class="p-6 border-t border-slate-100 dark:border-railway-border flex items-center justify-end gap-3 shrink-0">
            <button 
              type="button" 
              @click="closeModal"
              class="px-5 py-2.5 bg-slate-100 dark:bg-railway-dark hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider rounded-xl transition-all"
            >
              Batal
            </button>
            <button 
              type="button"
              @click="submitForm"
              :disabled="form.processing"
              class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 flex items-center gap-2"
            >
              <i v-if="form.processing" class="bi bi-arrow-clockwise animate-spin"></i>
              {{ isEditMode ? 'Simpan Perubahan' : 'Simpan Role' }}
            </button>
          </div>

        </div>
      </div>
    </Transition>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  roles: { type: Array, default: () => [] },
  permissions: { type: Array, default: () => [] }
});

const modalOpen = ref(false);
const isEditMode = ref(false);
const currentRoleId = ref(null);

const form = useForm({
  name: '',
  permissions: []
});

const togglePermission = (permName) => {
  const index = form.permissions.indexOf(permName);
  if (index > -1) {
    form.permissions.splice(index, 1);
  } else {
    form.permissions.push(permName);
  }
};

const openAddModal = () => {
  isEditMode.value = false;
  currentRoleId.value = null;
  form.reset();
  form.clearErrors();
  modalOpen.value = true;
};

const openEditModal = (role) => {
  isEditMode.value = true;
  currentRoleId.value = role.id;
  form.clearErrors();
  
  form.name = role.name;
  form.permissions = role.permissions.map(p => p.name);
  modalOpen.value = true;
};

const closeModal = () => {
  modalOpen.value = false;
  form.reset();
};

const submitForm = () => {
  if (isEditMode.value) {
    form.put(route('admin.roles.update', currentRoleId.value), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post(route('admin.roles.store'), {
      onSuccess: () => closeModal(),
    });
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>