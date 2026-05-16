@extends('layouts.modern')

@section('content')
<div class="container py-8 px-4 md:py-12">
    <div class="max-w-5xl mx-auto">
        
        {{-- Header Page --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.inventaris.index') }}" 
               class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic">Edit Data Aset</h2>
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Pembaruan Inventaris Lab</p>
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
            <div class="p-6 md:p-10">
                <form action="{{ route('admin.inventaris.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- Section 1: Identitas Dasar --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Kode Barang</label>
                            <input type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-500 font-mono text-sm" value="{{ $item->kode_barang }}" readonly>
                            <p class="text-[9px] text-slate-400 mt-2 ml-1 leading-relaxed">* Kode aset bersifat permanen dan tidak dapat diubah.</p>
                        </div>
                        <div class="md:col-span-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Nama Aset / Perangkat</label>
                            <input type="text" name="nama_aset" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all outline-none" value="{{ old('nama_aset', $item->nama_aset) }}" required px-5>
                        </div>
                    </div>

                    {{-- Section 2: Detail Teknis --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Kategori</label>
                            <select name="kategori" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none appearance-none" required>
                                @foreach(['Mikrokontroler', 'Sensor', 'Komputer', 'Trainer Kit', 'Alat Ukur', 'Lainnya'] as $cat)
                                    <option value="{{ $cat }}" {{ $item->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Merk / Brand</label>
                            <input type="text" name="merk" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none" value="{{ old('merk', $item->merk) }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">NUP (Nomor Urut Pusat)</label>
                            <input type="text" name="nup" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none" value="{{ old('nup', $item->nup) }}">
                        </div>
                    </div>

                    {{-- Section 3: Logistik & Stok --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-slate-50/50 dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Ruangan</label>
                            <input type="text" name="ruangan" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-blue-600" value="{{ old('ruangan', $item->ruangan) }}" required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Tahun</label>
                            <input type="number" name="tahun_perolehan" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-blue-600" value="{{ old('tahun_perolehan', $item->tahun_perolehan) }}" required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2 italic">Stok Baik</label>
                            <input type="number" name="jumlah_stok" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-900 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 font-bold text-emerald-700 dark:text-emerald-400" min="0" value="{{ old('jumlah_stok', $item->jumlah_stok) }}" required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-red-600 uppercase tracking-widest mb-2 italic">Jumlah Rusak</label>
                            <input type="number" name="jumlah_rusak" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-900 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500/20 font-bold text-red-700 dark:text-red-400" min="0" value="{{ old('jumlah_rusak', $item->jumlah_rusak) }}" required>
                        </div>
                    </div>

                    {{-- Section 4: Kebijakan & Kondisi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Status Kondisi Umum</label>
                            <select name="kondisi" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-white outline-none focus:border-blue-600 appearance-none" required>
                                <option value="Baik" {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>🟢 Baik (Siap Pakai)</option>
                                <option value="Rusak Ringan" {{ $item->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>🟡 Rusak Ringan</option>
                                <option value="Rusak Berat" {{ $item->kondisi == 'Rusak Berat' ? 'selected' : '' }}>🔴 Rusak Berat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2 ml-1 italic">Kebijakan Peminjaman</label>
                            <select name="tipe_peminjaman" class="w-full px-5 py-3.5 bg-white dark:bg-slate-900 border border-blue-100 dark:border-blue-900 rounded-2xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-blue-600/20 appearance-none font-bold" required>
                                <option value="Hanya di Lab" {{ $item->tipe_peminjaman == 'Hanya di Lab' ? 'selected' : '' }}>Statis (Hanya di Lab)</option>
                                <option value="Bisa Dipinjam" {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'selected' : '' }}>Mobile (Bisa Dipinjam)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Section 5: Deskripsi --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Deskripsi & Fungsi Alat</label>
                        <textarea name="deskripsi" rows="4" class="w-full px-5 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-[2rem] text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-600/20 outline-none resize-none" placeholder="Jelaskan spesifikasi singkat atau kegunaan alat ini...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                    </div>

                    {{-- Section 6: Media --}}
                    <div class="p-6 bg-slate-50 dark:bg-slate-900/80 rounded-[2rem] border border-dashed border-slate-300 dark:border-slate-700">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic text-center">Foto Perangkat</label>
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            @if($item->foto_barang)
                                <div class="relative group">
                                    <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" class="w-32 h-32 object-cover rounded-2xl border-4 border-white dark:border-slate-800 shadow-md">
                                    <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-[8px] font-black px-2 py-1 rounded-full uppercase tracking-tighter shadow-lg">Current</div>
                                </div>
                            @endif
                            <div class="flex-grow w-full">
                                <input type="file" name="foto_barang" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                <p class="text-[9px] text-slate-400 mt-2 italic">* Kosongkan jika tidak ingin memperbarui foto utama.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex flex-col md:flex-row items-center justify-end gap-4 pt-6 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('admin.inventaris.index') }}" class="w-full md:w-auto text-center px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-600 transition-colors italic">Batalkan</a>
                        <button type="submit" class="w-full md:w-auto px-10 py-4 bg-slate-900 dark:bg-blue-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl md:rounded-full shadow-xl shadow-slate-200 dark:shadow-none hover:-translate-y-1 active:scale-95 transition-all italic">
                            <i class="bi bi-cloud-arrow-up-fill mr-2"></i> Perbarui Data Aset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection