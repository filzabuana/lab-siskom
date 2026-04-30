@extends('layouts.app')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            
            <div class="mb-4 text-center">
                <a href="{{ route('sop.index') }}" class="btn btn-link text-decoration-none p-0 text-secondary mb-3 small">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Repository
                </a>
                <h2 class="fw-bold text-dark mb-2">{{ $sop->judul }}</h2>
                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.7rem;">
                    {{ $sop->kategori }}
                </span>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0 text-center text-md-start">
                        <h6 class="fw-bold m-0 text-dark">Dokumen resmi tersedia</h6>
                        <small class="text-muted">Versi lengkap dalam format PDF</small>
                    </div>
                    <a href="{{ asset('dokumen-sop/' . $sop->file_pdf) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" download>
                        <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-primary mb-2 small text-uppercase tracking-wider">Ringkasan</h6>
                    <p class="text-secondary mb-0" style="line-height: 1.7; white-space: pre-line; font-size: 0.95rem;">
                        {{ $sop->deskripsi }}
                    </p>
                </div>
            </div>

            <div class="mb-5">
                <h6 class="fw-bold text-muted mb-4 text-center small">--- KLIK TAHAPAN UNTUK MEMBUKA ALUR ---</h6>
                
                @php $semuaAlur = json_decode($sop->gambar_alur, true); @endphp

                @if(is_array($semuaAlur))
                    <div class="accordion border-0 shadow-none" id="accordionSOP">
                        @foreach($semuaAlur as $index => $alur)
                            <div class="accordion-item border-0 shadow-sm mb-3 rounded-4 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold py-3 px-4" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $index }}" 
                                            aria-expanded="false">
                                        <span class="text-primary me-2">{{ $index + 1 }}.</span> {{ $alur['judul'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#accordionSOP">
                                    <div class="accordion-body p-0 bg-white">
                                        <div class="mermaid-outer border-top">
                                            <div class="mermaid-inner">
                                                <div class="mermaid-container" data-code="{{ $alur['kode'] }}">
                                                    <div class="text-muted small py-3">Memuat alur...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    mermaid.initialize({ 
        startOnLoad: false, 
        theme: 'neutral',
        flowchart: { useMaxWidth: true, htmlLabels: true, curve: 'stepAfter' }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const accordionSOP = document.getElementById('accordionSOP');

        if (accordionSOP) {
            accordionSOP.addEventListener('shown.bs.collapse', function (event) {
                const target = event.target;
                const container = target.querySelector('.mermaid-container');
                const code = container.getAttribute('data-code');

                // Hanya render jika belum pernah dirender sebelumnya
                if (container && !container.classList.contains('rendered')) {
                    container.innerHTML = `<div class="mermaid">${code}</div>`;
                    const mermaidDiv = container.querySelector('.mermaid');
                    
                    mermaid.init(undefined, mermaidDiv).then(() => {
                        container.classList.add('rendered');
                    }).catch(err => {
                        console.error("Mermaid Render Error:", err);
                        container.innerHTML = `<div class="alert alert-danger m-3 small">Gagal merender alur. Silakan cek format kode Mermaid.</div>`;
                    });
                }
            });
        }
    });
</script>

<style>
    body { background-color: #f8fafc; overflow-x: hidden; font-family: 'Inter', sans-serif; }
    
    /* Accordion Custom */
    .accordion-item { border-radius: 12px !important; }
    .accordion-button { border-radius: 12px !important; font-size: 0.9rem; border: none; }
    .accordion-button:not(.collapsed) { background-color: #fff; color: #0d6efd; box-shadow: none; border-bottom: 1px solid #f1f5f9; }
    .accordion-button:focus { box-shadow: none; }

    /* Mermaid Layout */
    .mermaid-outer { width: 100%; overflow-x: auto; background: #fff; -webkit-overflow-scrolling: touch; }
    .mermaid-inner { display: inline-flex; min-width: 100%; justify-content: center; padding: 25px 15px; }
    .mermaid { width: 100%; max-width: 700px; margin: 0 auto; }
    .mermaid svg { height: auto !important; width: 100% !important; }

    @media (max-width: 768px) {
        .mermaid-inner { justify-content: flex-start; padding: 15px 10px; }
        .mermaid { min-width: 330px; }
        .mermaid-outer::-webkit-scrollbar { display: none; }
        .accordion-button { font-size: 0.85rem; }
    }
    .mermaid-outer::-webkit-scrollbar { height: 4px; }
    .mermaid-outer::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection