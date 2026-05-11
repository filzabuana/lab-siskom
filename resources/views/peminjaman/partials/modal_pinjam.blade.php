{{-- resources/views/peminjaman/partials/modal_pinjam.blade.php --}}
<teleport to="body">
    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="activeModal === {{ $item->id }}" 
             v-cloak 
             class="fixed inset-0 z-[9999] overflow-y-auto">
            
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <transition
                    appear
                    enter-active-class="transition duration-300 ease-out transform"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition duration-200 ease-in transform"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div v-if="activeModal === {{ $item->id }}" 
                         {{-- TAMBAHKAN: color-scheme-dark untuk memperbaiki icon kalender/input --}}
                         class="relative bg-white dark:bg-railway-card w-full max-w-lg shadow-2xl rounded-[2.5rem] overflow-hidden dark:color-scheme-dark"
                         @click.stop>
                        
                        <div class="p-8">
                            <div class="flex justify-between items-center mb-8 text-start">
                                <div>
                                    <h5 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter uppercase leading-none italic">
                                        Form Peminjaman
                                    </h5>
                                    <div class="w-10 h-1 bg-blue-600 rounded-full mt-2"></div>
                                </div>
                                <button type="button" @click="closeModal" class="p-2 hover:bg-slate-100 dark:hover:bg-railway-dark rounded-full transition-colors group">
                                    {{-- Beri warna lebih terang pada icon X di dark mode --}}
                                    <i class="bi bi-x-lg text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white"></i>
                                </button>
                            </div>

                            <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-6">
                                @csrf
                                {{-- Border & Background adjustment --}}
                                <div class="p-5 bg-slate-50 dark:bg-railway-dark/80 rounded-2xl border border-slate-100 dark:border-white/5 text-center">
                                    <h6 class="text-sm font-black text-slate-800 dark:text-white uppercase mb-1">{{ $item->nama_aset }}</h6>
                                    <span class="inline-flex px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-full uppercase italic">
                                        Stok: {{ $item->jumlah_stok }} Unit
                                    </span>
                                </div>

                                <input type="hidden" name="inventaris_id" value="{{ $item->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-start">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest px-1">Jumlah</label>
                                        {{-- Fokus pada border-white/10 agar terlihat di background gelap --}}
                                        <input type="number" name="jumlah_pinjam" 
                                            class="w-full px-4 py-3 bg-slate-100/50 dark:bg-railway-dark border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold dark:text-white focus:border-blue-500 focus:ring-0 outline-none" 
                                            min="1" max="{{ $item->jumlah_stok }}" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest px-1">Tgl Kembali</label>
                                        <input type="date" name="tgl_kembali_rencana" 
                                            class="w-full px-4 py-3 bg-slate-100/50 dark:bg-railway-dark border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold dark:text-white focus:border-blue-500 focus:ring-0 outline-none" 
                                            required>
                                    </div>
                                </div>

                                <div class="space-y-2 text-start">
                                    <label class="text-[11px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest px-1">Keperluan</label>
                                    <textarea name="keperluan" rows="3" 
                                        class="w-full px-4 py-3 bg-slate-100/50 dark:bg-railway-dark border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold dark:text-white focus:border-blue-500 focus:ring-0 outline-none" 
                                        required></textarea>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                                    <button type="button" @click="closeModal" 
                                        class="w-full sm:w-1/3 py-4 bg-slate-100 dark:bg-railway-dark/50 text-slate-600 dark:text-slate-300 border border-transparent dark:border-white/5 rounded-full font-black text-[11px] uppercase transition-colors hover:bg-slate-200 dark:hover:bg-railway-dark">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                        class="w-full sm:w-2/3 py-4 bg-blue-600 text-white rounded-full font-black text-[11px] uppercase shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95">
                                        Kirim <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </transition>
</teleport>