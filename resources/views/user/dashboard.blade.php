<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Dashboard Mahasiswa</h2>
            <p class="text-body-secondary">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>.</p>
        </div>
    </div>

    <!-- Statistik Singkat: Dioptimalkan untuk Mobile (g-2 dan col-6) -->
    <div class="row g-2 g-md-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-2 p-md-3 h-100">
                <small class="text-secondary d-block" style="font-size: 0.75rem;">Sedang Dipinjam</small>
                <span class="fs-4 fw-bold text-primary">{{ $countPeminjamanAktif ?? 0 }}</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-2 p-md-3 h-100">
                <small class="text-secondary d-block" style="font-size: 0.75rem;">Menunggu Approval</small>
                <span class="fs-4 fw-bold text-warning">{{ $countPending ?? 0 }}</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-2 p-md-3 h-100">
                <small class="text-secondary d-block" style="font-size: 0.75rem;">Pinjaman Selesai</small>
                <span class="fs-4 fw-bold text-success">{{ $countTotal ?? 0 }}</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-2 p-md-3 h-100 d-flex flex-column justify-content-center align-items-center">
                <small class="text-secondary d-block mb-1" style="font-size: 0.75rem;">Bebas Lab</small>
                <span class="badge bg-success-subtle text-success rounded-pill w-100 py-1">Aktif</span>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <!-- Fitur 1: Peminjaman Alat -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-box-seam text-primary fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Peminjaman Alat</h5>
                    </div>
                    <p class="text-body-secondary small">Ajukan peminjaman alat untuk keperluan tugas akhir atau praktikum mandiri.</p>
                    <div class="d-flex flex-wrap gap-2 mt-auto">
                        <a href="{{ route('katalog.index') }}" class="btn btn-primary rounded-pill px-3 px-md-4 btn-sm fw-bold">Cari Alat</a>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary rounded-pill px-3 px-md-4 btn-sm fw-bold">Riwayat</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fitur 2: Bebas Lab (Aksen Warna Info) -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-info border-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-file-earmark-check text-info fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Bebas Lab (RSK)</h5>
                    </div>
                    <p class="text-body-secondary small">Pastikan tidak ada tanggungan alat sebelum mengajukan validasi yudisium.</p>
                    <a href="{{ route('bebas-lab.form') }}" class="btn btn-info text-white rounded-pill px-4 btn-sm fw-bold">Cek Status</a>
                </div>
            </div>
        </div>

        <!-- Fitur 3: Dokumentasi SOP (Fix Dark Mode) -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-journal-text text-warning fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Dokumentasi SOP</h5>
                    </div>
                    <p class="text-body-secondary small">Panduan penggunaan alat laboratorium dan prosedur keselamatan kerja.</p>
                    {{-- Ubah ke btn-warning tanpa outline agar teks tetap terbaca di dark mode --}}
                    <a href="{{ route('sop.index') }}" class="btn btn-warning rounded-pill px-4 btn-sm fw-bold text-dark shadow-sm">
                        <i class="bi bi-eye me-1"></i> Buka Dokumen
                    </a>
                </div>
            </div>
        </div>

        <!-- Fitit 4: Profil -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-person-gear text-secondary fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Pengaturan Akun</h5>
                    </div>
                    <p class="text-body-secondary small">Kelola informasi profil dan pastikan email institusi Anda aktif.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm fw-bold">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>
</div>