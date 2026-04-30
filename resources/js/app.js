import './bootstrap';

import Alpine from 'alpinejs';
import mermaid from 'mermaid';

window.Alpine = Alpine;

Alpine.start();



mermaid.initialize({
    startOnLoad: true,
    theme: 'forest', // Anda bisa ganti ke 'default', 'dark', atau 'neutral'
});
