import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'; // Penting untuk memetakan folder vendor

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/js/app-blade.js',
                'resources/js/app-inertia.js',],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // Alias untuk mendeteksi Ziggy dari folder vendor PHP
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
            
            // Mempertahankan kompatibilitas komponen legacy Anda
            'vue': 'vue/dist/vue.esm-bundler.js',
            
            // Alias tambahan untuk mempermudah import (opsional)
            '@': path.resolve('resources/js'),
        },
    },
    optimizeDeps: {
        // Tetap mengecualikan library flow agar tidak terjadi error saat pre-bundling
        exclude: ['@vue-flow/core', '@vue-flow/background', '@vue-flow/controls']
    },
    build: {
        chunkSizeWarningLimit: 1600, // Mengingat Anda punya komponen berat (flowchart)
    }
});