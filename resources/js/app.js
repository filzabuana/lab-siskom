import './bootstrap';
import { createApp } from 'vue';
import Swal from 'sweetalert2';
import { ref } from 'vue'; 

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

// Daftarkan ke window agar tetap bisa dipanggil dari file .blade jika diperlukan
window.Swal = LabAlert;

// Import Komponen
import LogicGate from './components/LogicGate.vue';
import HBETrainer from './components/HBETrainer.vue';
import VirtualTrainer from './components/VirtualTrainer.vue';

const app = createApp({
    data() {
        return {
            activeTab: 'basic',
            activeModal: null,
            // 1. PINDAHKAN STATE KE SINI
            selectedPinjam: null,
            showRejectModal: false,
            showNoteModal: false
        }
    },
    methods: {
        // 2. PINDAHKAN LOGIC KE METHODS
        openReject(item) {
            this.selectedPinjam = item;
            this.showRejectModal = true;
        },
        openNote(item) {
            this.selectedPinjam = item;
            this.showNoteModal = true;
        },
        closeReject() {
            this.showRejectModal = false;
            this.selectedPinjam = null;
        },

        openModal(id) {
            this.activeModal = id;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.activeModal = null;
            document.body.style.overflow = 'auto';
        },
        
        notify(title, text, icon = 'success') {
            LabAlert.fire({ title, text, icon });
        },
        // Logic Konfirmasi Hapus khusus Inventaris
        confirmDelete(id, name) {
    // Panggil LabAlert langsung
    LabAlert.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus <br><b class="text-red-600 dark:text-red-400">${name}</b>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus Aset',
        cancelButtonText: 'Batalkan',
        reverseButtons: true,
        // Kita timpa customClass-nya secara manual saja, Bang
        customClass: {
            popup: 'rounded-[2.5rem] bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border shadow-2xl p-8',
            title: 'text-2xl font-black uppercase italic tracking-tighter text-slate-800 dark:text-white',
            htmlContainer: 'text-[11px] uppercase tracking-[0.1em] font-bold text-slate-500 dark:text-slate-400 my-4',
            confirmButton: 'px-8 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full font-black text-[11px] uppercase tracking-widest transition-all scale-100 active:scale-95 shadow-xl shadow-red-500/20 mx-2', // Warna Merah
            cancelButton: 'px-8 py-3 bg-slate-100 dark:bg-railway-dark text-slate-600 dark:text-slate-400 rounded-full font-black text-[11px] uppercase tracking-widest mx-2',
            icon: 'border-2'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`delete-form-${id}`);
            if (form) {
                form.submit();
            } else {
                console.error(`Form delete-form-${id} tidak ditemukan.`);
            }
        }
    });
}
    }
});

// Jika pakai Vue 3 Composition API
const selectedPinjam = ref(null); // Menyimpan objek pinjam yang aktif
const showRejectModal = ref(false);
const showNoteModal = ref(false);

const openReject = (item) => {
    selectedPinjam.value = item;
    showRejectModal.value = true;
}

const openNote = (item) => {
    selectedPinjam.value = item;
    showNoteModal.value = true;
}

// Tambahkan Swal sebagai global property (Bisa dipanggil via this.$swal di komponen .vue)
app.config.globalProperties.$swal = LabAlert;

// Registrasi Komponen
app.component('logic-gate', LogicGate);
app.component('hbe-trainer', HBETrainer);
app.component('virtual-trainer', VirtualTrainer);

// Mount App ke ID #app
app.mount('#app');