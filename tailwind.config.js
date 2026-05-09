import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    // 1. Tambahkan darkMode selector
    darkMode: 'class', 

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',   
        './resources/js/**/*.vue',  
    ],

    theme: {
        extend: {
            fontFamily: {
                // 2. Prioritaskan Inter karena desain kita memakai vibe modern
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['Fira Code', 'Cascadia Code', ...defaultTheme.fontFamily.mono],
            },
            // 3. Menambahkan kustomisasi warna jika ingin lebih presisi
            colors: {
                zinc: {
                    950: '#09090b', // Warna hampir hitam untuk dark mode yang lebih deep
                }
            }
        },
    },

    plugins: [
        forms,
        typography, // Penting untuk konten artikel dari markdown
    ],
};