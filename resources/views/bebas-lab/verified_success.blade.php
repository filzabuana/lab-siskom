@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <!-- Icon Success -->
                    <div class="mb-4 text-success">
                        <i class="bi bi-patch-check-fill" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h2 class="fw-bold text-body">Email Berhasil Diverifikasi!</h2>
                    <p class="text-body-secondary mt-3">
                        Halo mahasiswa, terima kasih telah memverifikasi email Anda. 
                        Pengajuan Surat Bebas Lab Anda kini telah masuk ke sistem kami untuk diperiksa oleh Admin Laboratorium.
                    </p>

                    <div class="alert alert-info border-0 rounded-3 small mt-4 text-start">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Selanjutnya:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Admin akan mengecek riwayat peminjaman alat Anda.</li>
                            <li>Surat Bebas Lab (PDF) akan dikirimkan ke email ini jika semua urusan administrasi selesai.</li>
                            <li>Proses ini biasanya memakan waktu 1-2 hari kerja.</li>
                        </ul>
                    </div>

                    <div class="mt-5">
                        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-5 fw-bold">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection