<template>
    <div class="p-5 bg-white shadow rounded-lg border-2 border-blue-500 text-center">
        <h2 class="text-xl font-bold mb-2">Status Gerbang: {{ status ? 'AKTIF (1)' : 'MATI (0)' }}</h2>
        <button 
            @click="status = !status"
            :class="status ? 'bg-green-500' : 'bg-red-500'"
            class="px-4 py-2 text-white rounded transition"
        >
            Klik untuk Ubah Logic
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const status = ref(false);
</script>