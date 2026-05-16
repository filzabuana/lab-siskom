@extends('layouts.modern')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Daftar Pengajuan Bebas Lab
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Verifikasi pengajuan mahasiswa dan cek status peminjaman alat secara real-time.
            </p>
        </div>
        <div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                <span class="relative flex h-2 w-2 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                {{ $data->count() }} Total Pengajuan
            </span>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" id="searchName" placeholder="Cari Nama atau NIM..." 
                class="block w-full pl-10 pr-3 py-2.5 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
        </div>

        <div>
            <select id="filterProdi" 
                class="block w-full px-3 py-2.5 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition text-slate-600 dark:text-slate-300">
                <option value="">Semua Program Studi</option>
                @foreach($data->pluck('prodi')->unique() as $prodi)
                    <option value="{{ $prodi }}">{{ $prodi }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select id="filterStatus" 
                class="block w-full px-3 py-2.5 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition text-slate-600 dark:text-slate-300">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Verifikasi</option>
                <option value="verified_email">Siap Cek Alat</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
    </div>

    {{-- Data Container --}}
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none">
        
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Prodi</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($data as $item)
                    @php
                        $c = [
                            'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800'],
                            'verified_email' => ['label' => 'Siap Cek Alat', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800'],
                            'disetujui' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800'],
                            'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800'],
                        ][$item->status] ?? ['label' => $item->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
                    @endphp
                    <tr class="item-element hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors" 
                        data-name="{{ strtolower($item->nama) }}" 
                        data-nim="{{ $item->nim }}" 
                        data-prodi="{{ $item->prodi }}" 
                        data-status="{{ $item->status }}">
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $item->nama }}</span>
                                <span class="text-xs text-slate-500 font-mono mt-0.5">{{ $item->nim }} • {{ $item->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-400">{{ $item->prodi }}</td>
                        <td class="px-6 py-5">
                            <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border {{ $c['class'] }}">
                                {{ $c['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($item->status == 'verified_email')
                                <button onclick="openModal('modalVerify{{ $item->id }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-blue-500/30">
                                    Proses Verifikasi
                                </button>
                            @else
                                <span class="text-xs font-medium text-slate-400 italic">No Action Needed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada pengajuan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y divide-slate-200 dark:divide-slate-800">
            @forelse($data as $item)
            @php
                $c = [
                    'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800'],
                    'verified_email' => ['label' => 'Siap Cek Alat', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800'],
                    'disetujui' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800'],
                    'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800'],
                ][$item->status] ?? ['label' => $item->status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
            @endphp
            <div class="item-element p-5 flex flex-col gap-4" 
                data-name="{{ strtolower($item->nama) }}" 
                data-nim="{{ $item->nim }}" 
                data-prodi="{{ $item->prodi }}" 
                data-status="{{ $item->status }}">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg text-slate-900 dark:text-white leading-tight">{{ $item->nama }}</span>
                        <span class="text-xs text-slate-500 font-mono">{{ $item->nim }}</span>
                    </div>
                    <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase rounded-full border {{ $c['class'] }}">
                        {{ $c['label'] }}
                    </span>
                </div>
                <div class="flex flex-col gap-1 text-sm text-slate-600 dark:text-slate-400">
                    <div class="flex items-center italic">
                        <svg class="w-4 h-4 mr-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $item->prodi }}
                    </div>
                </div>
                @if($item->status == 'verified_email')
                    <button onclick="openModal('modalVerify{{ $item->id }}')" class="w-full py-3 bg-blue-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-500/20 transition">
                        Proses Sekarang
                    </button>
                @endif
            </div>
            @empty
            <div class="p-10 text-center text-slate-500">Belum ada pengajuan masuk.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Modal Section (Tidak Berubah) --}}
@foreach($data as $item)
<div id="modalVerify{{ $item->id }}" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalVerify{{ $item->id }}')"></div>
        <div class="inline-block bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border dark:border-slate-800 z-10">
            <form action="{{ route('admin.bebas-lab.update', $item->id) }}" method="POST">
                @csrf
                <div class="px-6 py-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Proses Verifikasi</h3>
                    <p class="text-sm text-slate-500 mt-2">Mahasiswa: {{ $item->nama }}</p>
                    <div class="mt-6 space-y-4">
                        <select name="action" class="w-full bg-slate-50 dark:bg-slate-800 border dark:border-slate-700 rounded-xl px-4 py-3 dark:text-white outline-none" required onchange="toggleCatatan(this, {{ $item->id }})">
                            <option value="setujui">Setujui & Kirim Surat Bebas Lab</option>
                            <option value="tolak">Tolak (Perlu Perbaikan)</option>
                        </select>
                        <div id="catatanSection{{ $item->id }}" class="hidden">
                            <textarea name="catatan" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border dark:border-slate-700 rounded-xl px-4 py-3 dark:text-white" placeholder="Alasan penolakan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl">Simpan</button>
                    <button type="button" onclick="closeModal('modalVerify{{ $item->id }}')" class="px-6 py-2.5 bg-white dark:bg-slate-700 dark:text-white font-bold rounded-xl border">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchName');
        const prodiFilter = document.getElementById('filterProdi');
        const statusFilter = document.getElementById('filterStatus');
        
        function filterData() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedProdi = prodiFilter.value;
            const selectedStatus = statusFilter.value;
            
            // Mengambil semua elemen baik baris tabel maupun card mobile
            const items = document.querySelectorAll('.item-element');

            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const nim = item.getAttribute('data-nim') || '';
                const prodi = item.getAttribute('data-prodi') || '';
                const status = item.getAttribute('data-status') || '';

                const matchesSearch = name.includes(searchTerm) || nim.includes(searchTerm);
                const matchesProdi = selectedProdi === "" || prodi === selectedProdi;
                const matchesStatus = selectedStatus === "" || status === selectedStatus;

                if (matchesSearch && matchesProdi && matchesStatus) {
                    item.style.display = ""; // Tampilkan (flex atau table-row sesuai aslinya)
                } else {
                    item.style.display = "none"; // Sembunyikan
                }
            });
        }

        searchInput.addEventListener('input', filterData);
        prodiFilter.addEventListener('change', filterData);
        statusFilter.addEventListener('change', filterData);
    });

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    function toggleCatatan(select, id) {
        const section = document.getElementById('catatanSection' + id);
        section.classList.toggle('hidden', select.value !== 'tolak');
    }
</script>
@endsection