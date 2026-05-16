@extends('layouts.modern')

@section('content')
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- HERO -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-[2.5rem] p-8 lg:p-16 mb-12 shadow-2xl">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-6 uppercase tracking-wider">
                    Rekayasa Sistem Komputer
                </span>
                <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight mb-6">
                    Laboratorium Pemrograman & Komputasi
                </h1>
                <p class="text-lg text-zinc-400 mb-8 leading-relaxed font-medium">
                    Solusi terintegrasi untuk <br>
                    <span class="inline-block font-mono bg-blue-500/10 px-3 py-1 rounded border-l-4 border-blue-500 text-blue-400 mt-2">
                        <span id="typed-text"></span>
                    </span><br>
                    <span class="block mt-2">yang efisien.</span>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#layanan" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold rounded-full transition-all shadow-lg shadow-blue-600/20">
                        Akses Pelayanan
                    </a>
                    <a href="/sop" class="px-8 py-4 bg-transparent hover:bg-zinc-800 text-white text-center font-bold rounded-full transition-all border border-zinc-700">
                        SOP Laboratorium
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <div class="relative h-[300px] lg:h-[450px] w-full rounded-3xl overflow-hidden border-4 border-zinc-800 shadow-2xl bg-zinc-800">
                    <!-- Carousel Placeholder Logic -->
                    <img src="{{ asset('images/hero-lab.jpeg') }}" class="w-full h-full object-cover" alt="Lab">
                </div>
            </div>
        </div>
    </div>

    <!-- SERVICES -->
    <div id="layanan" class="bg-zinc-900/50 border border-zinc-800 rounded-[3rem] p-8 lg:p-12 mb-16 shadow-inner">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-3">Layanan Utama</h2>
            <div class="w-12 h-1.5 bg-blue-600 mx-auto rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card Peminjaman -->
            <div class="bg-zinc-800/40 p-8 rounded-3xl border border-zinc-700 hover:border-blue-500 transition-all group shadow-sm hover:shadow-blue-500/10 flex flex-col">
                <div class="flex items-center mb-6">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg shadow-blue-600/20">
                        <i class="bi bi-cpu text-2xl text-white"></i>
                    </div>
                    <h5 class="text-xl font-bold text-white m-0">Peminjaman Alat</h5>
                </div>
                <p class="text-zinc-400 text-sm mb-6 leading-relaxed flex-grow">Katalog hardware & kit robotika yang dapat digunakan untuk riset.</p>
                <a href="#katalog" class="text-blue-400 font-bold text-sm inline-flex items-center group-hover:gap-2 transition-all">
                    Cek Alat <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
            <!-- Card Bebas Lab -->
            <div class="bg-zinc-800/40 p-8 rounded-3xl border border-zinc-700 hover:border-cyan-500 transition-all group shadow-sm hover:shadow-cyan-500/10 flex flex-col">
                <div class="flex items-center mb-6">
                    <div class="w-14 h-14 bg-cyan-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg shadow-cyan-500/20">
                        <i class="bi bi-file-earmark-check text-2xl text-white"></i>
                    </div>
                    <h5 class="text-xl font-bold text-white m-0">Surat Bebas Lab</h5>
                </div>
                <p class="text-zinc-400 text-sm mb-6 leading-relaxed flex-grow">Pengurusan surat keterangan bebas laboratorium untuk yudisium.</p>
                <a href="{{ route('bebas-lab.form') }}" class="text-cyan-400 font-bold text-sm inline-flex items-center group-hover:gap-2 transition-all">
                    Ajukan <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
            <!-- Card SOP -->
            <div class="bg-zinc-800/40 p-8 rounded-3xl border border-zinc-700 hover:border-emerald-500 transition-all group shadow-sm hover:shadow-emerald-500/10 flex flex-col">
                <div class="flex items-center mb-6">
                    <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg shadow-emerald-500/20">
                        <i class="bi bi-diagram-3 text-2xl text-white"></i>
                    </div>
                    <h5 class="text-xl font-bold text-white m-0">Visualisasi Alur</h5>
                </div>
                <p class="text-zinc-400 text-sm mb-6 leading-relaxed flex-grow">Lihat prosedur kerja dan standar operasional laboratorium.</p>
                <a href="/sop" class="text-emerald-400 font-bold text-sm inline-flex items-center group-hover:gap-2 transition-all">
                    Lihat SOP <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- KATALOG -->
    <div id="katalog">
        <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-black text-white mb-0">Katalog Alat</h2>
                <p class="text-zinc-500 text-sm mt-1 uppercase tracking-widest">Aset Lab Pemrograman & Komputasi - UNTAN</p>
            </div>
            <a href="{{ url('/katalog') }}" class="text-blue-400 font-bold hover:text-blue-300 transition-colors py-2">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($inventaris->take(4) as $item)
                <div class="bg-zinc-900 border border-zinc-800 rounded-[2rem] overflow-hidden hover:shadow-2xl transition-all flex flex-col group hover:border-zinc-700 shadow-xl">
                    <div class="h-44 bg-zinc-950 flex items-center justify-center overflow-hidden border-b border-zinc-800">
                        @if($item->foto_barang)
                            <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="{{ $item->nama_aset }}">
                        @else
                            <i class="bi bi-image text-zinc-800 text-5xl"></i>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <h6 class="text-white font-bold truncate mb-1">{{ $item->nama_aset }}</h6>
                        <code class="text-[10px] text-zinc-500 mb-4 block">{{ $item->kode_barang }}</code>
                        
                        <div class="mb-4">
                            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold uppercase tracking-tighter">Bisa Dipinjam</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-zinc-500/10 text-zinc-500 border border-zinc-500/20 text-[10px] font-bold uppercase tracking-tighter">Gunakan di Lab</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @auth
                                @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                    <button onclick="openModal('{{ $item->id }}')" 
                                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-bold transition-all shadow-lg shadow-blue-600/10 {{ $item->jumlah_stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item->jumlah_stok <= 0 ? 'disabled' : '' }}>
                                        Pinjam Alat
                                    </button>
                                @else
                                    <button class="w-full py-3 bg-zinc-800 text-zinc-500 rounded-2xl text-xs font-bold cursor-not-allowed" disabled>Hanya di Lab</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full py-3 bg-zinc-800 text-zinc-300 hover:bg-zinc-700 text-center rounded-2xl text-xs font-bold transition-all border border-zinc-700">Login</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Modal Handler -->
                @auth 
                    @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                        <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/90 backdrop-blur-md">
                            <div class="bg-zinc-900 border border-zinc-800 w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-2xl transition-all">
                                <div class="p-6 border-b border-zinc-800 flex justify-between items-center bg-zinc-900 text-white">
                                    <h3 class="font-bold">Form Peminjaman Alat</h3>
                                    <button onclick="closeModal('{{ $item->id }}')" class="text-zinc-500 hover:text-white transition-colors"><i class="bi bi-x-lg text-xl"></i></button>
                                </div>
                                <div class="p-8 text-white max-h-[75vh] overflow-y-auto">
                                    @include('peminjaman.partials.modal_pinjam', ['item' => $item])
                                </div>
                            </div>
                        </div>
                    @endif 
                @endauth

            @empty
                <div class="col-span-full py-20 text-center text-zinc-600 border border-dashed border-zinc-800 rounded-3xl uppercase tracking-widest text-sm font-bold">
                    Belum ada alat tersedia.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Manual Modal Logic for Tailwind
    function openModal(id) {
        const modal = document.getElementById('modal-' + id);
        if(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }
    function closeModal(id) {
        const modal = document.getElementById('modal-' + id);
        if(modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    }

    // Typed.js
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Typed !== 'undefined') {
            new Typed('#typed-text', {
                strings: ['peminjaman_alat', 'riset_penelitian', 'layanan_administrasi'],
                typeSpeed: 40,
                backSpeed: 20,
                backDelay: 2000,
                loop: true,
                cursorChar: '█',
            });
        }
    });

    // Close on backdrop click
    window.onclick = function(event) {
        if (event.target.id.startsWith('modal-')) {
            closeModal(event.target.id.replace('modal-', ''));
        }
    }
</script>
@endsection