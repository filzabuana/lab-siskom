import './bootstrap';
import { createApp, defineAsyncComponent } from 'vue';
import Swal from 'sweetalert2';

// 1. Import Komponen Statis (Ringan)
import LogicGate from './components/LogicGate.vue';
import HBETrainer from './components/HBETrainer.vue';
import VirtualTrainer from './components/VirtualTrainer.vue';

// 2. Import Komponen Berat (Async)
// Menggunakan defineAsyncComponent untuk library berbasis canvas/flow agar lebih stabil
const FlowchartEditorTabs = defineAsyncComponent(() => 
    import('./components/FlowchartEditorTabs.vue')
);
const FlowchartViewer = defineAsyncComponent(() => 
    import('./components/FlowchartViewer.vue')
);
const FlowchartMakerComponent = defineAsyncComponent(() => 
    import('./components/FlowchartMakerComponent.vue')
);

// 3. Konfigurasi SweetAlert2 Mixin
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
    showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
    hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' }
});

window.Swal = LabAlert;

// 4. Inisialisasi App
const app = createApp({
    data() {
        return {
            activeTab: 'basic',
            activeModal: null,
            selectedPinjam: null,
            showRejectModal: false,
            showNoteModal: false
        }
    },
    methods: {
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
        confirmDelete(id, name) {
            LabAlert.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus <br><b class="text-red-600 dark:text-red-400">${name}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Aset',
                cancelButtonText: 'Batalkan',
                reverseButtons: true,
                customClass: {
                    ...LabAlert.getConfig().customClass,
                    confirmButton: 'px-8 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full font-black text-[11px] uppercase tracking-widest transition-all scale-100 active:scale-95 shadow-xl shadow-red-500/20 mx-2',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(`delete-form-${id}`);
                    if (form) form.submit();
                }
            });
        }
    }
});

// 5. Registrasi Global Properties & Komponen
app.config.globalProperties.$swal = LabAlert;

app.component('logic-gate', LogicGate);
app.component('hbe-trainer', HBETrainer);
app.component('virtual-trainer', VirtualTrainer);
app.component('flowchart-maker-component', FlowchartMakerComponent);
app.component('flowchart-editor-tabs', FlowchartEditorTabs);
app.component('flowchart-viewer', FlowchartViewer);

// 6. Mount
app.mount('#app');