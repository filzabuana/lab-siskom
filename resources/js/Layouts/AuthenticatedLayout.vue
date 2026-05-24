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
                Kembali
            </button>
        </div>
    </Transition>

    <div :class="[
            'min-h-screen bg-slate-50 dark:bg-railway-dark text-slate-900 dark:text-slate-100 transition-all duration-500',
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
        class="fixed inset-y-0 left-0 z-[70] w-[285px] bg-white dark:bg-railway-card border-r border-slate-200 dark:border-railway-border transition-all duration-300 ease-in-out lg:translate-x-0 shadow-sm dark:shadow-2xl flex flex-col"
      >
        <div class="p-8 flex items-center justify-between shrink-0">
          <div class="flex items-center space-x-3">
            <div class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30 ring-4 ring-blue-500/10">
              <i class="bi bi-cpu-fill text-white text-2xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="font-black tracking-tighter text-xl italic uppercase leading-none text-slate-900 dark:text-white">
                LAB <span class="text-blue-600">SISKOM</span>
              </span>
              <span class="text-[9px] text-slate-500 dark:text-slate-400 font-black tracking-[0.2em] uppercase mt-1">Pemrograman &amp; Komputasi</span>
            </div>
          </div>
          <button @click="drawer = false" class="lg:hidden text-slate-400 hover:text-red-500 transition-colors">
            <i class="bi bi-x-lg text-xl"></i>
          </button>
        </div>

        <div class="mx-6 mb-4 h-px bg-gradient-to-r from-transparent via-slate-200 dark:via-railway-border to-transparent shrink-0"></div>

        <nav class="flex-1 px-4 pb-8 space-y-1.5 overflow-y-auto">
          
          <div class="mb-6 bg-slate-50 dark:bg-railway-dark/50 border border-slate-200 dark:border-railway-border/60 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-11 h-11 shrink-0 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center font-black text-white italic shadow-lg ring-2 ring-white dark:ring-railway-card uppercase overflow-hidden">
              <img v-if="$page.props.auth.user?.avatar" :src="$page.props.auth.user.avatar" class="w-full h-full object-cover" />
              <span v-else>{{ $page.props.auth.user?.name ? $page.props.auth.user.name.charAt(0) : '?' }}</span>
            </div>
            <div class="overflow-hidden">
              <div class="text-[10px] font-black truncate uppercase text-slate-900 dark:text-white leading-tight">
                {{ $page.props.auth.user?.name || 'Guest' }}
              </div>
              <div class="text-[8px] font-bold text-blue-500 uppercase tracking-tighter truncate mt-1">
                {{ safeDisplayRole }}
              </div>
            </div>
          </div>

          <button @click="visit('dashboard')" 
                  @mouseenter="prefetchMenu('dashboard')"
                  @touchstart="prefetchMenu('dashboard')"
                  :class="route().current('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50'"
                  class="group w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl font-black text-[11px] tracking-widest transition-all uppercase italic text-left">
            <i class="bi bi-speedometer2 text-lg"></i>
            DASHBOARD
          </button>


          <div v-if="hasPermission(['request-bebas-lab', 'view-katalog-alat', 'view-riwayat-pinjam','create-presensi'])" class="pt-4">
            <div class="px-4 py-2 text-[9px] font-black text-slate-400 dark:text-slate-500 tracking-[0.3em] uppercase italic">Praktikum</div>
            <button v-if="hasPermission('create-presensi')"
                  @click="visit('admin.attendance.index')" 
                  :class="route().current('admin.attendance.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                  class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all">
              <i class="bi bi-qr-code text-lg"></i>
              PRESENSI PRAKTIKUM
           </button>
          </div>
          <div v-if="hasPermission(['request-bebas-lab', 'view-katalog-alat', 'view-riwayat-pinjam'])" class="pt-4">
            <div class="px-4 py-2 text-[9px] font-black text-slate-400 dark:text-slate-500 tracking-[0.3em] uppercase italic">Layanan Akademik</div>
            
            <a v-if="hasPermission('request-bebas-lab')"
               :href="route('bebas-lab.form')" 
               :class="route().current('bebas-lab.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
               class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all">
                <i class="bi bi-file-earmark-check-fill text-lg"></i>
                SURAT BEBAS LAB
            </a>

            <button v-if="hasPermission('view-katalog-alat')"
                    @click="visit('peminjaman.katalog')" 
                    @mouseenter="prefetchMenu('peminjaman.katalog')"
                    @touchstart="prefetchMenu('peminjaman.katalog')"
                    :class="route().current('peminjaman.katalog') || route().current('peminjaman.cart.view') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all mt-1">
              <i class="bi bi-cart-plus-fill text-lg"></i>
              KATALOG ALAT
            </button>

            <button v-if="hasPermission('view-riwayat-pinjam')"
                    @click="visit('peminjaman.history')" 
                    @mouseenter="prefetchMenu('peminjaman.history')"
                    @touchstart="prefetchMenu('peminjaman.history')"
                    :class="route().current('peminjaman.history') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all mt-1">
              <i class="bi bi-clock-history text-lg"></i>
              RIWAYAT PINJAM
            </button>
          </div>

          <div v-if="hasPermission(['review-peminjaman', 'view-inventaris', 'manage-posts-blog', 'manage-users'])" class="pt-4">
            <div class="px-4 py-2 text-[9px] font-black text-slate-400 dark:text-slate-500 tracking-[0.3em] uppercase italic">Manajemen Internal</div>
            
            <button v-if="hasPermission('review-peminjaman')"
                    @click="visit('admin.peminjaman.index')" 
                    @mouseenter="prefetchMenu('admin.peminjaman.index')"
                    @touchstart="prefetchMenu('admin.peminjaman.index')"
                    :class="route().current('admin.peminjaman.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all">
              <i class="bi bi-clipboard2-check-fill text-lg"></i>
              PERMINTAAN PINJAM
            </button>

            <button v-if="hasPermission('view-inventaris')"
                    @click="visit('admin.inventaris.index')" 
                    @mouseenter="prefetchMenu('admin.inventaris.index')"
                    @touchstart="prefetchMenu('admin.inventaris.index')"
                    :class="route().current('admin.inventaris.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all mt-1">
              <i class="bi bi-box-seam-fill text-lg"></i>
              INVENTORI ASET
            </button>

            <button v-if="hasPermission('manage-posts')"
                    @click="visit('admin.posts.index')" 
                    @mouseenter="prefetchMenu('admin.posts.index')"
                    @touchstart="prefetchMenu('admin.posts.index')"
                    :class="route().current('admin.posts.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all mt-1">
              <i class="bi bi-journal-text text-lg"></i>
              PENULISAN BLOG
            </button>

            <button v-if="hasPermission('manage-users')"
                    @click="visit('admin.users.index')" 
                    @mouseenter="prefetchMenu('admin.users.index')"
                    @touchstart="prefetchMenu('admin.users.index')"
                    :class="route().current('admin.users.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all mt-1">
              <i class="bi bi-people-fill text-lg"></i>
              MANAJEMEN USER
            </button>
            <button v-if="$page.props.auth.user?.is_admin"
                  @click="visit('admin.roles.index')" 
                  @mouseenter="prefetchMenu('admin.roles.index')"
                  @touchstart="prefetchMenu('admin.roles.index')"
                  :class="route().current('admin.roles.*') ? 'text-blue-600 bg-blue-50 dark:bg-blue-500/5 ring-1 ring-blue-500/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
                  class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-[11px] italic text-left transition-all mt-1">
            <i class="bi bi-shield-lock-fill text-lg"></i>
            MANAJEMEN ROLE
          </button>
          </div>
        </nav>
      </aside>

      <div class="lg:ml-[285px] transition-all duration-300 min-h-screen flex flex-col">
        <header class="h-16 flex items-center justify-between px-4 md:px-8 bg-white/80 dark:bg-railway-dark/80 backdrop-blur-xl border-b border-slate-200 dark:border-railway-border sticky top-0 z-40">
          
          <div class="flex items-center">
            <button @click="drawer = !drawer" class="lg:hidden text-slate-900 dark:text-white p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
              <i class="bi bi-list text-2xl"></i>
            </button>
          </div>

          <div class="flex items-center gap-2 md:gap-4">
            
            <button v-if="hasRole(['mahasiswa'])" 
                    @click="visit('peminjaman.cart.view')"
                    @mouseenter="prefetchMenu('peminjaman.cart.view')"
                    @touchstart="prefetchMenu('peminjaman.cart.view')"
                    class="relative p-2 w-10 h-10 flex items-center justify-center rounded-2xl border border-slate-200 dark:border-railway-border hover:border-blue-500 bg-white dark:bg-railway-dark transition-all shadow-sm group">
              <i class="bi bi-cart3 text-lg text-slate-600 dark:text-slate-400 group-hover:text-blue-600 transition-colors"></i>
              
              <span v-if="$page.props.cartCount > 0" 
                    class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 bg-blue-600 text-white text-[10px] font-black rounded-lg flex items-center justify-center ring-4 ring-white dark:ring-railway-dark transition-all">
                {{ $page.props.cartCount }}
              </span>
            </button>

            <button v-if="hasPermission('review-peminjaman')" 
                    @click="visit('admin.peminjaman.index')"
                    @mouseenter="prefetchMenu('admin.peminjaman.index')"
                    @touchstart="prefetchMenu('admin.peminjaman.index')"
                    class="relative p-2 w-10 h-10 flex items-center justify-center rounded-2xl border border-slate-200 dark:border-railway-border hover:border-blue-500 bg-white dark:bg-railway-dark transition-all shadow-sm group">
              <i class="bi bi-bell-fill text-lg text-slate-600 dark:text-slate-400 group-hover:text-amber-500 transition-colors"></i>
              <span v-if="$page.props.pendingCount > 0" 
                    class="absolute -top-1 -right-1 w-5 h-5 bg-amber-500 text-white text-[10px] font-black rounded-lg flex items-center justify-center ring-4 ring-white dark:ring-railway-dark animate-bounce">
                {{ $page.props.pendingCount }}
              </span>
            </button>

            <button @click="toggleTheme" class="group relative p-2 w-10 h-10 flex items-center justify-center rounded-2xl border border-slate-200 dark:border-railway-border hover:border-blue-500 bg-white dark:bg-railway-dark transition-all shadow-sm">
              <i class="bi bi-sun-fill text-amber-500 text-lg" v-if="isDark"></i>
              <i class="bi bi-moon-stars-fill text-blue-700 text-lg" v-else></i>
            </button>

            <div class="h-8 w-px bg-slate-200 dark:bg-railway-border mx-1"></div>

            <div class="relative">
              <button 
                @click="showProfileDropdown = !showProfileDropdown"
                class="flex items-center gap-2 p-1 pr-3 rounded-2xl border border-slate-200 dark:border-railway-border bg-white dark:bg-railway-dark hover:border-blue-500/50 transition-all shadow-sm active:scale-95 group"
              >
                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white text-xs font-black uppercase tracking-tighter italic overflow-hidden shadow-inner">
                  <img v-if="$page.props.auth.user?.avatar" :src="$page.props.auth.user.avatar" class="w-full h-full object-cover" />
                  <span v-else>{{ $page.props.auth.user?.name ? $page.props.auth.user.name.charAt(0) : '?' }}</span>
                </div>
                <i class="bi bi-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-colors"></i>
              </button>

              <Transition name="fade">
                <div 
                  v-if="showProfileDropdown"
                  class="absolute right-0 mt-2 w-56 bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-2xl shadow-xl py-2 z-50 animate-dropdown"
                >
                  <div class="px-4 py-2.5 border-b border-slate-100 dark:border-railway-border mb-1">
                    <p class="text-[9px] font-black tracking-widest text-blue-600 dark:text-blue-400 uppercase italic">Identitas Login</p>
                    <p class="text-xs font-black uppercase text-slate-900 dark:text-white truncate mt-1">{{ $page.props.auth.user?.name }}</p>
                    <p class="text-[9px] font-mono text-slate-400 truncate mt-0.5">{{ $page.props.auth.user?.email }}</p>
                  </div>

                  <Link 
                    :href="route('profile.edit')"
                    prefetch
                    as="button"
                    type="button"
                    class="w-full flex items-center gap-3 px-4 py-2 text-[10px] font-black tracking-wider uppercase italic text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-blue-600 dark:hover:text-blue-400 transition-all text-left"
                  >
                    <i class="bi bi-person-gear text-base"></i>
                      Profil
                  </Link>

                  <div class="my-1 h-px bg-slate-100 dark:bg-railway-border"></div>

                  <button 
                    @click="logout"
                    class="w-full flex items-center gap-3 px-4 py-2 text-[10px] font-black tracking-wider uppercase italic text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-left"
                  >
                    <i class="bi bi-power text-base"></i>
                    Sign Out
                  </button>
                </div>
              </Transition>
            </div>

          </div>
        </header>

        <main class="p-5 md:p-10 flex-grow">
          <div class="max-w-7xl mx-auto">
            <slot />
          </div>
        </main>

        <footer class="p-8 border-t border-slate-100 dark:border-railway-border text-center shrink-0">
            <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.5em] italic">
                &copy; 2026 Laboratorium Pemrograman dan Komputasi • FMIPA UNTAN
            </p>
        </footer>
      </div>

      <GlobalNotification />

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import GlobalNotification from '@/Components/GlobalNotification.vue';

const drawer = ref(false); 
const isDark = ref(true);
const showProfileDropdown = ref(false); 

// MODIFIKASI: Fungsi Helper untuk mengecek Permission (Mendukung string tunggal maupun array)
const hasPermission = (permissionNames) => {
    const userPermissions = usePage().props.auth.user?.permissions || [];
    
    // Jika user memiliki permission master wildcard '*', langsung loloskan otomatis
    if (userPermissions.includes('*')) return true;

    if (Array.isArray(permissionNames)) {
        return permissionNames.some(perm => userPermissions.includes(perm));
    }
    return userPermissions.includes(permissionNames);
};

const hasRole = (roleNames) => {
    const userRoles = usePage().props.auth.user?.roles || [];
    return roleNames.some(role => userRoles.includes(role));
};

watch(() => usePage().url, () => {
  setTimeout(() => {
    if (window.innerWidth < 1024) {
      drawer.value = false;
    }
    showProfileDropdown.value = false; 
  }, 150);
});

const safeDisplayRole = computed(() => {
  const user = usePage().props.auth.user;
  if (!user) return 'GUEST';
  
  let roles = [];
  if (Array.isArray(user.roles)) roles = [...user.roles];
  if (user.is_admin) roles.unshift('superadmin');
  
  return roles.length > 0 
    ? roles.map(r => r.replace('_', ' ')).join(' | ').toUpperCase() 
    : 'USER ACCESS';
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

  window.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      showProfileDropdown.value = false;
    }
  });
});

const visit = (routeName) => { 
    router.get(route(routeName)); 
};

const prefetchMenu = (routeName) => {
    router.prefetch(route(routeName), { method: 'get' });
};

const logout = () => {
    router.post(route('logout'), {}, {
        onFinish: () => { window.location.href = '/'; }
    });
};

const leaveImpersonate = () => {
    router.get(route('admin.stop-impersonate'), {}, {
        onFinish: () => { 
            window.location.replace(route('admin.users.index')); 
        }
    });
};
</script>

<style scoped>
/* Style bawaan Anda tetap utuh */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.dark ::-webkit-scrollbar-thumb { background: #1f2937; } 
.slide-down-enter-active, .slide-down-leave-active { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.slide-down-enter-from, .slide-down-leave-to { transform: translateY(-100%); opacity: 0; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.animate-dropdown {
  animation: dropdownIn 0.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes dropdownIn {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}

main { animation: pageIn 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
@keyframes pageIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>