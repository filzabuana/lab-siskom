@extends('layouts.app')

@section('content')
<div class="container py-3 py-md-4">
    {{-- Header --}}
    <div class="row mb-3 mb-md-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold mb-0 fs-4 fs-md-2 text-body">Kelola Peminjaman Alat</h2>
                <p class="text-body-secondary small mb-0">Lab. Pemrograman & Komputasi - Untan</p>
            </div>
            @if(!Auth::user()->is_admin)
                <a href="{{ url('/katalog') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Pinjam Alat Baru
                </a>
            @endif
        </div>
    </div>

    @php
        $activePeminjaman = $peminjamans->filter(function($item) {
            return in_array($item->status, ['pending', 'disetujui']);
        })->sortByDesc('created_at');

        $historyPeminjaman = $peminjamans->filter(function($item) {
            return in_array($item->status, ['selesai', 'ditolak']);
        })->sortByDesc('updated_at');
    @endphp

    {{-- TABEL 1: ACTIVE --}}
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary p-2 rounded-3 me-3 d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                <i class="bi bi-hourglass-split fs-5"></i>
            </div>
            <h5 class="fw-bold mb-0 text-body">Sedang Berjalan / Menunggu</h5>
            <span class="badge bg-primary-subtle text-primary rounded-pill ms-2">{{ $activePeminjaman->count() }}</span>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr class="text-secondary small">
                                <th class="ps-4 border-0 py-3">DETAIL PEMINJAMAN</th>
                                <th class="border-0 py-3 text-center d-none d-md-table-cell">TGL KEMBALI</th>
                                <th class="border-0 py-3 text-center">STATUS</th>
                                <th class="border-0 py-3 text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activePeminjaman as $pinjam)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-body fs-6">{{ $pinjam->user->name }}</div>
                                        <div class="text-primary small fw-medium mt-1">
                                            <i class="bi bi-box-seam me-1"></i>{{ $pinjam->inventaris->nama_aset }} 
                                            <span class="text-secondary opacity-75">({{ $pinjam->jumlah_pinjam }} Unit)</span>
                                        </div>
                                        <div class="d-md-none mt-1 text-secondary" style="font-size: 0.75rem;">
                                            <i class="bi bi-calendar-check me-1"></i>Estimasi: 
                                            <span class="fw-bold text-body">{{ \Carbon\Carbon::parse($pinjam->tgl_kembali_rencana)->format('d/m/y') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <span class="small text-body">{{ \Carbon\Carbon::parse($pinjam->tgl_kembali_rencana)->format('d M Y') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($pinjam->status == 'pending')
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.65rem;">Pending</span>
                                        @else
                                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle" style="font-size: 0.65rem;">Dipinjam</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if(Auth::user()->is_admin)
                                            <div class="d-flex justify-content-end gap-2">
                                                @if($pinjam->status == 'pending')
                                                    <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="disetujui">
                                                        <button type="submit" class="btn btn-sm btn-success rounded-3 shadow-sm border-0" title="Setujui" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                    {{-- Tombol Tolak --}}
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 border-1" 
                                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pinjam->id }}" title="Tolak" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                @elseif($pinjam->status == 'disetujui')
                                                    <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-1 shadow-sm fw-bold" style="font-size: 0.75rem;">Selesai</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            <i class="bi bi-hourglass-split text-muted opacity-50"></i>
                                        @endif
                                    </td>
                                </tr>

                                {{-- MODAL TOLAK (Khusus Admin) --}}
                                @if(Auth::user()->is_admin)
                                <div class="modal fade" id="rejectModal{{ $pinjam->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white border-0">
                                                <h5 class="modal-title fw-bold">Tolak Peminjaman</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <div class="modal-body text-start">
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <p class="text-body">Tolak peminjaman <strong>{{ $pinjam->inventaris->nama_aset }}</strong> oleh <strong>{{ $pinjam->user->name }}</strong>?</p>
                                                    <div class="mb-0">
                                                        <label class="form-label small fw-bold text-body">Alasan Penolakan</label>
                                                        <textarea name="catatan" class="form-control bg-body text-body" rows="3" placeholder="Contoh: Alat sedang diperbaiki..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Konfirmasi Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted">Tidak ada peminjaman aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL 2: HISTORY --}}
    <div class="mt-5">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-secondary p-2 rounded-3 me-3 d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                <i class="bi bi-clock-history fs-5"></i>
            </div>
            <h5 class="fw-bold mb-0 text-body">Riwayat Peminjaman</h5>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr class="text-secondary small">
                                <th class="ps-4 border-0 py-3">PEMINJAM & ALAT</th>
                                <th class="border-0 py-3 text-center d-none d-md-table-cell">TGL SELESAI</th>
                                <th class="border-0 py-3 text-center">STATUS</th>
                                <th class="border-0 py-3 text-end pe-4">INFO</th>
                            </tr>
                        </thead>
                        <tbody class="text-secondary">
                            @forelse($historyPeminjaman as $pinjam)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold small text-body">{{ $pinjam->user->name }}</div>
                                        <div class="text-primary small">{{ $pinjam->inventaris->nama_aset }}</div>
                                        <div class="d-md-none mt-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($pinjam->updated_at)->format('d/m/y') }}
                                        </div>
                                    </td>
                                    <td class="text-center py-3 d-none d-md-table-cell">
                                        <small class="text-body">{{ \Carbon\Carbon::parse($pinjam->updated_at)->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-center py-3">
                                        @if($pinjam->status == 'selesai')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;">Kembali</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.65rem;">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        @if($pinjam->status == 'ditolak')
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#noteModal{{ $pinjam->id }}">
                                                Catatan
                                            </button>

                                            {{-- MODAL LIHAT CATATAN --}}
                                            <div class="modal fade" id="noteModal{{ $pinjam->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold text-body">Alasan Penolakan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="p-3 bg-body-tertiary rounded-3 border text-body">
                                                                {{ $pinjam->catatan ?? 'Tidak ada catatan tambahan.' }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Mengerti</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <i class="bi bi-check2-all text-success"></i>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted small">Belum ada riwayat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    [data-bs-theme="dark"] .bg-body-tertiary { background-color: #252525 !important; }
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,.02); }
    [data-bs-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,.02); }
</style>
@endsection