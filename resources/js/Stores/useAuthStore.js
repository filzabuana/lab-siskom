import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null, 
    }),
    
    getters: {
        // Tambahkan getter untuk avatar agar aman diakses
        userAvatar: (state) => state.user?.avatar || null,

        userRoles: (state) => Array.isArray(state.user?.roles) ? state.user.roles : [],

        displayRole: (state) => {
            const roles = Array.isArray(state.user?.roles) ? state.user.roles : [];
            if (roles.length === 0) return 'User';
            // Ambil role pertama, ganti snake_case jadi Capital Case
            return roles[0].replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },

        isAdmin: (state) => {
            const roles = Array.isArray(state.user?.roles) ? state.user.roles : [];
            return roles.includes('admin') || roles.includes('plp') || !!state.user?.is_admin;
        },

        isPlp: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('plp'),

        isMahasiswa: (state) => {
            return typeof state.user?.nim_nip === 'string' && state.user.nim_nip.toUpperCase().startsWith('H');
        },

        isAsisten: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('asisten_praktikum'),
        isDosen: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('dosen'),
        isKalab: (state) => (Array.isArray(state.user?.roles) ? state.user.roles : []).includes('kepala_lab'),
        
        isAuthenticated: (state) => !!state.user,
    },

    actions: {
        /**
         * Pastikan saat setUser dipanggil, seluruh object user (termasuk avatar) disimpan.
         */
        setUser(userData) {
            this.user = userData;
        },
        clearUser() {
            this.user = null;
        }
    }
});