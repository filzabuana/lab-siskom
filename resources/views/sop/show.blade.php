@extends('layouts.app')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            
            <div class="mb-4 text-center">
                <!-- text-secondary -> text-body-secondary -->
                <a href="{{ route('sop.index') }}" class="btn btn-link text-decoration-none p-0 text-body-secondary mb-3 small">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Repository
                </a>
                <!-- text-dark -> text-body -->
                <h2 class="fw-bold text-body mb-2">{{ $sop->judul }}</h2>
                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.7rem;">
                    {{ $sop->kategori }}
                </span>
            </div>

            <!-- bg-white -> bg-body-tertiary -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-body-tertiary">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0 text-center text-md-start">
                        <h6 class="fw-bold m-0 text-body">Dokumen resmi tersedia</h6>
                        <small class="text-body-secondary">Versi lengkap dalam format PDF</small>
                    </div>
                    <a href="{{ asset('dokumen-sop/' . $sop->file_pdf) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" download>
                        <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-body-tertiary">
                <div class="card-body p-4 text-body">
                    <h6 class="fw-bold text-primary mb-2 small text-uppercase tracking-wider">Ringkasan</h6>
                    <p class="text-body-secondary mb-0" style="line-height: 1.7; white-space: pre-line; font-size: 0.95rem;">
                        {{ $sop->deskripsi }}
                    </p>
                </div>
            </div>

            <div class="mb-5">
                <h6 class="fw-bold text-body-tertiary mb-4 text-center small">--- KLIK TAHAPAN UNTUK MEMBUKA ALUR ---</h6>
                    <!-- Bagian Legenda Warna -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-body-tertiary">
                        <div class="card-body p-3">
                            <div class="text-center mb-2">
                                <small class="fw-bold text-body-secondary text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Legenda Pelaksana (Aktor)</small>
                            </div>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <div class="d-flex align-items-center bg-body p-2 rounded-3 border border-secondary-subtle">
                                    <span class="badge bg-primary me-2">&nbsp;</span>
                                    <small class="text-body fw-medium" style="font-size: 0.75rem;">Kepala Lab</small>
                                </div>
                                <div class="d-flex align-items-center bg-body p-2 rounded-3 border border-secondary-subtle">
                                    <span class="badge bg-success me-2">&nbsp;</span>
                                    <small class="text-body fw-medium" style="font-size: 0.75rem;">Laboran/PLP</small>
                                </div>
                                <div class="d-flex align-items-center bg-body p-2 rounded-3 border border-secondary-subtle">
                                    <span class="badge bg-warning me-2">&nbsp;</span>
                                    <small class="text-body fw-medium" style="font-size: 0.75rem;">Dosen</small>
                                </div>
                                <div class="d-flex align-items-center bg-body p-2 rounded-3 border border-secondary-subtle">
                                    <span class="badge bg-info me-2">&nbsp;</span>
                                    <small class="text-body fw-medium" style="font-size: 0.75rem;">Asisten</small>
                                </div>
                                <div class="d-flex align-items-center bg-body p-2 rounded-3 border border-secondary-subtle">
                                    <span class="badge bg-secondary me-2">&nbsp;</span>
                                    <small class="text-body fw-medium" style="font-size: 0.75rem;">Mahasiswa</small>
                                </div>
                            </div>
                        </div>
                    </div>

                @php $semuaAlur = json_decode($sop->gambar_alur, true); @endphp

                @if(is_array($semuaAlur))
                    <div class="accordion border-0 shadow-none" id="accordionSOP">
                        @foreach($semuaAlur as $index => $alur)
                            <div class="accordion-item border-0 shadow-sm mb-3 rounded-4 overflow-hidden bg-body-tertiary">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-bold py-3 px-4 bg-body-tertiary text-body" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $index }}" 
                                            aria-expanded="false">
                                        <span class="text-primary me-2">{{ $index + 1 }}.</span> {{ $alur['judul'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#accordionSOP">
                                    <!-- bg-white -> bg-body -->
                                    <div class="accordion-body p-0 bg-body">
                                        <div class="mermaid-outer border-top border-secondary-subtle">
                                            <div class="mermaid-inner">
                                                <div class="mermaid-container" data-code="{{ $alur['kode'] }}">
                                                    <div class="text-body-tertiary small py-3 text-center">Memuat alur...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                {{-- Catatan Pengecualian BHP --}}
            <div class="mt-4 p-3 rounded-4 border-0 shadow-sm bg-body-tertiary">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                    <small class="text-body-secondary" style="line-height: 1.5;">
                        <strong>Catatan:</strong> Kerusakan pada barang habis pakai (BHP) dikecualikan dari prosedur penggantian barang rusak.
                    </small>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    // Fungsi untuk mendapatkan tema Mermaid berdasarkan atribut data-bs-theme
    function getMermaidTheme() {
        const theme = document.documentElement.getAttribute('data-bs-theme');
        return (theme === 'dark') ? 'dark' : 'neutral';
    }

   mermaid.initialize({ 
    // ... setting lainnya
    flowchart: { 
        htmlLabels: true, 
        curve: 'stepAfter' // Pastikan ini 'stepAfter' untuk mode siku
    }
});

    document.addEventListener('DOMContentLoaded', function() {
        const accordionSOP = document.getElementById('accordionSOP');

        if (accordionSOP) {
            accordionSOP.addEventListener('shown.bs.collapse', function (event) {
                const target = event.target;
                const container = target.querySelector('.mermaid-container');
                const code = container.getAttribute('data-code');

                if (container && !container.classList.contains('rendered')) {
                    // Reset konten untuk render ulang dengan tema yang benar
                    container.innerHTML = `<div class="mermaid">${code}</div>`;
                    const mermaidDiv = container.querySelector('.mermaid');
                    
                    mermaid.init(undefined, mermaidDiv).then(() => {
                        container.classList.add('rendered');
                    });
                }
            });
        }
    });

    // Listener tambahan jika user mengganti tema saat accordion sudah terbuka
    window.addEventListener('click', function(e) {
        if (e.target.id === 'bd-theme' || e.target.closest('#bd-theme')) {
            // Beri sedikit jeda agar atribut data-bs-theme berubah dulu
            setTimeout(() => {
                location.reload(); // Cara paling aman agar Mermaid me-render ulang seluruh tema
            }, 100);
        }
    });
</script>

<style>
    /* Hapus inline background-color pada body agar ikut config global app.blade.php */
    
    .accordion-item { border-radius: 12px !important; }
    .accordion-button { border-radius: 12px !important; font-size: 0.9rem; border: none; }
    
    /* Adaptasi Accordion Aktif */
    .accordion-button:not(.collapsed) { 
        background-color: var(--bs-body-bg); 
        color: var(--bs-primary); 
        box-shadow: none; 
        border-bottom: 1px solid var(--bs-border-color); 
    }
    .accordion-button:focus { box-shadow: none; }

    /* Mermaid Layout Adaptif */
    .mermaid-outer { 
        width: 100%; 
        overflow-x: auto; 
        background: transparent; 
        -webkit-overflow-scrolling: touch; 
    }
    .mermaid-inner { display: inline-flex; min-width: 100%; justify-content: center; padding: 25px 15px; }
    .mermaid { width: 100%; max-width: 700px; margin: 0 auto; }
    
    /* Scrollbar Adaptif */
    .mermaid-outer::-webkit-scrollbar { height: 4px; }
    .mermaid-outer::-webkit-scrollbar-thumb { 
        background: var(--bs-secondary-bg); 
        border-radius: 10px; 
    }
</style>
@endsection