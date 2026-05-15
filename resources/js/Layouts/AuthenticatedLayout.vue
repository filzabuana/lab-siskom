<template>
  <div :class="{ 'dark': isDark }" class="min-h-screen transition-colors duration-300">
    <!-- Main Wrapper -->
    <div class="min-h-screen bg-slate-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100">
      
      <!-- Overlay untuk Mobile -->
      <div 
        v-if="drawer" 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden transition-opacity"
        @click="drawer = false"
      ></div>

      <!-- Sidebar: Statis di Desktop (lg:translate-x-0), Toggleable di Mobile -->
      <aside 
        :class="drawer ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-[70] w-[280px] bg-white dark:bg-[#1e293b] border-r border-slate-200 dark:border-slate-800 transition-transform duration-300 ease-in-out lg:translate-x-0"
      >
        <!-- Logo Section -->
        <div class="p-8 flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20">
              <i class="bi bi-cpu-fill text-white text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="font-black tracking-tighter text-xl italic uppercase leading-none text-slate-900 dark:text-white">
                LAB <span class="text-blue-600">UNTAN</span>
              </span>
              <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold tracking-[0.2em] uppercase mt-1">Computation Unit</span>
            </div>
          </div>
          <!-- Tombol Close (Hanya muncul di Mobile) -->
          <button @click="drawer = false" class="lg:hidden text-slate-400 hover:text-red-500 transition-colors">
            <i class="bi bi-x-lg text-xl"></i>
          </button>
        </div>

        <div class="mx-6 mb-6 h-px bg-slate-200 dark:bg-slate-700 opacity-50"></div>

        <!-- Navigation Menu -->
        <nav class="px-4 space-y-2 overflow-y-auto max-h-[calc(100vh-250px)]">
          <button @click="visit('dashboard')" class="group w-full flex items-center gap-3 px-4 py-3 rounded-lg font-black text-xs tracking-widest transition-all hover:bg-slate-100 dark:hover:bg-slate-800/50 uppercase italic text-left text-slate-700 dark:text-slate-300">
            <i class="bi bi-speedometer2 text-lg text-slate-400 group-hover:text-blue-600 transition-colors"></i>
            DASHBOARD
          </button>

          <!-- Menu Layanan -->
          <div v-if="auth.isMahasiswa" class="pt-4 space-y-1">
            <div class="px-4 py-2 text-[10px] font-black text-slate-400 dark:text-slate-500 tracking-[0.2em] uppercase italic">Layanan</div>
            <button @click="visit('bebas-lab.index')" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg font-bold text-xs hover:bg-slate-100 dark:hover:bg-slate-800/50 italic text-left text-slate-700 dark:text-slate-300">
              <i class="bi bi-file-earmark-check text-lg text-blue-600"></i>
              BEBAS LAB
            </button>
          </div>

          <!-- Menu Manajemen -->
          <div v-if="auth.isAdmin || auth.isKepalaLab" class="pt-4 space-y-1">
            <div class="px-4 py-2 text-[10px] font-black text-slate-400 dark:text-slate-500 tracking-[0.2em] uppercase italic">Manajemen</div>
            <button @click="visit('assets.index')" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg font-bold text-xs hover:bg-slate-100 dark:hover:bg-slate-800/50 italic text-left text-slate-700 dark:text-slate-300">
              <i class="bi bi-box-seam text-lg text-blue-600"></i>
              INVENTORI ASET
            </button>
            <button @click="visit('admin.users.index')" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg font-bold text-xs hover:bg-slate-100 dark:hover:bg-slate-800/50 italic text-left text-slate-700 dark:text-slate-300">
              <i class="bi bi-people text-lg text-blue-600"></i>
              MANAJEMEN USER
            </button>
          </div>

          <!-- Simulator Link -->
          <a href="/apps" class="flex items-center justify-between px-4 py-3 mt-8 rounded-lg font-black text-xs tracking-widest bg-slate-900 dark:bg-white text-white dark:text-slate-900 italic transition-all hover:scale-[1.02] active:scale-95 shadow-md">
            <span class="flex items-center gap-3">
              <i class="bi bi-controller text-lg"></i>
              SIMULATOR
            </span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </nav>

        <!-- User Profile Card -->
        <div class="absolute bottom-0 w-full p-4 bg-white dark:bg-[#1e293b]">
          <div class="bg-slate-100 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex items-center gap-3 overflow-hidden">
            <div class="w-10 h-10 shrink-0 bg-blue-600/10 border border-blue-600/20 rounded-lg flex items-center justify-center font-black text-blue-600 italic uppercase">
              {{ auth.user?.name ? auth.user.name.charAt(0) : '?' }}
            </div>
            <div class="overflow-hidden text-left">
              <div class="text-[10px] font-black truncate uppercase text-slate-900 dark:text-slate-100">
                {{ auth.user?.name || 'Guest User' }}
              </div>
              <div class="text-[8px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tighter truncate">
                {{ safeDisplayRole }}
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <div class="lg:ml-[280px] transition-all duration-300 min-h-screen flex flex-col">
        <!-- App Bar -->
        <header class="h-16 flex items-center justify-between px-4 md:px-8 bg-white/80 dark:bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40">
          <div class="flex items-center gap-4">
            <!-- Tombol Menu: Hanya muncul di Mobile -->
            <button @click="drawer = !drawer" class="lg:hidden text-slate-900 dark:text-white p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
              <i class="bi bi-list text-2xl"></i>
            </button>
            <span class="font-black text-sm italic tracking-tighter dark:text-white uppercase">
              {{ $page.props.header || 'System Administration' }}
            </span>
          </div>

          <div class="flex items-center gap-3 md:gap-4">
            <!-- THEME SWITCHER -->
            <button @click="toggleTheme" class="group relative p-2 w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-800 hover:border-blue-600 transition-all">
              <i v-if="isDark" class="bi bi-sun-fill text-amber-500 text-lg"></i>
              <i v-else class="bi bi-moon-stars-fill text-blue-700 text-lg"></i>
            </button>

            <div class="hidden sm:block h-8 w-px bg-slate-200 dark:bg-slate-800 mx-1 md:mx-2"></div>

            <button @click="logout" class="flex items-center gap-2 group text-red-500 font-black text-[10px] tracking-widest italic uppercase">
              <span class="hidden md:inline-block">LOGOUT</span>
              <i class="bi bi-box-arrow-right text-xl"></i>
            </button>
          </div>
        </header>

        <!-- Content Body -->
        <main class="p-4 md:p-10 flex-grow">
          <div class="max-w-7xl mx-auto">
            <slot />
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useAuthStore } from '@/Stores/useAuthStore';

const drawer = ref(false); 
const auth = useAuthStore();
const isDark = ref(true);

// Tutup sidebar di mobile setelah pindah halaman
watch(() => usePage().url, () => {
  if (window.innerWidth < 1024) {
    drawer.value = false;
  }
});

const safeDisplayRole = computed(() => {
  try {
    const role = auth.displayRole;
    return (typeof role === 'string' ? role : String(role || 'USER')).toUpperCase();
  } catch (e) { return 'USER'; }
});

const toggleTheme = () => {
  isDark.value = !isDark.value;
  applyTheme();
};

const applyTheme = () => {
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
};

onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    isDark.value = savedTheme === 'dark';
  } else {
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  applyTheme();
});

const visit = (routeName) => { router.get(route(routeName)); };
const logout = () => {
    router.post(route('logout'), {}, {
        onFinish: () => {
            //Paksa browser pindah total ke home
            window.location.href = '/';
        }
    });
};
</script>

<style scoped>
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
.dark ::-webkit-scrollbar-thumb { background: #1e293b; }

main {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>