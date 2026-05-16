@extends('layouts.modern')

@section('content')
<div class="bg-white dark:bg-[#0f172a] min-h-screen pb-20 font-sans selection:bg-blue-500/30 transition-all overflow-visible">
    
    <div id="scroll-progress" class="fixed top-0 left-0 h-1.5 bg-gradient-to-r from-blue-600 to-cyan-400 z-[10020] transition-all duration-75" style="width: 0%"></div>

    <button id="mobile-toc-btn" class="lg:hidden fixed bottom-6 right-6 z-[10010] w-14 h-14 bg-blue-600 text-white rounded-2xl shadow-2xl flex items-center justify-center border border-blue-400 active:scale-95 transition-all">
        <i class="bi bi-list-nested text-2xl"></i>
    </button>

    <div id="drawer-overlay" class="lg:hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[10000] hidden opacity-0 transition-opacity duration-300"></div>

    <div class="max-w-6xl mx-auto px-6 py-12">
        
        <nav class="mb-10 flex items-center space-x-2 text-[10px] font-bold uppercase tracking-[2px] text-slate-400 dark:text-slate-500 overflow-x-auto whitespace-nowrap no-scrollbar">
            <a href="/" class="hover:text-blue-600 transition-colors">Portal</a>
            <span class="opacity-30 text-lg">/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-blue-600 transition-colors">Documentation</a>
            <span class="opacity-30 text-lg">/</span>
            <span class="text-blue-600 truncate">Doc</span>
        </nav>

        <header class="mb-12">
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tight uppercase leading-none italic mb-8">
                {{ $post->title }}
            </h1>
        </header>

        <div class="flex flex-col lg:flex-row gap-16 items-start overflow-visible relative">
            
            <main class="w-full lg:w-2/3 order-1 flex-shrink-0 overflow-visible">
                <figure class="mb-12 relative overflow-hidden rounded-3xl shadow-2xl border dark:border-slate-800">
                    <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/1200x600/1e293b/ffffff?text=LAB+KOMPUTASI' }}" 
                         class="w-full h-auto object-cover"
                         alt="Cover Image">
                </figure>

                <article id="rendered-markdown" class="article-markdown-engine prose prose-slate dark:prose-invert max-w-none">
                    @php $parsedown = new \Parsedown(); @endphp
                    {!! $parsedown->text($post->content) !!}
                </article>

                <div class="lg:hidden mt-20 bg-blue-600 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden group">
                    <i class="bi bi-cpu absolute -right-4 -bottom-4 text-7xl text-white/10 group-hover:scale-110 transition-transform duration-700"></i>
                    <div class="relative z-10">
                        <h5 class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70">Lab Komputasi</h5>
                        <p class="text-sm font-medium mb-6 italic">FMIPA Universitas Tanjungpura</p>
                        <a href="#" class="inline-block bg-white text-blue-600 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest">Profil Lab</a>
                    </div>
                </div>
            </main>

            <aside class="w-full lg:w-1/3 order-2 sticky top-10 overflow-visible hidden lg:block" id="aside-container">
                <div class="space-y-8">
                    <div id="toc-box" class="bg-white dark:bg-slate-900 lg:bg-slate-50 lg:dark:bg-slate-800/40 p-8 lg:rounded-[2.5rem] border border-slate-100 dark:border-slate-800 lg:shadow-sm">
                        <div class="flex items-center justify-between mb-6 border-b dark:border-slate-700 pb-4">
                            <h4 class="text-[10px] font-black uppercase tracking-[3px] text-slate-400 flex items-center">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span> Navigasi
                            </h4>
                            <button id="close-drawer" class="lg:hidden text-slate-500">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <nav id="toc-sidebar-list" class="flex flex-col space-y-1 no-scrollbar"></nav>
                    </div>

                    <div class="hidden lg:block bg-blue-600 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden group">
                        <i class="bi bi-cpu absolute -right-4 -bottom-4 text-7xl text-white/10 group-hover:scale-110 transition-transform duration-700"></i>
                        <div class="relative z-10">
                            <h5 class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70">Lab Komputasi</h5>
                            <p class="text-sm font-medium mb-6 italic">FMIPA Universitas Tanjungpura</p>
                            <a href="#" class="inline-block bg-white text-blue-600 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest">Profil Lab</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Inisialisasi Elemen
    const elements = {
        tocBox: document.getElementById('toc-box'),
        toggleBtn: document.getElementById('mobile-toc-btn'),
        closeBtn: document.getElementById('close-drawer'),
        overlay: document.getElementById('drawer-overlay'),
        progressBar: document.getElementById("scroll-progress"),
        article: document.getElementById('rendered-markdown'),
        tocList: document.getElementById('toc-sidebar-list'),
        aside: document.getElementById('aside-container')
    };

    // 2. Progress Bar Logic
    if (elements.progressBar) {
        window.addEventListener('scroll', () => {
            const winScroll = window.pageYOffset || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            elements.progressBar.style.width = scrolled + "%";
        });
    }

    // 3. Mobile Drawer Logic
    function toggleDrawer() {
        if (!elements.tocBox || !elements.overlay || window.innerWidth >= 1024) return;
        
        const isActive = elements.tocBox.classList.contains('active');
        if (isActive) {
            elements.tocBox.classList.remove('active');
            elements.overlay.classList.remove('active');
            setTimeout(() => elements.overlay.classList.add('hidden'), 300);
            document.body.style.overflow = '';
        } else {
            elements.overlay.classList.remove('hidden');
            if (elements.aside) elements.aside.classList.remove('hidden'); 
            
            setTimeout(() => {
                elements.tocBox.classList.add('active');
                elements.overlay.classList.add('active');
            }, 10);
            document.body.style.overflow = 'hidden';
        }
    }

    if (elements.toggleBtn) elements.toggleBtn.onclick = toggleDrawer;
    if (elements.closeBtn) elements.closeBtn.onclick = toggleDrawer;
    if (elements.overlay) elements.overlay.onclick = toggleDrawer;

    // 4. TOC Generator
    if (elements.article && elements.tocList) {
        const headings = elements.article.querySelectorAll('h2, h3');
        headings.forEach((heading, index) => {
            const anchorId = 'section-' + index;
            heading.setAttribute('id', anchorId);
            
            const link = document.createElement('a');
            link.href = '#' + anchorId;
            link.textContent = heading.textContent;
            const isH3 = heading.tagName.toLowerCase() === 'h3';
            
            link.className = isH3 
                ? 'pl-6 text-[12px] text-slate-500 py-1.5 border-l border-slate-200 dark:border-slate-800 hover:text-blue-600 transition-all uppercase tracking-wider'
                : 'text-[13px] font-bold text-slate-700 dark:text-slate-300 py-2 border-l-2 border-transparent pl-4 hover:text-blue-600 transition-all tracking-tighter italic uppercase';
            
            link.onclick = (e) => {
                e.preventDefault();
                if (window.innerWidth < 1024) toggleDrawer();
                
                const offset = 100;
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = heading.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
            };
            elements.tocList.appendChild(link);
        });
    }

    // 5. Prism Syntax Highlighting
    if (typeof Prism !== 'undefined') { 
        Prism.highlightAll(); 
    }

    // 6. Copy Button with Fallback (Anti-Tanda Seru)
    const codeBlocks = elements.article ? elements.article.querySelectorAll('pre') : [];
    
    codeBlocks.forEach((block) => {
        block.style.position = 'relative';

        const button = document.createElement('button');
        button.className = 'copy-button'; 
        button.type = 'button';
        button.innerHTML = '<i class="bi bi-clipboard"></i>';
        button.setAttribute('aria-label', 'Copy code');
        block.appendChild(button);

        button.addEventListener('click', () => {
            const code = block.querySelector('code');
            if (!code) return;
            const text = code.innerText;

            // Fungsi sukses
            const showSuccess = () => {
                button.innerHTML = '<i class="bi bi-check2 text-green-400"></i>';
                button.classList.add('border-green-500/50');
                setTimeout(() => {
                    button.innerHTML = '<i class="bi bi-clipboard"></i>';
                    button.classList.remove('border-green-500/50');
                }, 2000);
            };

            // Fungsi gagal
            const showError = () => {
                button.innerHTML = '<i class="bi bi-exclamation-triangle text-red-500"></i>';
                setTimeout(() => {
                    button.innerHTML = '<i class="bi bi-clipboard"></i>';
                }, 2000);
            };

            // Strategi Copy: Cek Modern Clipboard API (Khusus HTTPS)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text)
                    .then(showSuccess)
                    .catch(() => {
                        // Jika gagal, coba cara lama (fallback)
                        execFallbackCopy(text, showSuccess, showError);
                    });
            } else {
                // Gunakan fallback jika tidak di lingkungan secure (HTTP/IP Lokal)
                execFallbackCopy(text, showSuccess, showError);
            }
        });
    });

    // Helper: Cara lama menggunakan textarea (bekerja di HTTP)
    function execFallbackCopy(text, successCb, errorCb) {
        try {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            // Pastikan textarea tidak terlihat tapi tetap di dokumen
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            textArea.style.top = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            
            if (successful) successCb(); else errorCb();
        } catch (err) {
            errorCb();
        }
    }
});
</script>

<style>
/* Style Drawer Mobile agar tidak tabrakan dengan Desktop Sticky */
@media (max-width: 1023px) {
    #aside-container {
        position: fixed !important;
        inset: 0;
        z-index: 10005;
        pointer-events: none;
        display: none; /* Default hidden di mobile */
    }
    
    #aside-container.active, #aside-container:has(.active) {
        display: block;
    }

    #toc-box {
        position: fixed;
        bottom: -100%;
        left: 0;
        width: 100%;
        background: white;
        z-index: 10010;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 2rem 2rem 0 0;
        pointer-events: auto;
    }

    #toc-box.active {
        bottom: 0;
    }

    #drawer-overlay.active {
        display: block !important;
        opacity: 1 !important;
    }
}

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- Resources --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
@endsection