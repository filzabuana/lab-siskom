<teleport to="body">
    <div v-if="showDeleteModal" 
         class="fixed inset-0 z-[999] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop Blur --}}
            <div @click="showDeleteModal = false" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
                
                <div class="bg-white dark:bg-slate-800 p-8">
                    <div class="flex flex-col items-center">
                        {{-- Icon Warning --}}
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 dark:bg-red-900/20 text-red-600 mb-6">
                            <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                        </div>
                        
                        <div class="text-center">
                            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tight mb-4" id="modal-title">
                                Konfirmasi Hapus
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 italic leading-relaxed">
                                Apakah Anda yakin ingin menghapus aset <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">"{{ $item->nama_aset }}"</span>? 
                                Tindakan ini bersifat permanen dan data akan hilang dari database laboratorium.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-slate-50 dark:bg-slate-900/50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                    <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-red-200 dark:shadow-none px-8 py-3 bg-red-600 text-[10px] font-black uppercase tracking-widest text-white hover:bg-red-700 transition-all hover:-translate-y-0.5 italic">
                            Ya, Hapus Aset
                        </button>
                    </form>
                    
                    <button @click="showDeleteModal = false" 
                            type="button" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-xl px-8 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors italic">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</teleport>