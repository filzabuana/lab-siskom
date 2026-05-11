import './bootstrap';
import { createApp } from 'vue';
import Swal from 'sweetalert2';

// 1. Konfigurasi SweetAlert2 Mixin (Theme: Railway Lab)
const LabAlert = Swal.mixin({
    customClass: {
        popup: 'rounded-[2.5rem] bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border shadow-2xl p-8',
        title: 'text-2xl font-black uppercase italic tracking-tighter text-slate-800 dark:text-white',
        htmlContainer: 'text-[11px] uppercase tracking-[0.1em] font-bold text-slate-500 dark:text-slate-400 my-4',
        confirmButton: 'px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-black text-[11px] uppercase tracking-widest transition-all scale-100 active:scale-95 shadow-xl shadow-blue-500/20 mx-2',
        cancelButton: 'px-8 py-3 bg-slate-100 dark:bg-railway-dark text-slate-600 dark:text-slate-400 rounded-full font-black text-[11px] uppercase tracking-widest mx-2',
        icon: 'border-2'
    },
    buttonsStyling: false,
    showClass: {
        popup: 'animate__animated animate__fadeInUp animate__faster'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutDown animate__faster'
    }
});

// Daftarkan ke window agar bisa dipanggil dari file .blade (misal: window.Swal.fire)
window.Swal = LabAlert;

// Import Komponen
import LogicGate from './components/LogicGate.vue';
import HBETrainer from './components/HBETrainer.vue';
import VirtualTrainer from './components/VirtualTrainer.vue';

const app = createApp({
    data() {
        return {
            activeTab: 'basic',
            activeModal: null 
        }
    },
    methods: {
        // Kontrol Modal Katalog
        openModal(id) {
            this.activeModal = id;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.activeModal = null;
            document.body.style.overflow = 'auto';
        },
        
        // Helper untuk memanggil alert dari instance Vue
        notify(title, text, icon = 'success') {
            LabAlert.fire({ title, text, icon });
        }
    }
});

// Tambahkan Swal sebagai global property (Bisa dipanggil via this.$swal di komponen)
app.config.globalProperties.$swal = LabAlert;

// Registrasi Komponen
app.component('logic-gate', LogicGate);
app.component('hbe-trainer', HBETrainer);
app.component('virtual-trainer', VirtualTrainer);

// Mount App
app.mount('#app');