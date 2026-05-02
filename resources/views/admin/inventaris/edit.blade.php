@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 py-3 bg-transparent d-flex align-items-center">
                    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-light btn-sm rounded-circle me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold mb-0 text-body">Edit Data Aset</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.inventaris.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Row 1: Kode & Nama --}}
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kode Barang</label>
                                <input type="text" class="form-control bg-light" value="{{ $item->kode_barang }}" readonly>
                                <small class="text-muted">Kode barang tidak dapat diubah.</small>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nama Aset</label>
                                <input type="text" name="nama_aset" class="form-control" value="{{ old('nama_aset', $item->nama_aset) }}" required>
                            </div>
                        </div>

                        {{-- Row 2: Kategori, Merk, NUP --}}
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    @foreach(['Mikrokontroler', 'Sensor', 'Komputer', 'Trainer Kit', 'Alat Ukur', 'Lainnya'] as $cat)
                                        <option value="{{ $cat }}" {{ $item->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Merk</label>
                                <input type="text" name="merk" class="form-control" value="{{ old('merk', $item->merk) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">NUP</label>
                                <input type="text" name="nup" class="form-control" value="{{ old('nup', $item->nup) }}">
                            </div>
                        </div>

                        {{-- Row 3: Ruangan, Tahun, Stok Baik, Stok Rusak --}}
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Ruangan</label>
                                <input type="text" name="ruangan" class="form-control" value="{{ old('ruangan', $item->ruangan) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Tahun Perolehan</label>
                                <input type="number" name="tahun_perolehan" class="form-control" value="{{ old('tahun_perolehan', $item->tahun_perolehan) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-success">Jumlah Stok Baik</label>
                                <input type="number" name="jumlah_stok" class="form-control border-success" min="0" value="{{ old('jumlah_stok', $item->jumlah_stok) }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-danger">Jumlah Rusak</label>
                                <input type="number" name="jumlah_rusak" class="form-control border-danger" min="0" value="{{ old('jumlah_rusak', $item->jumlah_rusak) }}" required>
                            </div>
                        </div>

                        {{-- Row 4: Kondisi & Tipe Akses --}}
                        <div class="row mb-4 border p-3 rounded-4 bg-light mx-0 shadow-sm">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kondisi Umum (Status)</label>
                                <select name="kondisi" class="form-select border-primary" required>
                                    <option value="Baik" {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>🟢 Baik (Siap Pakai)</option>
                                    <option value="Rusak Ringan" {{ $item->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>🟡 Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ $item->kondisi == 'Rusak Berat' ? 'selected' : '' }}>🔴 Rusak Berat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-primary">Kebijakan Peminjaman</label>
                                <select name="tipe_peminjaman" class="form-select border-primary" required>
                                    <option value="Hanya di Lab" {{ $item->tipe_peminjaman == 'Hanya di Lab' ? 'selected' : '' }}>Statis (Hanya di Lab)</option>
                                    <option value="Bisa Dipinjam" {{ $item->tipe_peminjaman == 'Bisa Dipinjam' ? 'selected' : '' }}>Mobile (Bisa Dipinjam Mahasiswa)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 5: Deskripsi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi & Fungsi Alat</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                        </div>

                        {{-- Row 6: Update Foto --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Update Foto Barang</label>
                            @if($item->foto_barang)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/inventaris/' . $item->foto_barang) }}" class="rounded-3 shadow-sm" style="height: 100px;">
                                    <small class="text-muted d-block">Foto saat ini</small>
                                </div>
                            @endif
                            <input type="file" name="foto_barang" class="form-control">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-link text-decoration-none text-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-check2-all me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection