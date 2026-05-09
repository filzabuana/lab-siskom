import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['Fira Code', 'Cascadia Code', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                zinc: {
                    950: '#09090b',
                },
                // Menambahkan warna identitas agar konsisten
            railway: {
                dark: '#0a0f1c',   // Biru gelap yang Bapak suka
                card: '#111827',   // Warna card yang serasi (Slate 900)
                border: '#1f2937', // Warna border (Slate 800)
                accent: '#3b82f6', // Biru terang (tetap)
            }
            },
            animation: {
                'marquee-horizontal': 'marquee-horizontal 40s linear infinite',
            },
            keyframes: {
                'marquee-horizontal': {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                }
            }
        },
    },

    plugins: [
        forms,
        typography,
    ],
};