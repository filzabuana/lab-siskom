<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Portal Operasional Lab</h2>
            <p class="text-body-secondary">Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Anda login sebagai Personel Laboratorium.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Monitoring SOP -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-book text-primary fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Dokumentasi SOP</h5>
                    </div>
                    <p class="text-body-secondary small">Lihat daftar prosedur operasional untuk membantu mahasiswa dalam praktikum atau penggunaan alat.</p>
                    <a href="/sop" class="btn btn-outline-primary rounded-pill px-4 btn-sm">Lihat Prosedur</a>
                </div>
            </div>
        </div>

        <!-- Informasi Akademik/Lab -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-info-circle text-info fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Informasi Sistem</h5>
                    </div>
                    <p class="text-body-secondary small">Sistem verifikasi Bebas Lab saat ini berjalan via jalur Email Institusi (NIM H...). Akun Anda digunakan untuk keperluan administratif internal.</p>
                    <span class="badge bg-info-subtle text-info rounded-pill px-3">Status Sistem: Normal</span>
                </div>
            </div>
        </div>
    </div>
</div>