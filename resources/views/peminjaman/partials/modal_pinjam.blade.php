{{-- resources/views/peminjaman/partials/modal_pinjam.blade.php --}}
<div class="modal fade" id="modalPinjam{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start"> {{-- text-start agar teks tidak center jika di katalog --}}
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Form Peminjaman Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded-3 text-center">
                        <h6 class="mb-1 fw-bold text-dark">{{ $item->nama_aset }}</h6>
                        <span class="badge bg-primary rounded-pill">Stok: {{ $item->jumlah_stok }}</span>
                    </div>

                    <input type="hidden" name="inventaris_id" value="{{ $item->id }}">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Pinjam</label>
                        <input type="number" name="jumlah_pinjam" class="form-control" min="1" max="{{ $item->jumlah_stok }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Rencana Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali_rencana" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Praktikum Pemrograman Jaringan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>