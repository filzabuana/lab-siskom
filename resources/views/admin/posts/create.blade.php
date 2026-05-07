@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4" style="align-items: flex-start !important;">
            {{-- Kolom Kiri: Editor Utama --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary rounded-circle me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0">Tulis Artikel Baru</h2>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    {{-- Input Judul --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Artikel</label>
                        <input type="text" name="title" class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" placeholder="Masukkan judul menarik..." required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Input Konten --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label class="form-label fw-bold mb-0">Konten (Markdown)</label>
                            <span class="badge badge-markdown small">Support Markdown & HTML</span>
                        </div>
                        <textarea name="content" id="markdown-editor" rows="15" 
                                  class="form-control font-monospace rounded-3 @error('content') is-invalid @enderror" 
                                  placeholder="Tulis artikel di sini..." required>{{ old('content') }}</textarea>
                        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Markdown Helper Box --}}
                    <div class="helper-box border rounded-3 p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted-custom">
                                <i class="bi bi-code-slash me-1"></i> Bantuan Penulisan:
                            </span>
                            <a href="{{ route('admin.posts.guide') }}" target="_blank" class="text-decoration-none small fw-bold link-guide">
                                Panduan Lengkap &rarr;
                            </a>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4 col-md-2">
                                <code class="helper-item">## Judul</code>
                            </div>
                            <div class="col-4 col-md-2">
                                <code class="helper-item">**Tebal**</code>
                            </div>
                            <div class="col-4 col-md-2">
                                <code class="helper-item">*Miring*</code>
                            </div>
                            <div class="col-4 col-md-2">
                                <code class="helper-item">[Link](url)</code>
                            </div>
                            <div class="col-4 col-md-2">
                                <code class="helper-item">- List</code>
                            </div>
                            <div class="col-4 col-md-2">
                                <code class="helper-item">`Code`</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Meta Data & Publish (Sticky) --}}
            <div class="col-lg-4">
                <div id="sticky-sidebar"> 
                    
                    {{-- Card Publikasi --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-send me-2"></i>Publikasi</h5>
                        
                        {{-- Switch Status --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Status Publikasi</label>
                            <div class="form-check form-switch p-3 bg-switch-container rounded-3">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_published" id="statusSwitch" 
                                       value="1" {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="statusSwitch">Terbitkan Sekarang</label>
                            </div>
                        </div>

                        {{-- Upload Gambar --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Gambar Sampul</label>
                            
                            <div class="alert alert-custom py-2 px-3 border-0 rounded-3 mb-3" style="font-size: 0.85rem;">
                                <i class="bi bi-aspect-ratio me-1"></i> Gunakan rasio **16:9** (contoh: 1280x720px) agar thumbnail tidak terpotong.
                            </div>

                            <div id="image-preview-wrapper" class="mb-2"></div>
                            <input type="file" name="image" class="form-control rounded-3 @error('image') is-invalid @enderror" id="image-input" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow">
                            <i class="bi bi-plus-circle me-2"></i>Simpan Artikel
                        </button>
                    </div>

                    {{-- Card Info --}}
                    <div class="card border-0 bg-info-soft rounded-4 p-3 text-center">
                        <small class="text-info-custom"><i class="bi bi-info-circle-fill me-1"></i> Artikel draft tidak akan muncul di halaman publik.</small>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* --- Styling Editor & Common Elements --- */
    #markdown-editor {
        background-color: #fcfcfc;
        border: 1px solid #e0e0e0;
        resize: vertical;
        line-height: 1.6;
    }

    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }

    #preview-img {
        aspect-ratio: 16 / 9;
        width: 100%;
        object-fit: cover;
    }

    /* --- Custom Colors & Containers (Light Mode Default) --- */
    .helper-box { background-color: #f8f9fa; }
    .helper-item { display: block; padding: 4px; border: 1px solid #dee2e6; border-radius: 4px; background-color: #fff; color: #212529; font-size: 0.75rem; }
    .badge-markdown { background-color: #e9ecef; color: #495057; }
    .bg-switch-container { background-color: #f8f9fa; }
    .alert-custom { background-color: #e1f5fe; color: #01579b; }
    .bg-info-soft { background-color: rgba(13, 110, 253, 0.1); }
    .text-info-custom { color: #0d6efd; }
    .text-muted-custom { color: #6c757d; }
    .link-guide { color: #0d6efd; }

    /* --- DARK MODE OVERRIDES ([data-bs-theme="dark"]) --- */
    [data-bs-theme="dark"] #markdown-editor {
        background-color: #1e1e1e;
        border-color: #333;
        color: #fff;
    }

    [data-bs-theme="dark"] .card {
        background-color: #2b3035 !important;
        color: #dee2e6 !important;
    }

    [data-bs-theme="dark"] .helper-box {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: #444 !important;
    }

    [data-bs-theme="dark"] .helper-item {
        background-color: #1e1e1e;
        border-color: #444;
        color: #e685b5; /* Pink khas code agar stand out */
    }

    [data-bs-theme="dark"] .badge-markdown {
        background-color: #373b3e;
        color: #adb5bd;
    }

    [data-bs-theme="dark"] .bg-switch-container {
        background-color: rgba(0, 0, 0, 0.2);
    }

    [data-bs-theme="dark"] .alert-custom {
        background-color: rgba(0, 150, 136, 0.1);
        color: #4db6ac;
    }

    [data-bs-theme="dark"] .text-info-custom {
        color: #7abaff;
    }

    [data-bs-theme="dark"] .text-muted-custom {
        color: #adb5bd;
    }

    [data-bs-theme="dark"] .link-guide {
        color: #7abaff;
    }

    /* Logic Sticky Sidebar */
    @media (min-width: 992px) {
        #sticky-sidebar {
            position: sticky !important;
            top: 100px !important;
            z-index: 100;
            align-self: flex-start;
        }
    }
</style>

<script>
    document.getElementById('image-input').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            let preview = document.getElementById('preview-img');
            const wrapper = document.getElementById('image-preview-wrapper');
            
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'preview-img';
                preview.className = 'img-fluid rounded-3 shadow-sm border mb-2';
                wrapper.appendChild(preview);
            }
            preview.src = URL.createObjectURL(file);
        }
    }
</script>
@endsection