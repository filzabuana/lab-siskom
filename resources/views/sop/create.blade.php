@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                {{-- Menghapus bg-white dan text-dark agar adaptif --}}
                <div class="card-header border-0 py-3 bg-transparent">
                    <h4 class="fw-bold mb-0">Tambah SOP Baru</h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('sop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Judul SOP</label>
                                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: SOP Praktikum Semester Genap" required>
                                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" id="kategoriSelect" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="Layanan">1. Layanan</option>
                                    <option value="Teknis">2. Teknis</option>
                                    <option value="Pemeliharaan dan Kalibrasi">3. Pemeliharaan dan Kalibrasi</option>
                                    <option value="Keselamatan dan Kesehatan Kerja">4. Keselamatan dan Kesehatan Kerja</option>
                                    <option value="Manajemen Mutu dan Administrasi">5. Manajemen Mutu dan Administrasi</option>
                                    <option value="Manajemen data dan aset digital">6. Manajemen data dan aset digital</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 d-none mb-3" id="kategoriLainnyaWrapper">
                                <label class="form-label opacity-75">Sebutkan Kategori Baru</label>
                                <input type="text" name="kategori_input_manual" class="form-control" placeholder="Masukkan nama kategori">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi Ringkas</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Jelaskan secara singkat tujuan SOP ini..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Dokumen PDF (Maks 2MB)</label>
                            <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror" accept=".pdf" required>
                            @error('file_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-5 opacity-25">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0 text-primary">Bagian Alur Prosedur (Mermaid)</h5>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="tambahAlur()">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Bagian Alur
                            </button>
                        </div>

                        <div id="container-alur">
                            {{-- Mengganti bg-light dengan border dan bg-transparent agar terlihat di dark mode --}}
                            <div class="alur-item card border p-3 mb-3 shadow-sm rounded-3 bg-opacity-10 bg-secondary">
                                <div class="d-flex justify-content-between mb-2">
                                    <label class="fw-bold small opacity-75">BAGIAN #1</label>
                                    <span class="small opacity-50">Wajib diisi minimal satu</span>
                                </div>
                                <input type="text" name="alur_judul[]" class="form-control mb-2" placeholder="Judul Bagian (Contoh: Tahap Persiapan)" required>
                                <textarea name="alur_kode[]" class="form-control mb-1" rows="4" style="font-family: monospace;" placeholder="Ketik kode flowchart TD..." required></textarea>
                                <div class="form-text">Gunakan format <a href="https://mermaid.js.org/syntax/flowchart.html" target="_blank" class="text-decoration-none">Mermaid.js Flowchart</a></div>
                            </div>
                        </div>

                        <div class="mt-5 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                                <i class="bi bi-save me-2"></i> Simpan SOP Lengkap
                            </button>
                            <a href="{{ route('sop.index') }}" class="btn btn-link text-decoration-none text-secondary mt-2">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('kategoriSelect').addEventListener('change', function() {
        const wrapper = document.getElementById('kategoriLainnyaWrapper');
        if (this.value === 'Lainnya') {
            wrapper.classList.remove('d-none');
        } else {
            wrapper.classList.add('d-none');
        }
    });

    let count = 1;
    function tambahAlur() {
        count++;
        const container = document.getElementById('container-alur');
        // HTML Template juga disesuaikan untuk Dark Mode
        const html = `
            <div class="alur-item card border p-3 mb-3 shadow-sm rounded-3 bg-opacity-10 bg-secondary animate__animated animate__fadeInUp">
                <div class="d-flex justify-content-between mb-2">
                    <label class="fw-bold small opacity-75">BAGIAN #${count}</label>
                    <button type="button" class="btn-close" onclick="hapusAlur(this)" aria-label="Close"></button>
                </div>
                <input type="text" name="alur_judul[]" class="form-control mb-2" placeholder="Judul Bagian" required>
                <textarea name="alur_kode[]" class="form-control" rows="4" style="font-family: monospace;" placeholder="Ketik kode Mermaid..." required></textarea>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function hapusAlur(btn) {
        btn.closest('.alur-item').remove();
    }
</script>

<style>
    /* Menggunakan variabel CSS untuk shadow agar lebih soft di dark mode */
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        border-color: #0d6efd;
    }
    /* Pastikan input background mengikuti tema */
    .card {
        background-color: var(--bs-card-bg);
    }
</style>
@endsection