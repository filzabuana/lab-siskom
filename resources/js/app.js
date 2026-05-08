import './bootstrap';
import { createApp } from 'vue';

// Import komponen
import LogicGate from './components/LogicGate.vue';
import HBETrainer from './components/HBETrainer.vue';
import VirtualTrainer from './components/VirtualTrainer.vue';

const app = createApp({
    data() {
        return {
            // Kita gunakan state ini untuk mengatur tab mana yang aktif
            activeTab: 'basic' 
        }
    }
});

// Daftarkan komponen secara global
app.component('logic-gate', LogicGate);
app.component('hbe-trainer', HBETrainer);
app.component('virtual-trainer', VirtualTrainer);
app.mount('#app');