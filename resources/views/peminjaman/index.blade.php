@extends('layouts.modern')

@section('styles')
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" v-cloak>
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter">
                Lending Management
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">
                Lab. Pemrograman & Komputasi — FMIPA UNTAN
            </p>
        </div>
        @if(!Auth::user()->is_admin)
            <a href="{{ url('/katalog') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest italic transition-all shadow-lg shadow-blue-500/25 active:scale-95">
                <i class="bi bi-plus-lg mr-2"></i> New Request
            </a>
        @endif
    </div>

    @php
        $activePeminjaman = $peminjamans->filter(fn($item) => in_array($item->status, ['pending', 'disetujui']))->sortByDesc('created_at');
        $historyPeminjaman = $peminjamans->filter(fn($item) => in_array($item->status, ['selesai', 'ditolak']))->sortByDesc('updated_at');
    @endphp

    {{-- TABEL 1: ACTIVE / PENDING --}}
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-5 px-1">
            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                <i class="bi bi-hourglass-split text-lg"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase italic tracking-widest">
                Active & Pending Sessions
            </h3>
            <span class="bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black px-3 py-1 rounded-full border border-blue-200 dark:border-blue-500/20">
                {{ $activePeminjaman->count() }}
            </span>
        </div>

        <div class="bg-white dark:bg-railway-card rounded-[2rem] border border-slate-100 dark:border-railway-border shadow-xl overflow-hidden">
            <div class="overflow-x-auto md:overflow-visible text-slate-900 dark:text-white">
                <table class="w-full border-collapse text-slate-900 dark:text-white">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-slate-50/50 dark:bg-white/[0.02] border-b border-slate-100 dark:border-railway-border text-slate-900 dark:text-white">
                            <th class="px-6 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Borrower & Asset</th>
                            <th class="px-6 py-5 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Due Date</th>
                            <th class="px-6 py-5 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Status</th>
                            <th class="px-6 py-5 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-railway-border text-slate-900 dark:text-white">
                        @forelse($activePeminjaman as $pinjam)
                        <tr class="flex flex-col md:table-row hover:bg-slate-50 dark:hover:bg-white/[0.01] transition-colors p-6 md:p-0 border-b md:border-b-0 border-slate-100 dark:border-railway-border">
                            <td class="md:px-6 md:py-5 flex flex-col md:table-cell">
                                <div class="text-xs font-black uppercase italic tracking-tight leading-tight">
                                    {{ $pinjam->user->name }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-tighter">{{ $pinjam->inventaris->nama_aset }}</span>
                                    <span class="text-[9px] font-mono text-slate-400">[{{ $pinjam->jumlah_pinjam }} Units]</span>
                                </div>
                            </td>
                            <td class="mt-4 md:mt-0 md:px-6 md:py-5 md:text-center flex items-center justify-between md:table-cell">
                                <span class="md:hidden text-[8px] font-black text-slate-400 uppercase italic">Return Date:</span>
                                <span class="text-[10px] font-black italic">
                                    {{ \Carbon\Carbon::parse($pinjam->tgl_kembali_rencana)->format('d M Y') }}
                                </span>
                            </td>
                            <td class="mt-2 md:mt-0 md:px-6 md:py-5 md:text-center flex items-center justify-between md:table-cell">
                                <span class="md:hidden text-[8px] font-black text-slate-400 uppercase italic">Current Status:</span>
                                @if($pinjam->status == 'pending')
                                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[8px] font-black uppercase tracking-widest border border-amber-200 dark:border-amber-500/20 rounded-lg italic">Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[8px] font-black uppercase tracking-widest border border-blue-200 dark:border-blue-500/20 rounded-lg italic">In Use</span>
                                @endif
                            </td>
                            <td class="mt-6 md:mt-0 md:px-6 md:py-5 flex md:table-cell">
                                <div class="flex md:justify-end gap-3 w-full">
                                    @if(Auth::user()->is_admin)
                                        @if($pinjam->status == 'pending')
                                            <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST" class="flex-1 md:flex-initial">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="disetujui">
                                                <button type="submit" class="w-full md:w-10 md:h-10 py-3 md:py-0 flex items-center justify-center bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                                                    <i class="bi bi-check-lg text-lg"></i>
                                                    <span class="md:hidden ml-2 text-[10px] font-black uppercase">Approve</span>
                                                </button>
                                            </form>
                                            {{-- Perbaikan parameter: kirim update_url yang presisi --}}
                                            <button type="button" @click.stop="openReject({ 
                                                id: {{ $pinjam->id }}, 
                                                name: '{{ $pinjam->user->name }}',
                                                update_url: '{{ route('admin.peminjaman.update', $pinjam->id) }}' 
                                            })"
                                                class="flex-1 md:flex-initial md:w-10 md:h-10 py-3 md:py-0 flex items-center justify-center border-2 border-red-100 dark:border-red-500/20 text-red-500 rounded-xl active:scale-95 transition-all">
                                                <i class="bi bi-x-lg text-sm"></i>
                                                <span class="md:hidden ml-2 text-[10px] font-black uppercase">Reject</span>
                                            </button>
                                        @elseif($pinjam->status == 'disetujui')
                                            <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST" class="w-full md:w-auto">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="w-full px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-md active:scale-95 transition-all italic">
                                                    Mark as Returned
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest italic leading-loose">No active requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TABEL 2: HISTORY --}}
    <div class="mt-16">
        <div class="flex items-center gap-3 mb-5 px-1 opacity-80">
            <div class="w-10 h-10 bg-slate-400 dark:bg-slate-700 rounded-xl flex items-center justify-center text-white">
                <i class="bi bi-clock-history text-lg"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase italic tracking-widest">
                Log History
            </h3>
        </div>

        <div class="bg-white dark:bg-railway-card rounded-[2rem] border border-slate-100 dark:border-railway-border shadow-lg overflow-hidden opacity-90 text-slate-900 dark:text-white">
            <div class="overflow-x-auto md:overflow-visible">
                <table class="w-full border-collapse">
                    <thead class="hidden md:table-header-group text-slate-900 dark:text-white">
                        <tr class="bg-slate-50/50 dark:bg-white/[0.01] border-b border-slate-100 dark:border-railway-border">
                            <th class="px-6 py-4 text-left text-[8px] font-black text-slate-400 uppercase tracking-widest">Entity</th>
                            <th class="px-6 py-4 text-center text-[8px] font-black text-slate-400 uppercase tracking-widest">Final Status</th>
                            <th class="px-6 py-4 text-right text-[8px] font-black text-slate-400 uppercase tracking-widest">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-railway-border text-slate-900 dark:text-white">
                        @foreach($historyPeminjaman as $pinjam)
                        <tr class="flex flex-col md:table-row hover:bg-slate-50/50 dark:hover:bg-white/[0.01] p-5 md:p-0">
                            <td class="md:px-6 md:py-4 flex flex-col md:table-cell">
                                <div class="text-[10px] font-bold">{{ $pinjam->user->name }}</div>
                                <div class="text-[9px] font-black text-blue-500/80 italic tracking-tight uppercase">{{ $pinjam->inventaris->nama_aset }}</div>
                            </td>
                            <td class="mt-2 md:mt-0 md:px-6 md:py-4 md:text-center flex items-center justify-between md:table-cell">
                                <span class="md:hidden text-[7px] font-black text-slate-400 uppercase tracking-widest">Outcome:</span>
                                @if($pinjam->status == 'selesai')
                                    <span class="text-[8px] font-black uppercase text-emerald-500 italic bg-emerald-500/5 px-2 py-0.5 rounded">Returned</span>
                                @else
                                    <span class="text-[8px] font-black uppercase text-red-400 italic bg-red-400/5 px-2 py-0.5 rounded">Rejected</span>
                                @endif
                            </td>
                            <td class="mt-3 md:mt-0 md:px-6 md:py-4 flex md:table-cell justify-end">
                                <div class="w-full md:w-auto flex md:justify-end items-center">
                                    @if($pinjam->status == 'ditolak')
                                        <button type="button" @click.stop="openNote({ note: '{{ $pinjam->catatan }}', name: '{{ $pinjam->user->name }}' })"
                                            class="w-full md:w-auto text-center py-2 md:py-0 text-[9px] font-black text-slate-400 hover:text-blue-500 uppercase tracking-widest transition-colors underline decoration-dotted">
                                            <i class="bi bi-chat-left-dots md:hidden mr-1"></i> View Note
                                        </button>
                                    @else
                                        <div class="flex items-center gap-2 text-[9px] font-bold text-slate-300 dark:text-slate-700 uppercase tracking-tighter italic">
                                            <span class="md:hidden">Completed</span>
                                            <i class="bi bi-check2-circle text-emerald-500/30 text-base md:text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL 1: REJECT --}}
    <transition name="fade">
        <div v-if="showRejectModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="showRejectModal = false">
            <transition name="zoom" appear>
                <div v-if="showRejectModal" class="bg-white dark:bg-railway-card w-full max-w-md rounded-[2.5rem] shadow-2xl border border-white/20 overflow-hidden text-slate-900 dark:text-white">
                    <div class="px-8 py-6 bg-red-600 text-white flex justify-between items-center">
                        <h5 class="font-black uppercase italic tracking-widest text-sm">Decline Request</h5>
                        <button @click="showRejectModal = false" class="hover:rotate-90 transition-transform"><i class="bi bi-x-lg"></i></button>
                    </div>

                    {{-- FIX: Action form menggunakan update_url yang dikirim dari tombol --}}
                    <form v-if="selectedPinjam" :action="selectedPinjam.update_url" method="POST">
                        @csrf 
                        @method('PATCH')
                        
                        <div class="p-8">
                            <input type="hidden" name="status" value="ditolak">
                            <p class="text-sm text-slate-500 mb-6 italic leading-relaxed text-slate-900 dark:text-white">Tolak peminjaman untuk <br><span class="font-black text-base text-slate-900 dark:text-white">@{{ selectedPinjam.name }}</span>?</p>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic">Reason / Catatan</label>
                                <textarea name="catatan" class="w-full bg-slate-50 dark:bg-railway-dark border border-slate-100 dark:border-railway-border rounded-2xl p-4 text-sm focus:ring-2 focus:ring-red-500 transition-all outline-none text-slate-900 dark:text-white" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="px-8 pb-8 flex gap-3">
                            <button type="button" @click="showRejectModal = false" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 italic">Cancel</button>
                            <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-500/20 active:scale-95 transition-all italic">Confirm Reject</button>
                        </div>
                    </form>
                </div>
            </transition>
        </div>
    </transition>

    {{-- MODAL 2: VIEW NOTE --}}
    <transition name="fade">
        <div v-if="showNoteModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="showNoteModal = false">
            <transition name="zoom" appear>
                <div v-if="showNoteModal && selectedPinjam" class="bg-white dark:bg-railway-card w-full max-w-md rounded-[2.5rem] shadow-2xl border border-white/20 overflow-hidden text-slate-900 dark:text-white">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-slate-100 dark:bg-railway-dark rounded-lg flex items-center justify-center text-slate-400">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-black uppercase italic tracking-[0.2em] text-slate-900 dark:text-white">Admin Note</h5>
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mt-0.5">@{{ selectedPinjam.name }}</p>
                            </div>
                        </div>
                        <div class="p-5 bg-slate-50 dark:bg-railway-dark rounded-2xl border border-slate-100 dark:border-railway-border text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic font-medium">
                            "@{{ selectedPinjam.note || 'No comments provided.' }}"
                        </div>
                        <button @click="showNoteModal = false" class="w-full mt-6 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest italic active:scale-95 transition-all">Close Window</button>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</div>
@endsection