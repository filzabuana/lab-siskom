@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 py-3 bg-transparent">
                    <h4 class="fw-bold mb-0 text-body">Registrasi Aset Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.inventaris.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Row 1: Kode & Nama --}}
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kode Barang (Sesuai TIK)</label>
                                <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" placeholder="Contoh: LAB-IOT-005" required value="{{ old('kode_barang') }}">
                                @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nama Aset</label>
                                <input type="text" name="nama_aset" class="form-control" placeholder="Nama lengkap barang" required value="{{ old('nama_aset') }}">
                            </div>
                        </div>

                        {{-- Row 2: Kategori, Merk, NUP --}}
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Mikrokontroler">Mikrokontroler</option>
                                    <option value="Sensor">Sensor/Module</option>
                                    <option value="Komputer">Komputer/Laptop</option>
                                    <option value="Trainer Kit">Trainer Kit</option>
                                    <option value="Alat Ukur">Alat Ukur</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Merk</label>
                                <input type="text" name="merk" class="form-control" placeholder="Dell, Apple, Arduino, dll" value="{{ old('merk') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">NUP (Jika ada)</label>
                                <input type="text" name="nup" class="form-control" placeholder="Nomor Urut Pendaftaran" value="{{ old('nup') }}">
                            </div>
                        </div>

                        {{-- Row 3: Ruangan, Tahun, Stok Baik, Stok Rusak --}}
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Ruangan</label>
                                <input type="text" name="ruangan" class="form-control" placeholder="Contoh: Lab Komputasi" required value="{{ old('ruangan') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Tahun Perolehan</label>
                                <input type="number" name="tahun_perolehan" class="form-control" value="{{ date('Y') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-success">Jumlah Stok Baik</label>
                                <input type="number" name="jumlah_stok" class="form-control border-success" min="0" placeholder="0" required value="{{ old('jumlah_stok', 0) }}">
                                <small class="text-muted">Unit siap pakai</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-danger">Jumlah Rusak</label>
                                <input type="number" name="jumlah_rusak" class="form-control border-danger" min="0" placeholder="0" required value="{{ old('jumlah_rusak', 0) }}">
                                <small class="text-muted">Unit tidak berfungsi</small>
                            </div>
                        </div>

                        {{-- Row 4: Kondisi & Tipe Akses --}}
                        <div class="row mb-4 border p-3 rounded-4 bg-light mx-0 shadow-sm">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kondisi Umum (Status)</label>
                                <select name="kondisi" class="form-select border-primary" required>
                                    <option value="Baik">🟢 Baik (Siap Pakai)</option>
                                    <option value="Rusak Ringan">🟡 Rusak Ringan</option>
                                    <option value="Rusak Berat">🔴 Rusak Berat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-primary">Kebijakan Peminjaman</label>
                                <select name="tipe_peminjaman" class="form-select border-primary" required>
                                    <option value="Hanya di Lab">Statis (Hanya di Lab)</option>
                                    <option value="Bisa Dipinjam">Mobile (Bisa Dipinjam Mahasiswa)</option>
                                </select>
                                <small class="text-muted">Tentukan aksesibilitas alat bagi mahasiswa.</small>
                            </div>
                        </div>

                        {{-- Row 5: Deskripsi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi & Fungsi Alat</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan spesifikasi singkat atau kegunaan alat ini untuk mahasiswa...">{{ old('deskripsi') }}</textarea>
                        </div>

                        {{-- Row 6: Catatan Lokasi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Catatan Lokasi Spesifik (Internal)</label>
                            <textarea name="catatan_lokasi" class="form-control" rows="2" placeholder="Contoh: Lemari C, Laci nomor 4 (Hanya dilihat admin)">{{ old('catatan_lokasi') }}</textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-link text-decoration-none text-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-save me-2"></i> Simpan Aset Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection