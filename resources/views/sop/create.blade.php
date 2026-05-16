@extends('layouts.modern')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] transition-colors duration-500 py-10">
    <div class="max-w-5xl mx-auto px-4">
        
        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Tambah <span class="text-blue-600">SOP Baru</span></h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gunakan editor flowchart interaktif untuk publikasi prosedur.</p>
            </div>
            <a href="{{ route('sop.index') }}" class="group flex items-center text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all">
                <i class="bi bi-arrow-left me-2 transition-transform group-hover:-translate-x-1"></i> Kembali
            </a>
        </div>

        {{-- Error Validation Feedback --}}
        @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-400 text-sm">
            <p class="font-black uppercase tracking-widest mb-2 italic">Periksa kembali inputan Anda:</p>
            <ul class="list-disc list-inside font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.sop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="mainSopForm">
            @csrf

            {{-- 1. Informasi Dasar --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Judul Dokumen</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required
                               placeholder="SOP Penggunaan Laboratorium Komputasi"
                               class="w-full px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Kategori</label>
                        <select name="kategori" id="kategoriSelect" required
                                 class="w-full px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Layanan" {{ old('kategori') == 'Layanan' ? 'selected' : '' }}>Layanan</option>
                            <option value="Teknis" {{ old('kategori') == 'Teknis' ? 'selected' : '' }}>Teknis</option>
                            <option value="Pemeliharaan dan Kalibrasi" {{ old('kategori') == 'Pemeliharaan dan Kalibrasi' ? 'selected' : '' }}>Pemeliharaan dan Kalibrasi</option>
                            <option value="Keselamatan dan Kesehatan Kerja" {{ old('kategori') == 'Keselamatan dan Kesehatan Kerja' ? 'selected' : '' }}>Keselamatan dan Kesehatan Kerja</option>
                            <option value="Manajemen data dan aset digital" {{ old('kategori') == 'Manajemen data dan aset digital' ? 'selected' : '' }}>Manajemen data dan aset digital</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya...</option>
                        </select>
                    </div>

                    <div id="kategoriLainnyaWrapper" class="{{ old('kategori') == 'Lainnya' ? '' : 'hidden' }} md:col-span-2 space-y-2">
                        <label class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest ml-1">Kategori Baru</label>
                        <input type="text" name="kategori_input_manual" value="{{ old('kategori_input_manual') }}" placeholder="Sebutkan kategori..."
                               class="w-full px-5 py-3 rounded-2xl bg-emerald-50/30 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800 text-slate-900 dark:text-white outline-none">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Deskripsi Ringkas</label>
                        <textarea name="deskripsi" rows="3" required
                                  placeholder="Ringkasan tujuan SOP..."
                                  class="w-full px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none leading-relaxed">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Visual Flowchart Tabs Editor --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm transition-all">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <div>
                        <h5 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="bi bi-diagram-3 text-blue-600"></i>
                            Visual SOP Designer
                        </h5>
                        <p class="text-xs text-slate-500 mt-1">Buat satu atau beberapa alur prosedur dalam bentuk tab.</p>
                    </div>
                </div>
                
                <div class="relative bg-slate-50 dark:bg-[#0f172a]" style="min-height: 700px;">
                    {{-- Komponen Vue --}}
                    <flowchart-editor-tabs></flowchart-editor-tabs>
                    
                    {{-- Input Hidden Terpusat --}}
                    <input type="hidden" name="flowchart_data" id="flowchart_master_input">
                </div>
            </div>

            {{-- 3. File PDF --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <i class="bi bi-file-earmark-pdf text-xl"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-900 dark:text-white">Lampiran Dokumen (PDF)</h5>
                        <p class="text-xs text-slate-500">Unggah dokumen resmi Laboratorium FMIPA UNTAN (Max 2MB).</p>
                    </div>
                </div>
                
                <input type="file" name="file_pdf" accept=".pdf" required
                       class="block w-full text-sm text-slate-500 dark:text-slate-400
                              file:mr-4 file:py-3 file:px-6
                              file:rounded-2xl file:border-0
                              file:bg-slate-100 dark:file:bg-slate-800
                              hover:file:bg-blue-600 hover:file:text-white transition-all cursor-pointer">
            </div>

            {{-- Footer Action --}}
            <div class="pt-6 flex flex-col md:flex-row items-center gap-6">
                <button type="submit" class="w-full md:w-auto px-12 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-500/25 hover:-translate-y-1 italic uppercase tracking-widest text-[11px]">
                    <i class="bi bi-cloud-check-fill me-2"></i> Publikasikan SOP Baru
                </button>
                <a href="{{ route('sop.index') }}" class="text-[11px] uppercase tracking-widest font-black text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    Batalkan & Kembali
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    // Logic Kategori Lainnya
    const kategoriSelect = document.getElementById('kategoriSelect');
    const kategoriWrapper = document.getElementById('kategoriLainnyaWrapper');

    if(kategoriSelect) {
        kategoriSelect.addEventListener('change', function() {
            kategoriWrapper.classList.toggle('hidden', this.value !== 'Lainnya');
        });
    }
</script>

<style>
    .dark ::-webkit-scrollbar { width: 8px; height: 8px; }
    .dark ::-webkit-scrollbar-track { background: #0f172a; }
    .dark ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>

@endsection