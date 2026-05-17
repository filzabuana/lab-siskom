import './bootstrap';
import { createApp, h, defineAsyncComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { useAuthStore } from './Stores/useAuthStore';
import { ZiggyVue } from 'ziggy-js'; 
import { Ziggy } from './ziggy'; 


createInertiaApp({
    title: (title) => `${title} - Lab FMIPA UNTAN`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        if (!page) {
            console.error(`Gagal memuat halaman: ./Pages/${name}.vue`);
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        // 1. Inisialisasi Pinia
        const pinia = createPinia();
        
        // 2. Buat instance Vue App
        const app = createApp({ render: () => h(App, props) });

        // 3. Gunakan Plugin secara berurutan
        app.use(plugin)
           .use(pinia)
          

        // 4. Integrasi Ziggy (Routing)
        if (typeof Ziggy !== 'undefined') {
            app.use(ZiggyVue, Ziggy);
        }

        // 5. Registrasi Global Component secara Async
        app.component('logic-gate', defineAsyncComponent(() => 
            import('./components/LogicGate.vue')
        ));

        // 6. SINKRONISASI AUTH STORE (DIPERBAIKI)
        // Kita bungkus dalam try-catch agar jika data auth kosong, 
        // aplikasi tidak crash (mencegah error reading '0')
        try {
            const auth = useAuthStore(pinia);
            const userData = props.initialPage?.props?.auth?.user;
            
            if (userData) {
                auth.setUser(userData);
                console.log("--- [AUTH STORE] User Data Loaded ---", userData.name);
            }
        } catch (error) {
            console.warn("Gagal inisialisasi Auth Store:", error);
        }

        // 7. Mount ke elemen #app
        if (el) {
            app.mount(el);
        } else {
            console.error("Elemen root #app tidak ditemukan di DOM.");
        }
    },
    progress: { 
        color: '#2563eb',
        showSpinner: true 
    },
});