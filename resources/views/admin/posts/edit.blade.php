@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4" style="align-items: flex-start !important;">
            
            {{-- Kolom Kiri: Editor Utama --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary rounded-circle me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0">Edit Artikel</h2>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    {{-- Input Judul --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Artikel</label>
                        <input type="text" name="title" class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" 
                               value="{{ old('title', $post->title) }}" placeholder="Masukkan judul..." required>
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
                                  placeholder="Tulis konten..." required>{{ old('content', $post->content) }}</textarea>
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
                            <div class="col-4 col-md-2"><code class="helper-item">## Judul</code></div>
                            <div class="col-4 col-md-2"><code class="helper-item">**Tebal**</code></div>
                            <div class="col-4 col-md-2"><code class="helper-item">*Miring*</code></div>
                            <div class="col-4 col-md-2"><code class="helper-item">[Link](url)</code></div>
                            <div class="col-4 col-md-2"><code class="helper-item">- List</code></div>
                            <div class="col-4 col-md-2"><code class="helper-item">`Code`</code></div>
                        </div>
                    </div>
                </div>
            </div> {{-- Akhir Kolom Kiri --}}

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
                                       value="1" {{ $post->is_published ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="statusSwitch">Terbitkan Artikel</label>
                            </div>
                        </div>

                        {{-- Upload Gambar --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Gambar Sampul</label>
                            <div class="alert alert-custom py-2 px-3 border-0 rounded-3 mb-3" style="font-size: 0.85rem;">
                                <i class="bi bi-aspect-ratio me-1"></i> Gunakan rasio **16:9** agar thumbnail tidak terpotong.
                            </div>
                            
                            <div id="image-preview-wrapper" class="mb-2">
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded-3 shadow-sm border" id="preview-img">
                                @endif
                            </div>
                            <input type="file" name="image" class="form-control rounded-3" id="image-input" accept="image/*">
                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <hr class="my-4">

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow">
                            <i class="bi bi-save2 me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                    {{-- Card Info Tambahan --}}
                    <div class="card border-0 bg-info-soft rounded-4 p-3 text-center">
                        <small class="text-info-custom">
                            <i class="bi bi-shield-check me-1"></i> Konten ini akan dipublikasikan ke portal berita Lab Pemrograman dan Komputasi.
                        </small>
                    </div>
                </div>
            </div> {{-- Akhir Kolom Kanan --}}
            
        </div>
    </form>
</div>

<style>
    /* --- Base Styling --- */
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
        object-fit: cover;
        width: 100%;
    }

    /* --- Custom Colors (Light Mode) --- */
    .helper-box { background-color: #f8f9fa; }
    .helper-item { display: block; padding: 4px; border: 1px solid #dee2e6; border-radius: 4px; background-color: #fff; color: #212529; font-size: 0.75rem; }
    .badge-markdown { background-color: #e9ecef; color: #495057; }
    .bg-switch-container { background-color: #f8f9fa; }
    .alert-custom { background-color: #e1f5fe; color: #01579b; }
    .bg-info-soft { background-color: rgba(13, 110, 253, 0.1); }
    .text-info-custom { color: #0d6efd; }
    .text-muted-custom { color: #6c757d; }
    .link-guide { color: #0d6efd; }

    /* --- DARK MODE OVERRIDES --- */
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
        color: #e685b5;
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

    [data-bs-theme="dark"] .text-info-custom { color: #7abaff; }
    [data-bs-theme="dark"] .text-muted-custom { color: #adb5bd; }
    [data-bs-theme="dark"] .link-guide { color: #7abaff; }

    /* Sticky Sidebar Logic */
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
    // Preview Gambar Instant
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