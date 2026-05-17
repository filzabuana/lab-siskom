<template>
  <AuthenticatedLayout>
    <div class="min-h-screen py-6">
      <div class="max-w-5xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
          <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
              Profil Pengguna
            </h2>
            <p class="text-slate-500 dark:text-zinc-400 mt-1">Identitas resmi Anda dalam Sistem Informasi Laboratorium FMIPA UNTAN.</p>
          </div>
          
          <div :class="[
            'inline-flex items-center px-4 py-2 border rounded-2xl transition-colors',
            $page.props.auth.user.is_admin 
              ? 'bg-amber-500/10 border-amber-500/20 text-amber-600' 
              : 'bg-blue-500/10 border-blue-500/20 text-blue-600'
          ]">
            <i :class="['bi me-2', $page.props.auth.user.is_admin ? 'bi-shield-lock-fill' : 'bi-mortarboard-fill']"></i>
            <span class="text-sm font-bold">
              {{ statusBadgeText }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
          <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] p-8 shadow-sm text-center">
              <div class="relative inline-block">
                <img 
                  :src="userPhoto" 
                  referrerpolicy="no-referrer"
                  @error="handleAvatarError"
                  class="w-32 h-32 rounded-[2rem] object-cover ring-4 ring-white dark:ring-zinc-800 shadow-xl mx-auto border border-slate-100 dark:border-white/10"
                  alt="Avatar Pengguna"
                />
                <div class="absolute -bottom-2 -right-2 bg-emerald-500 border-4 border-white dark:border-zinc-900 w-8 h-8 rounded-full flex items-center justify-center text-white">
                  <i class="bi bi-check-lg text-xs"></i>
                </div>
              </div>

              <h3 class="mt-6 text-xl font-bold text-slate-900 dark:text-white">{{ $page.props.auth.user.name }}</h3>
              <p class="text-sm text-slate-500 dark:text-zinc-500 mb-6">{{ $page.props.auth.user.email }}</p>
              
              <div class="flex flex-col gap-2">
                <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl text-left border border-slate-100 dark:border-white/5">
                  <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1 leading-none">{{ identityLabel }}</span>
                  <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300">
                    {{ $page.props.auth.user.nim_nip || '-' }}
                  </span>
                </div>

                <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl text-left border border-slate-100 dark:border-white/5">
                  <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1 leading-none">{{ dynamicMetaLabel }}</span>
                  <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300">{{ dynamicMetaValue }}</span>
                </div>

                <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl text-left border border-slate-100 dark:border-white/5">
                  <span class="block text-[10px] uppercase font-bold text-slate-400 mb-1 leading-none">Metode Login</span>
                  <span class="text-sm font-semibold text-slate-700 dark:text-zinc-300 flex items-center gap-2">
                    <template v-if="$page.props.auth.user.google_id">
                      <i class="bi bi-google text-blue-500"></i> Google SSO
                    </template>
                    <template v-else>
                      <i class="bi bi-person-lock text-slate-400"></i> Akun Manual
                    </template>
                  </span>
                </div>
              </div>
            </div>

            <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-lg shadow-blue-500/20">
              <h4 class="font-bold mb-2 flex items-center gap-2">
                <i class="bi bi-shield-check"></i> Keamanan Data
              </h4>
              <p class="text-xs text-blue-100 leading-relaxed">
                Data identitas disinkronkan secara otomatis melalui otentikasi. 
                <span v-if="!$page.props.auth.user.google_id">Gunakan fitur ganti password secara berkala untuk menjaga keamanan akun manual Anda.</span>
                <span v-else>Akun Google Anda dikelola secara eksternal oleh institusi.</span>
              </p>
            </div>
          </div>

          <div class="lg:col-span-2 space-y-6">
              
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] overflow-hidden shadow-sm">
              <div class="p-8 md:p-10">
                <div class="flex items-center gap-3 mb-8">
                  <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                    <i class="bi bi-person-badge text-xl"></i>
                  </div>
                  <h4 class="text-lg font-bold text-slate-900 dark:text-white">Detail Identitas</h4>
                </div>

                <Transition name="fade">
                  <div v-if="$page.props.flash?.message && profileForm.wasSuccessful" class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-2xl flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-sm"></i>
                    {{ $page.props.flash.message }}
                  </div>
                </Transition>

                <form @submit.prevent="updateProfile" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-400 dark:text-zinc-500 outline-none cursor-not-allowed" :value="$page.props.auth.user.name" readonly>
                  </div>

                  <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">{{ identityLabel }}</label>
                    
                    <input 
                      v-if="isMahasiswa"
                      type="text" 
                      class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-400 dark:text-zinc-500 outline-none cursor-not-allowed" 
                      :value="profileForm.nim_nip" 
                      readonly
                    >

                    <template v-else>
                      <input 
                        type="text" 
                        v-model="profileForm.nim_nip"
                        placeholder="Masukkan NIP Anda"
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                        :class="{ 'border-rose-500 focus:ring-rose-500/10': profileForm.errors.nim_nip }"
                      >
                      <span v-if="profileForm.errors.nim_nip" class="text-xs text-rose-500 mt-2 ml-1 block font-medium">
                        {{ profileForm.errors.nim_nip }}
                      </span>
                    </template>
                  </div>

                  <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">{{ dynamicMetaLabel }}</label>
                    <input type="text" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-400 dark:text-zinc-500 outline-none cursor-not-allowed" :value="dynamicMetaValue" readonly>
                  </div>

                  <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Email Institusi</label>
                    <input type="email" class="w-full px-5 py-3.5 bg-slate-100 dark:bg-white/[0.01] border border-slate-200 dark:border-white/5 rounded-2xl text-slate-400 dark:text-zinc-500 outline-none cursor-not-allowed" :value="$page.props.auth.user.email" readonly>
                  </div>

                  <div v-if="!isMahasiswa" class="md:col-span-2 pt-2">
                    <button 
                      type="submit" 
                      :disabled="profileForm.processing"
                      class="px-8 py-3.5 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <span v-if="profileForm.processing">Menyimpan...</span>
                      <span v-else>Perbarui Data Pegawai</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <div v-if="!$page.props.auth.user.google_id" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] overflow-hidden shadow-sm">
              <div class="p-8 md:p-10">
                <div class="flex items-center gap-3 mb-8">
                  <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                    <i class="bi bi-key text-xl"></i>
                  </div>
                  <h4 class="text-lg font-bold text-slate-900 dark:text-white">Ubah Kata Sandi</h4>
                </div>

                <Transition name="fade">
                  <div v-if="$page.props.flash?.message && passwordForm.wasSuccessful" class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold rounded-2xl flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-sm"></i>
                    {{ $page.props.flash.message }}
                  </div>
                </Transition>

                <form @submit.prevent="updatePassword" class="space-y-5">
                  <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Password Saat Ini</label>
                    <input 
                      type="password" 
                      v-model="passwordForm.current_password"
                      class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all" 
                      :class="{ 'border-rose-500 focus:ring-rose-500/10': passwordForm.errors.current_password }"
                    >
                    <span v-if="passwordForm.errors.current_password" class="text-xs text-rose-500 mt-2 ml-1 block font-medium">
                      {{ passwordForm.errors.current_password }}
                    </span>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                      <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Password Baru</label>
                      <input 
                        type="password" 
                        v-model="passwordForm.password"
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                        :class="{ 'border-rose-500 focus:ring-rose-500/10': passwordForm.errors.password }"
                      >
                      <span v-if="passwordForm.errors.password" class="text-xs text-rose-500 mt-2 ml-1 block font-medium">
                        {{ passwordForm.errors.password }}
                      </span>
                    </div>
                    <div>
                      <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Konfirmasi Password Baru</label>
                      <input 
                        type="password" 
                        v-model="passwordForm.password_confirmation"
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 rounded-2xl text-slate-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                      >
                    </div>
                  </div>

                  <div class="pt-4">
                    <button 
                      type="submit" 
                      :disabled="passwordForm.processing"
                      class="px-8 py-3.5 bg-slate-900 dark:bg-white dark:text-zinc-950 text-white font-bold rounded-2xl hover:opacity-90 transition-all shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <span v-if="passwordForm.processing">Menyimpan...</span>
                      <span v-else>Simpan Perubahan Password</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <div v-else class="bg-emerald-500/5 border border-emerald-500/10 rounded-[2rem] p-6 flex items-start gap-4">
              <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex-shrink-0 flex items-center justify-center">
                <i class="bi bi-google"></i>
              </div>
              <div>
                <h5 class="text-emerald-800 dark:text-emerald-400 font-bold text-sm">Akun Terhubung dengan Google</h5>
                <p class="text-emerald-700/60 dark:text-emerald-400/60 text-xs mt-1 leading-relaxed">
                  Pengaturan kata sandi dilakukan melalui panel keamanan akun Google Anda. Kata sandi tidak dapat diubah dari sistem laboratorium ini demi keamanan integrasi SSO.
                </p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Logika Deteksi Peran (Mahasiswa vs Pegawai/Dosen)
const emailPrefix = computed(() => {
  const email = user.value?.email || '';
  return email.split('@')[0];
});

const isMahasiswa = computed(() => {
  return /^[Hh]\d+/.test(emailPrefix.value);
});

// Menentukan Label Dinamis Identitas Resmi
const identityLabel = computed(() => {
  return isMahasiswa.value ? 'NIM' : 'NIP / Identitas Pegawai';
});

// Menentukan Label Meta Kanan (Program Studi vs Jabatan)
const dynamicMetaLabel = computed(() => {
  return isMahasiswa.value ? 'Program Studi' : 'Jabatan / Posisi';
});

// Menentukan Isian Konten Meta Kanan
const dynamicMetaValue = computed(() => {
  if (!isMahasiswa.value) {
    if (user.value?.is_admin) return 'Administrator Laboratorium / PLP';
    return 'Dosen / Tenaga Pendidik FMIPA';
  }

  const prodiCodes = {
    'H101': 'Matematika',
    'H102': 'Fisika',
    'H103': 'Kimia',
    'H104': 'Biologi',
    'H105': 'Sistem Komputer',
    'H107': 'Geofisika',
    'H108': 'Statistika',
    'H109': 'Sistem Informasi',
    'H110': 'Ilmu Kelautan',
  };

  const currentIdentifier = user.value?.nim_nip || emailPrefix.value;
  const subStr = currentIdentifier.substring(0, 4).toUpperCase();
  return prodiCodes[subStr] || 'Mahasiswa FMIPA';
});

// Pemrosesan URL Avatar & Fallback Gambar
const fallbackAvatar = computed(() => {
  const nameParam = encodeURIComponent(user.value?.name || 'User');
  return `https://ui-avatars.com/api/?name=${nameParam}&background=0D8ABC&color=fff&size=256`;
});

const userPhoto = computed(() => {
  let photo = user.value?.avatar;
  if (user.value?.google_id && photo) {
    photo = photo.replace('http://', 'https://');
  }
  return photo || fallbackAvatar.value;
});

const handleAvatarError = (e) => {
  e.target.src = fallbackAvatar.value;
};

// Logika Teks Lencana Badge Atas
const statusBadgeText = computed(() => {
  if (user.value?.is_admin) return 'Administrator System';
  if (isMahasiswa.value) return 'Mahasiswa Aktif';
  return 'Civitas / Pegawai Resmi';
});

// ----------------------------------------------------
// FORM 1: Inertia Form Helper Pembaruan Profil (NIP Pegawai)
// ----------------------------------------------------
const profileForm = useForm({
  name: page.props.auth.user?.name || '',
  email: (page.props.auth.user?.email || '').toLowerCase(),
  nim_nip: page.props.auth.user?.nim_nip || '',
});

const updateProfile = () => {
  profileForm.patch(route('profile.update'), {
    preserveScroll: true,
  });
};

// ----------------------------------------------------
// FORM 2: Inertia Form Helper Pembaruan Password
// ----------------------------------------------------
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
  });
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>