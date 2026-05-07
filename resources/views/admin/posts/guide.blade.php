@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-5">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-white shadow-sm rounded-circle me-3 border">
                    <i class="bi bi-arrow-left text-primary"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">Panduan Penulisan Konten</h2>
                    <p class="text-muted mb-0">SOP Publikasi Web Lab Pemrograman & Komputasi</p>
                </div>
            </div>

            <div class="row g-4">
                {{-- 1. Standar Visual --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-image me-2"></i>1. Standar Visual & Media</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0"><i class="bi bi-check-circle-fill text-success"></i></div>
                                        <div class="ms-3">
                                            <strong>Rasio Aspek 16:9</strong>
                                            <p class="small text-muted">Wajib menggunakan resolusi 1280x720px atau 1920x1080px agar tidak terpotong di landing page.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0"><i class="bi bi-check-circle-fill text-success"></i></div>
                                        <div class="ms-3">
                                            <strong>Format WebP / JPG</strong>
                                            <p class="small text-muted">Gunakan <code>.webp</code> untuk efisiensi loading atau <code>.jpg</code> yang sudah dikompresi.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0"><i class="bi bi-exclamation-triangle-fill text-warning"></i></div>
                                        <div class="ms-3">
                                            <strong>Tipografi Gambar</strong>
                                            <p class="small text-muted">Hindari menaruh teks penting di pinggir gambar karena sistem otomatis melakukan cropping pada thumbnail.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-center">
                                    <div class="p-4 bg-light rounded-4 border border-dashed shadow-inner">
                                        <div class="bg-white border rounded-2 shadow-sm mx-auto d-flex align-items-center justify-content-center" style="aspect-ratio: 16/9; max-width: 220px;">
                                            <span class="text-primary fw-bold">16 : 9</span>
                                        </div>
                                        <p class="mt-2 mb-0 x-small text-muted">Contoh Area Thumbnail</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Panduan Markdown --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-dark border-0 py-3">
                        <h5 class="fw-bold text-white mb-0"><i class="bi bi-markdown me-2"></i>2. Cheat Sheet Markdown (Parsedown)</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Kolom Kiri: Dasar-dasar --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold border-bottom pb-2 mb-3">Struktur Teks</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <thead>
                                            <tr class="text-muted small">
                                                <th>Hasil</th>
                                                <th>Sintaks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><h1>H1</h1></td>
                                                <td><code># Judul Utama</code></td>
                                            </tr>
                                            <tr>
                                                <td><h3>H3</h3></td>
                                                <td><code>### Sub Judul</code></td>
                                            </tr>
                                            <tr>
                                                <td><i>Miring</i></td>
                                                <td><code>*Teks Miring*</code></td>
                                            </tr>
                                            <tr>
                                                <td><del>Coret</del></td>
                                                <td><code>~~Teks Dicoret~~</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3">Daftar (Lists)</h6>
                                <div class="bg-light p-3 rounded-3 mb-3">
                                    <p class="mb-1 small text-muted">Peluru (Unordered):</p>
                                    <code>- Item satu</code><br>
                                    <code>- Item dua</code>
                                    
                                    <p class="mt-2 mb-1 small text-muted">Nomor (Ordered):</p>
                                    <code>1. Langkah pertama</code><br>
                                    <code>2. Langkah kedua</code>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Lanjutan --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold border-bottom pb-2 mb-3">Tabel & Tugas</h6>
                                <div class="bg-light p-3 rounded-3 mb-3 small">
                                    <p class="mb-1 fw-bold">Membuat Tabel:</p>
                                    <pre class="mb-0"><code>| Header 1 | Header 2 |
                |----------|----------|
                | Baris 1  | Data 1   |
                | Baris 2  | Data 2   |</code></pre>
                                </div>

                                <div class="bg-light p-3 rounded-3 mb-3 small">
                                    <p class="mb-1 fw-bold">Tautan & Gambar:</p>
                                    <code>[Nama Link](https://google.com)</code><br>
                                    <code>![Alt Gambar](https://url-gambar.jpg)</code>
                                </div>

                                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3">Penulisan Kode</h6>
                                <p class="small text-muted">Untuk blok kode pemrograman, gunakan <i>backticks</i> tiga kali disertai nama bahasa:</p>
                                <div class="bg-dark text-info p-2 rounded-3 small">
                                    <span class="text-secondary">```python</span><br>
                                    def hello_world():<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;print("Hello Lab!")<br>
                                    <span class="text-secondary">```</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tips Ekstra --}}
                        <div class="alert alert-info border-0 rounded-4 mt-4 mb-0">
                            <div class="d-flex">
                                <i class="bi bi-lightbulb fs-4 me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Tips Parsedown Lab:</h6>
                                    <p class="small mb-0">Parsedown mendukung HTML mentah. Jika ingin membuat baris baru yang renggang, kamu bisa menyisipkan tag <code>&lt;br&gt;</code> secara manual di dalam konten Markdown.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 3. Etika --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 border-top border-primary border-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="bi bi-chat-left-quote text-primary fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Bahasa & Gaya</h5>
                        </div>
                        <p class="text-muted small mb-0">
                            Gunakan Bahasa Indonesia formal (PUEBI). Karena ini adalah portal resmi <strong>Untan</strong>, hindari penggunaan bahasa slang atau singkatan tidak baku.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 border-top border-danger border-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="bi bi-shield-check text-danger fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Verifikasi Konten</h5>
                        </div>
                        <p class="text-muted small mb-0">
                            Pastikan status postingan adalah <strong>Draft</strong> sebelum diverifikasi oleh PLP atau Koordinator Lab untuk menghindari kesalahan info publik.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 mb-5">
                <div class="d-inline-block p-3 bg-white border rounded-pill shadow-sm px-4">
                    <span class="text-muted small">Butuh bantuan teknis? Hubungi <strong>Filza Buana Putra</strong> atau Admin Lab.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: all 0.3s ease; }
    .card:hover { transform: translateY(-5px); }
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    code { font-size: 0.85em; color: #d63384; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection