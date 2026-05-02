@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 py-3 bg-transparent">
                    <h4 class="fw-bold mb-0">Registrasi Aset Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.inventaris.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kode Barang (Sesuai TIK)</label>
                                <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" placeholder="Contoh: LAB-IOT-005" required>
                                @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nama Aset</label>
                                <input type="text" name="nama_aset" class="form-control" placeholder="Nama lengkap barang" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Mikrokontroler">Mikrokontroler</option>
                                    <option value="Sensor">Sensor/Module</option>
                                    <option value="Komputer">Komputer/Laptop</option>
                                    <option value="Trainer Kit">Trainer Kit</option>
                                    <option value="Alat Ukur">Alat Ukur</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Merk</label>
                                <input type="text" name="merk" class="form-control" placeholder="Dell, Apple, Arduino, dll">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">NUP (Jika ada)</label>
                                <input type="text" name="nup" class="form-control" placeholder="Nomor Urut Pendaftaran">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Ruangan</label>
                                <input type="text" name="ruangan" class="form-control" placeholder="Contoh: Ruang Workshop" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Tahun Perolehan</label>
                                <input type="number" name="tahun_perolehan" class="form-control" value="{{ date('Y') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jumlah Stok</label>
                                <input type="number" name="jumlah_stok" class="form-control" min="0" required>
                            </div>
                        </div>

                        <div class="row mb-4 border p-3 rounded-3 bg-opacity-10 bg-secondary mx-0">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kondisi Barang</label>
                                <select name="kondisi" class="form-select border-primary" required>
                                    <option value="Baik">🟢 Baik (Siap Pakai)</option>
                                    <option value="Rusak Ringan">🟡 Rusak Ringan</option>
                                    <option value="Rusak Berat">🔴 Rusak Berat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-primary">Tipe Akses (Kebijakan PLP)</label>
                                <select name="tipe_peminjaman" class="form-select border-primary" required>
                                    <option value="Hanya di Lab">Statis (Hanya di Lab)</option>
                                    <option value="Bisa Dipinjam">Mobile (Bisa Dipinjam Mahasiswa)</option>
                                </select>
                                <small class="text-muted">Tentukan apakah barang ini boleh dibawa keluar lab.</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Catatan Lokasi Spesifik</label>
                            <textarea name="catatan_lokasi" class="form-control" rows="2" placeholder="Contoh: Lemari C, Laci nomor 4"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-link text-decoration-none text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-save me-2"></i> Simpan Aset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection