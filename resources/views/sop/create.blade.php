@extends('layouts.modern')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] transition-colors duration-500 py-10">
    <div class="max-w-5xl mx-auto px-4">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Tambah <span class="text-blue-600">SOP Baru</span></h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Lengkapi formulir di bawah untuk mempublikasikan prosedur laboratorium.</p>
            </div>
            <a href="{{ route('sop.index') }}" class="group flex items-center text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all">
                <i class="bi bi-arrow-left me-2 transition-transform group-hover:-translate-x-1"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-400 text-sm">
            <ul class="list-disc list-inside font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('sop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none p-8 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Judul Dokumen</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required
                               placeholder="Contoh: SOP Peminjaman Alat Laboratorium"
                               class="w-full px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Kategori</label>
                        <select name="kategori" id="kategoriSelect" required
                                class="w-full px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none appearance-none">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Layanan">1. Layanan</option>
                            <option value="Teknis">2. Teknis</option>
                            <option value="Pemeliharaan dan Kalibrasi">3. Pemeliharaan dan Kalibrasi</option>
                            <option value="Keselamatan dan Kesehatan Kerja">4. Keselamatan dan Kesehatan Kerja</option>
                            <option value="Manajemen Mutu dan Administrasi">5. Manajemen Mutu dan Administrasi</option>
                            <option value="Manajemen data dan aset digital">6. Manajemen data dan aset digital</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                    </div>

                    <div id="kategoriLainnyaWrapper" class="hidden md:col-span-2 space-y-2">
                        <label class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest ml-1">Tulis Kategori Baru</label>
                        <input type="text" name="kategori_input_manual" placeholder="Masukkan nama kategori baru"
                               class="w-full px-5 py-3 rounded-2xl bg-emerald-50/30 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Deskripsi Ringkas</label>
                        <textarea name="deskripsi" rows="3" required
                                  placeholder="Jelaskan secara singkat tujuan dan ruang lingkup SOP ini..."
                                  class="w-full px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none leading-relaxed">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 shadow-sm transition-all">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <i class="bi bi-cloud-arrow-up text-xl"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-900 dark:text-white">Dokumen PDF</h5>
                        <p class="text-xs text-slate-500">Maksimal ukuran file 2MB.</p>
                    </div>
                </div>
                
                <input type="file" name="file_pdf" accept=".pdf" required
                       class="block w-full text-sm text-slate-500 dark:text-slate-400
                              file:mr-4 file:py-3 file:px-6
                              file:rounded-2xl file:border-0
                              file:text-xs file:font-bold
                              file:bg-slate-100 dark:file:bg-slate-800
                              file:text-slate-700 dark:file:text-slate-300
                              hover:file:bg-blue-600 hover:file:text-white transition-all cursor-pointer">
            </div>

            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h5 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="bi bi-diagram-3-fill text-blue-600"></i>
                            Alur Prosedur Visual
                        </h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Gunakan sintaks Mermaid.js untuk membuat diagram alir.</p>
                    </div>
                    <button type="button" onclick="tambahAlur()" 
                            class="inline-flex items-center px-5 py-2.5 bg-blue-600/10 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400 font-bold text-xs rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                        <i class="bi bi-plus-lg me-2"></i> Tambah Bagian Alur
                    </button>
                </div>

                <div id="container-alur" class="space-y-4">
                    <div class="alur-item group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm transition-all animate-fade-in">
                        <div class="flex justify-between items-center mb-4">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">Bagian #1</span>
                            <span class="text-[10px] text-slate-400 italic">Wajib diisi minimal satu</span>
                        </div>
                        <div class="space-y-4">
                            <input type="text" name="alur_judul[]" required placeholder="Judul Bagian (Contoh: Tahap Pengajuan)"
                                   class="w-full px-5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm outline-none focus:border-blue-500">
                            <textarea name="alur_kode[]" rows="5" required placeholder="graph TD&#10;  A[Mulai] --> B{Setuju?}"
                                      class="w-full p-5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-xs border-none focus:ring-2 focus:ring-blue-500 transition-all shadow-inner"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-10 flex flex-col md:flex-row items-center gap-4">
                <button type="submit" class="w-full md:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-500/25 hover:-translate-y-1 active:scale-95">
                    <i class="bi bi-check2-circle me-2"></i> Simpan SOP Lengkap
                </button>
                <a href="{{ route('sop.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    Batal dan Kembali
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    // Logika Kategori Lainnya
    document.getElementById('kategoriSelect').addEventListener('change', function() {
        const wrapper = document.getElementById('kategoriLainnyaWrapper');
        if (this.value === 'Lainnya') {
            wrapper.classList.remove('hidden');
            wrapper.classList.add('animate-fade-in');
        } else {
            wrapper.classList.add('hidden');
        }
    });

    // Logika Tambah Alur Dinamis
    let count = 1;
    function tambahAlur() {
        count++;
        const container = document.getElementById('container-alur');
        const html = `
            <div class="alur-item bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm transition-all animate-fade-in">
                <div class="flex justify-between items-center mb-4">
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">Bagian #${count}</span>
                    <button type="button" onclick="hapusAlur(this)" class="text-red-400 hover:text-red-600 transition-colors text-sm font-bold flex items-center gap-1">
                        <i class="bi bi-trash3"></i> Hapus
                    </button>
                </div>
                <div class="space-y-4">
                    <input type="text" name="alur_judul[]" required placeholder="Judul Bagian"
                           class="w-full px-5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm outline-none focus:border-blue-500">
                    <textarea name="alur_kode[]" rows="5" required placeholder="Ketik kode Mermaid..."
                              class="w-full p-5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-xs border-none focus:ring-2 focus:ring-blue-500 transition-all shadow-inner"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function hapusAlur(btn) {
        btn.closest('.alur-item').classList.add('opacity-0', 'scale-95');
        setTimeout(() => btn.closest('.alur-item').remove(), 300);
    }
</script>

<style>
    /* Animasi Halus */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }

    /* Custom Focus Ring */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* Style scrollbar untuk editor mermaid */
    textarea::-webkit-scrollbar { width: 5px; }
    textarea::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
</style>
@endsection