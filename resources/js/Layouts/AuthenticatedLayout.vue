<template>
  <div :class="{ 'dark': isDark }" class="min-h-screen transition-colors duration-300">
    
    <Transition name="slide-down">
        <div v-if="$page.props.auth.impersonator" 
             class="fixed top-0 left-0 right-0 z-[100] bg-gradient-to-r from-amber-600 via-orange-600 to-amber-600 text-white px-4 py-2.5 flex items-center justify-between shadow-2xl border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center animate-pulse border border-white/30">
                    <i class="bi bi-incognito text-xl text-white"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] italic leading-none opacity-80">Impersonation Mode</span>
                    <span class="text-[11px] font-bold uppercase tracking-tight mt-1">
                        Acting as: <span class="underline decoration-2 underline-offset-4 font-black text-amber-100">{{ $page.props.auth.user?.name }}</span>
                    </span>
                </div>
            </div>
            
            <button @click="leaveImpersonate" 
                    class="px-5 py-2 bg-white text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all shadow-xl active:scale-95 flex items-center gap-2 border-b-4 border-amber-200">
                <i class="bi bi-door-open-fill"></i>
                Kembali ke Admin
            </button>
        </div>
    </Transition>

    <div :class="[
            'min-h-screen bg-slate-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100 transition-all duration-500',
            { 'pt-14 md:pt-12': $page.props.auth.impersonator }
         ]">
      
      <Transition name="fade">
          <div v-if="drawer" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden" @click="drawer = false"></div>
      </Transition>

      <aside 
        :class="[
          drawer ? 'translate-x-0' : '-translate-x-full',
          $page.props.auth.impersonator ? 'top-[56px] md:top-[48px]' : 'top-0'
        ]"
        class="fixed inset-y-0 left-0 z-[70] w-[285px] bg-white dark:bg-[#1e293b] border-r border-slate-200 dark:border-slate-800 transition-all duration-300 ease-in-out lg:translate-x-0"
      >
        <div class="p-8 flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30 ring-4 ring-blue-500/10">
              <i class="bi bi-cpu-fill text-white text-2xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="font-black tracking-tighter text-xl italic uppercase leading-none text-slate-900 dark:text-white">
                LAB <span class="text-blue-600">UNTAN</span>
              </span>
              <span class="text-[9px] text-slate-500 dark:text-slate-400 font-black tracking-[0.2em] uppercase mt-1">Computation Unit</span>
            </div>
          </div>
          <button @click="drawer = false" class="lg:hidden text-slate-400 hover:text-red-500 transition-colors">
            <i class="bi bi-x-lg text-xl"></i>
          </button>
        </div>

        <div class="mx-6 mb-6 h-px bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent"></div>

        <nav :class="`px-4 space-y-1.5 overflow-y-auto ${$page.props.auth.impersonator ? 'max-h-[calc(100vh-320px)]' : 'max-h-[calc(100vh-280px)]'}`">
          
          <button @click="visit('dashboard')" 
                  :class="route().current('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50'"
                  class="group w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl font-black text-[11px] tracking-widest transition-all uppercase italic text-left">
            <i class="bi bi-speedometer2 text-lg"></i>
            DASHBOARD
          </button>

          <div v-if="auth.isMahasiswa" class="pt-6">
            <div class="px-4 py-2 text-[9px] font-black text-slate-400 dark:text-slate-500 tracking-[0.3em] uppercase italic">Layanan Akademik</div>
            <button @click="visit('bebas-lab.index')" 
                    :class="route().current('bebas-lab.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all">
              <i class="bi bi-file-earmark-check-fill text-lg"></i>
              SURAT BEBAS LAB
            </button>
          </div>

          <div v-if="auth.isAdmin || auth.isKepalaLab || auth.isPLP" class="pt-6">
            <div class="px-4 py-2 text-[9px] font-black text-slate-400 dark:text-slate-500 tracking-[0.3em] uppercase italic">Manajemen Internal</div>
            
            <button @click="visit('admin.inventaris.index')" 
                    :class="route().current('admin.inventaris.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all">
              <i class="bi bi-box-seam-fill text-lg"></i>
              INVENTORI ASET
            </button>

            <button v-if="auth.isAdmin" 
                    @click="visit('admin.users.index')" 
                    :class="route().current('admin.users.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all">
              <i class="bi bi-people-fill text-lg"></i>
              MANAJEMEN USER
            </button>
          </div>

          <div class="px-2 pt-8">
            <a href="/apps" target="_blank" class="flex items-center justify-between px-4 py-4 rounded-2xl font-black text-[10px] tracking-widest bg-slate-900 dark:bg-white text-white dark:text-slate-900 italic transition-all hover:scale-[1.03] active:scale-95 shadow-xl shadow-slate-900/10 dark:shadow-white/5">
                <span class="flex items-center gap-3">
                  <i class="bi bi-controller text-xl"></i>
                  SIMULATOR LAB
                </span>
                <i class="bi bi-arrow-right-short text-xl"></i>
            </a>
          </div>
        </nav>

        <div class="absolute bottom-0 w-full p-4 bg-white dark:bg-[#1e293b] border-t border-slate-100 dark:border-slate-800">
          <div class="bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3 overflow-hidden">
            <div class="w-11 h-11 shrink-0 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center font-black text-white italic shadow-lg ring-2 ring-white dark:ring-slate-800 uppercase">
              {{ auth.user?.name ? auth.user.name.charAt(0) : '?' }}
            </div>
            <div class="overflow-hidden">
              <div class="text-[10px] font-black truncate uppercase text-slate-900 dark:text-white leading-tight">
                {{ auth.user?.name || 'Guest' }}
              </div>
              <div class="text-[8px] font-bold text-blue-500 uppercase tracking-tighter truncate mt-1">
                {{ safeDisplayRole }}
              </div>
            </div>
          </div>
        </div>
      </aside>

      <div class="lg:ml-[285px] transition-all duration-300 min-h-screen flex flex-col">
        
        <header class="h-16 flex items-center justify-between px-4 md:px-8 bg-white/80 dark:bg-[#0f172a]/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40">
          <div class="flex items-center gap-4">
            <button @click="drawer = !drawer" class="lg:hidden text-slate-900 dark:text-white p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
              <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="flex flex-col">
                <span class="font-black text-[11px] italic tracking-widest text-blue-600 uppercase leading-none">FMIPA UNTAN</span>
                <span class="font-black text-sm italic tracking-tighter dark:text-white uppercase mt-1">
                  {{ $page.props.header || 'System Administration' }}
                </span>
            </div>
          </div>

          <div class="flex items-center gap-3 md:gap-5">
            <button @click="toggleTheme" class="group relative p-2 w-10 h-10 flex items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-blue-500 bg-white dark:bg-slate-900 transition-all shadow-sm">
              <i v-if="isDark" class="bi bi-sun-fill text-amber-500 text-lg"></i>
              <i v-else class="bi bi-moon-stars-fill text-blue-700 text-lg"></i>
            </button>

            <div class="hidden sm:block h-8 w-px bg-slate-200 dark:bg-slate-800"></div>

            <button @click="logout" class="flex items-center gap-2 group text-red-500 font-black text-[10px] tracking-widest italic uppercase px-3 py-2 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all">
              <span class="hidden md:inline-block">SIGNOUT</span>
              <i class="bi bi-power text-xl"></i>
            </button>
          </div>
        </header>

        <main class="p-5 md:p-10 flex-grow">
          <div class="max-w-7xl mx-auto">
            <slot />
          </div>
        </main>

        <footer class="p-8 border-t border-slate-100 dark:border-slate-800 text-center">
            <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.5em] italic">
                &copy; 2026 Laboratorium Pemrograman dan Komputasi • Universitas Tanjungpura
            </p>
        </footer>
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

// Close drawer on route change (mobile)
watch(() => usePage().url, () => {
  if (window.innerWidth < 1024) {
    drawer.value = false;
  }
});

// Format role display (Spatie Multi-role support)
const safeDisplayRole = computed(() => {
  try {
    const roles = usePage().props.auth.user?.roles;
    if (Array.isArray(roles) && roles.length > 0) {
      return roles.map(r => r.replace('_', ' ')).join(' | ').toUpperCase();
    }
    return 'USER ACCESS';
  } catch (e) { return 'USER ACCESS'; }
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

const visit = (routeName) => { 
    router.get(route(routeName)); 
};

const logout = () => {
    router.post(route('logout'), {}, {
        onFinish: () => { window.location.href = '/'; }
    });
};

const leaveImpersonate = () => {
    // Gunakan window.location.replace agar state browser benar-benar fresh kembali ke Admin
    router.get(route('admin.stop-impersonate'), {}, {
        onSuccess: () => { 
            window.location.replace(route('admin.users.index')); 
        }
    });
};

// Syncing Pinia store with Inertia props
watch(() => usePage().props.auth.user, (newUser) => {
    if (newUser) {
        auth.setUser(newUser);
    }
}, { immediate: true });
</script>

<style scoped>
/* Scrollbar Styling */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.dark ::-webkit-scrollbar-thumb { background: #334155; }

/* Transitions */
.slide-down-enter-active, .slide-down-leave-active { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.slide-down-enter-from, .slide-down-leave-to { transform: translateY(-100%); opacity: 0; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

main {
  animation: pageIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes pageIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>