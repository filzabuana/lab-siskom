import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null, 
    }),
    
    getters: {
        // Mengembalikan array peran, jika kosong return array kosong agar tidak crash
        userRoles: (state) => Array.isArray(state.user?.roles) ? state.user.roles : [],

        // Format nama peran untuk tampilan (misal: asisten_praktikum -> Asisten Praktikum)
        displayRole: (state) => {
            const role = Array.isArray(state.user?.roles) ? state.user.roles[0] : 'User';
            return role.replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },

        // Pengecekan Admin atau PLP
        isAdmin: (state) => {
            const roles = Array.isArray(state.user?.roles) ? state.user.roles : [];
            return roles.includes('admin') || roles.includes('plp') || !!state.user?.is_admin;
        },

        // Cek NIM dengan awalan 'H' untuk Mahasiswa FMIPA UNTAN
        isMahasiswa: (state) => {
            return typeof state.user?.nim === 'string' && state.user.nim.toUpperCase().startsWith('H');
        },

        // Pengecekan peran lainnya
        isAsisten: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('asisten_praktikum'),
        isDosen: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('dosen'),
        isKepalaLab: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('kepala_lab'),
        
        isAuthenticated: (state) => !!state.user,
    },

    actions: {
        setUser(userData) {
            if (userData) {
                this.user = userData;
            }
        },
        clearUser() {
            this.user = null;
        }
    }
});