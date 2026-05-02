@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-0">Daftar Peminjaman Alat</h2>
                <p class="text-body-secondary">Monitor status peminjaman aset Lab Pemrograman</p>
            </div>
            {{-- Tombol ini muncul untuk mahasiswa agar bisa kembali ke daftar alat --}}
            @if(!Auth::user()->is_admin)
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Pinjam Alat Baru
                </a>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small">
                            <th class="ps-4 border-0">NAMA PEMINJAM</th>
                            <th class="border-0">ALAT</th>
                            <th class="border-0 text-center">JUMLAH</th>
                            <th class="border-0">TGL KEMBALI (RENCANA)</th>
                            <th class="border-0 text-center">STATUS</th>
                            <th class="border-0 text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $pinjam)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $pinjam->user->name }}</div>
                                <small class="text-secondary">{{ $pinjam->user->email }}</small>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $pinjam->inventaris->nama_aset }}</div>
                                <small class="text-muted">{{ $pinjam->inventaris->kode_barang }}</small>
                            </td>
                            <td class="text-center">{{ $pinjam->jumlah_pinjam }} Unit</td>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tgl_kembali_rencana)->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($pinjam->status == 'pending')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                @elseif($pinjam->status == 'disetujui')
                                    <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle">Dipinjam</span>
                                @elseif($pinjam->status == 'selesai')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Kembali</span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if(Auth::user()->is_admin && $pinjam->status == 'pending')
                                    {{-- Tombol Aksi untuk Admin (Approve/Reject) --}}
                                    <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button class="btn btn-sm btn-success rounded-pill px-3">Setujui</button>
                                    </form>
                                    
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pinjam->id }}">Tolak</button>

                                @elseif(Auth::user()->is_admin && $pinjam->status == 'disetujui')
                                    {{-- Tombol Tandai Selesai jika alat sudah dipulangkan --}}
                                    <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button class="btn btn-sm btn-primary rounded-pill px-3">Sudah Kembali</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Tolak (Hanya untuk Admin) --}}
                        <div class="modal fade" id="rejectModal{{ $pinjam->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('admin.peminjaman.update', $pinjam->id) }}" method="POST" class="modal-content">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Penolakan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Contoh: Alat sedang digunakan untuk praktikum matakuliah X" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Kirim Penolakan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada riwayat peminjaman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection