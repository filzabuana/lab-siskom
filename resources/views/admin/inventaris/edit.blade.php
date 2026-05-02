@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 py-3 bg-transparent">
                    <h4 class="fw-bold mb-0">Edit Data Aset: {{ $item->nama_aset }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.inventaris.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kode Barang (Sesuai TIK)</label>
                                <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" value="{{ $item->kode_barang }}" required>
                                @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nama Aset</label>
                                <input type="text" name="nama_aset" class="form-control" value="{{ $item->nama_aset }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Mikrokontroler" {{ $item->kategori == 'Mikrokontroler' ? 'selected' : '' }}>Mikrokontroler</option>
                                    <option value="Sensor" {{ $item->kategori == 'Sensor' ? 'selected' : '' }}>Sensor/Module</option>
                                    <option value="Komputer" {{ $item->kategori == 'Komputer' ? 'selected' : '' }}>Komputer/Laptop</option>
                                    <option value="Trainer Kit" {{ $item->kategori == 'Trainer Kit' ? 'selected' : '' }}>Trainer Kit</option>
                                    <option value="Alat Ukur" {{ $item->kategori == 'Alat Ukur' ? 'selected' : '' }}>Alat Ukur</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Merk</label>
                                <input type="text" name="merk" class="form-control" value="{{ $item->merk }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">NUP (Jika ada)</label>
                                <input type="text" name="nup" class="form-control" value="{{ $item->nup }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Ruangan</label>
                                <input type="text" name="ruangan" class="form-control" value="{{ $item->ruangan }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Tahun Perolehan</label>
                                <input type="number" name="tahun_perolehan" class="form-control" value="{{ $item->tahun_perolehan }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jumlah Stok</label>
                                <input type="number" name="jumlah_stok" class="form-control" value="{{ $item->jumlah_stok }}" min="0" required>
                            </div>
                        </div>

                        <div class="row mb-4 border p-3 rounded-3 bg-opacity-10 bg-secondary mx-0">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kondisi Barang</label>
                                <select name="kondisi" class="form-select border-primary" required>
                                    <option value="Baik" {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>🟢 Baik (Siap Pakai)</option>
                                    <option value="Rusak Ringan" {{ $item->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>🟡 Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ $item->kondisi == 'Rusak Berat' ? 'selected' : '' }}>🔴 Rusak Berat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-primary">Tipe Akses (Kebijakan PLP)</label>
                                <select name="tipe_peminjaman" class="form-select border-primary" required>
                                    <option value="Hanya di Lab" {{ $item->tipe_peminjaman == 'Hanya di Lab' ? 'selected' : '' }}>Statis (Hanya di Lab)</option>
                                    <option value="Bisa Dipinjam" {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'selected' : '' }}>Mobile (Bisa Dipinjam Mahasiswa)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Catatan Lokasi Spesifik</label>
                                <textarea name="catatan_lokasi" class="form-control" rows="2">{{ $item->catatan_lokasi }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ganti Foto Alat</label>
                                <input type="file" name="foto_barang" class="form-control" accept="image/*">
                                @if($item->foto_barang)
                                    <small class="text-primary mt-1 d-block">
                                        <i class="bi bi-image me-1"></i> Foto saat ini: {{ $item->foto_barang }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-link text-decoration-none text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-arrow-repeat me-2"></i> Update Data Aset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection