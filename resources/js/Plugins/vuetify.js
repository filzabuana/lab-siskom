import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Menggunakan MDI Webfont untuk mendukung ikon mdi-flask, mdi-database, dll
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import '@mdi/font/css/materialdesignicons.css' 

// Definisi palet warna Railway sesuai spek Bapak
const railwayTheme = {
    dark: true,
    colors: {
        background: '#09090b',     // Zinc 950 (Latar belakang utama)
        surface: '#111827',        // Railway Card / Slate 900
        'surface-variant': '#1f2937', // Railway Border / Slate 800
        primary: '#3b82f6',        // Railway Accent / Blue 500
        secondary: '#475569',      // Slate 600
        accent: '#f59e0b',         // Amber 500
        error: '#ef4444',          // Red 500
        info: '#3b82f6',           // Blue 500
        success: '#10b981',        // Emerald 500
        warning: '#f59e0b',        // Amber 500
    },
}

export default createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        // Default ke dark mode dengan tema Railway yang baru
        defaultTheme: 'railwayTheme',
        themes: {
            railwayTheme,
        },
    },
    // Konfigurasi tambahan untuk menghilangkan efek kapital otomatis pada tombol
    defaults: {
        VBtn: {
            style: 'text-transform: none;',
        },
        VCard: {
            variant: 'flat',
            border: true,
        }
    }
});