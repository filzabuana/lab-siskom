@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-body">Daftar Pengajuan Bebas Lab</h2>
            <p class="text-body-secondary mb-0">Verifikasi pengajuan mahasiswa dan cek status peminjaman alat.</p>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $data->count() }} Total Pengajuan</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4 py-3">Mahasiswa</th>
                            <th class="py-3">Prodi</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Tanggal Pengajuan</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-body">{{ $item->nama }}</div>
                                <div class="text-body-secondary small">{{ $item->nim }} | {{ $item->email }}</div>
                            </td>
                            <td><span class="text-body">{{ $item->prodi }}</span></td>
                            <td>
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Menunggu Verifikasi Email</span>
                                @elseif($item->status == 'verified_email')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3">Email Terverifikasi (Siap Cek Alat)</span>
                                @elseif($item->status == 'disetujui')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">Disetujui</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-body-secondary small">
                                {{ $item->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="text-end pe-4">
                                @if($item->status == 'verified_email')
                                    <!-- Button Trigger Modal Verifikasi -->
                                    <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalVerify{{ $item->id }}">
                                        Proses
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" disabled>Selesai</button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Verifikasi Admin -->
                        <div class="modal fade" id="modalVerify{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <form action="{{ route('admin.bebas-lab.update', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Proses Pengajuan: {{ $item->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-body-secondary">Pastikan mahasiswa ini sudah tidak memiliki pinjaman alat di Lab SISKOM.</p>
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Pilih Keputusan</label>
                                                <select name="action" class="form-select" required onchange="toggleCatatan(this, {{ $item->id }})">
                                                    <option value="setujui">Setujui & Kirim Surat Bebas Lab</option>
                                                    <option value="tolak">Tolak & Beri Alasan</option>
                                                </select>
                                            </div>

                                            <div id="catatanSection{{ $item->id }}" class="mb-3 d-none">
                                                <label class="form-label fw-bold">Catatan Penolakan</label>
                                                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Silakan kembalikan Solder dan Kit Arduino yang dipinjam bulan lalu."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-body-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada pengajuan masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCatatan(select, id) {
        const section = document.getElementById('catatanSection' + id);
        if (select.value === 'tolak') {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    }
</script>
@endsection