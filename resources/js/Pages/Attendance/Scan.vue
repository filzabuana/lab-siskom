<template>
    <AuthenticatedLayout>
        <div class="max-w-md mx-auto px-4 pb-20">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black italic uppercase tracking-tighter text-slate-900 dark:text-white">
                    PRESENSI <span class="text-blue-600">SCAN</span>
                </h1>
                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-black uppercase tracking-[0.2em] mt-2">
                    Arahkan kamera ke QR Code di depan layar
                </p>
            </div>

            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-[2.5rem] blur opacity-25"></div>
                
                <div class="relative bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <div class="relative aspect-square bg-black overflow-hidden">
                        <div id="reader" class="w-full h-full object-cover"></div>
                        
                        <div v-if="isCameraReady && !isProcessing" class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            <div class="w-64 h-64 border-2 border-blue-500/50 rounded-3xl relative">
                                <div class="absolute -top-1 -left-1 w-8 h-8 border-t-4 border-l-4 border-blue-500 rounded-tl-xl"></div>
                                <div class="absolute -top-1 -right-1 w-8 h-8 border-t-4 border-r-4 border-blue-500 rounded-tr-xl"></div>
                                <div class="absolute -bottom-1 -left-1 w-8 h-8 border-b-4 border-l-4 border-blue-500 rounded-bl-xl"></div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 border-b-4 border-r-4 border-blue-500 rounded-br-xl"></div>
                                <div class="absolute inset-x-4 h-[2px] bg-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.8)] animate-scan-line top-0"></div>
                            </div>
                        </div>

                        <div v-if="isProcessing" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6">
                            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                            <h3 class="text-white font-black italic uppercase tracking-widest text-sm">Memproses...</h3>
                            <p class="text-slate-400 text-[10px] uppercase font-bold mt-2">Sedang mencatat kehadiran Anda</p>
                        </div>

                        <div v-if="!isCameraReady && !isProcessing" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500 mb-4"></div>
                            <p class="text-xs font-black uppercase text-slate-400 italic tracking-widest">Menyiapkan Kamera...</p>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 dark:bg-railway-dark/50 border-t border-slate-200 dark:border-railway-border">
                        <div class="space-y-4">
                            <div v-if="cameras.length > 1">
                                <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-2 block">Lensa Kamera</label>
                                <select v-model="selectedCameraId" @change="switchCamera" 
                                    class="w-full bg-white dark:bg-railway-card border border-slate-200 dark:border-railway-border rounded-xl px-4 py-2 text-xs font-bold dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                                    <option v-for="cam in cameras" :key="cam.id" :value="cam.id">{{ cam.label }}</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3">
                                <div :class="isCameraReady ? 'bg-green-500' : 'bg-slate-500'" class="w-2 h-2 rounded-full animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase italic tracking-widest dark:text-slate-300">
                                    {{ isCameraReady ? 'Siap Memindai' : 'Mencari Sensor' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import { Html5Qrcode } from 'html5-qrcode';
import { router } from '@inertiajs/vue3';

const isCameraReady = ref(false);
const isProcessing = ref(false);
const cameras = ref([]);
const selectedCameraId = ref('');
let html5QrCode = null;

const onScanSuccess = async (decodedText) => {
    if (isProcessing.value) return;
    
    isProcessing.value = true; // Munculkan overlay loading di layar scanner
    await html5QrCode.stop();

    // Beri jeda dramatis 2 detik agar mahasiswa merasa proses sedang berjalan
    setTimeout(() => {
        router.post(route('attendance.scan.process'), {
            token: decodedText
        }, {
            onFinish: () => {
                // Jangan set isProcessing ke false di sini agar 
                // layar tetap loading sampai redirect selesai
            }
        });
    }, 2000); 
};

const startScanner = async () => {
    try {
        const devices = await Html5Qrcode.getCameras();
        if (devices && devices.length > 0) {
            cameras.value = devices;
            const backCamera = devices.find(d => d.label.toLowerCase().includes('back'));
            selectedCameraId.value = backCamera ? backCamera.id : devices[0].id;
            
            html5QrCode = new Html5Qrcode("reader");
            await runScanner();
        }
    } catch (err) {
        console.error("Camera Error:", err);
    }
};

const runScanner = async () => {
    isCameraReady.value = false;
    await html5QrCode.start(
        selectedCameraId.value,
        { fps: 20, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
        onScanSuccess
    );
    isCameraReady.value = true;
};

const switchCamera = async () => {
    if (html5QrCode.isScanning) await html5QrCode.stop();
    await runScanner();
};

onMounted(() => startScanner());
onUnmounted(() => {
    if (html5QrCode?.isScanning) html5QrCode.stop();
});
</script>

<style scoped>
@keyframes scan-line { 0% { top: 0%; } 100% { top: 100%; } }
.animate-scan-line { position: absolute; animation: scan-line 2.5s linear infinite; }
</style>