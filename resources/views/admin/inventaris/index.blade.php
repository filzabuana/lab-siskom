@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0">Inventaris Laboratorium</h2>
            <p class="text-secondary">Manajemen aset dan alat Lab Pemrograman & Komputasi</p>
        </div>
       <a href="{{ route('admin.inventaris.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Barang
       </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive p-4">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary opacity-75">
                        <th class="border-0">KODE</th>
                        <th class="border-0">NAMA ASET</th>
                        <th class="border-0">KATEGORI</th>
                        <th class="border-0">STOK</th>
                        <th class="border-0">TIPE AKSES</th>
                        <th class="border-0 text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semuaInventaris as $item)
                    <tr>
                        <td class="fw-bold text-primary">{{ $item->kode_barang }}</td>
                        <td>
                            <div class="fw-bold">{{ $item->nama_aset }}</div>
                            <small class="text-secondary">{{ $item->merk }} ({{ $item->tahun_perolehan }})</small>
                        </td>
                        <td><span class="badge bg-info-subtle text-info px-3">{{ $item->kategori }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->jumlah_stok + $item->jumlah_rusak }} Unit</div>
                            @if($item->jumlah_rusak > 0)
                                <small class="text-danger" style="font-size: 0.75rem;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $item->jumlah_rusak }} Rusak
                                </small>
                            @else
                                <small class="text-success" style="font-size: 0.7rem;">Ready</small>
                            @endif
                        </td>
                        <td>
                            @if($item->tipe_peminjaman == 'Bisa Dipinjam')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                    <i class="bi bi-house-door me-1"></i> Boleh Dipinjam
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">
                                    <i class="bi bi-lock-fill me-1"></i> Hanya di Lab
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.inventaris.show', $item->id) }}" 
                                class="btn btn-light btn-sm rounded-circle shadow-sm" 
                                title="Lihat Detail">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>

                                <a href="{{ route('admin.inventaris.edit', $item->id) }}" 
                                class="btn btn-light btn-sm rounded-circle shadow-sm" 
                                title="Edit Data">
                                    <i class="bi bi-pencil text-warning"></i>
                                </a>

                                <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="btn btn-light btn-sm rounded-circle shadow-sm btn-delete" 
                                            title="Hapus Barang"
                                            data-nama="{{ $item->nama_aset }}">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table { color: inherit; }
    .card { background-color: var(--bs-card-bg); }
    .btn-light { background-color: var(--bs-tertiary-bg); border: none; }
    .swal2-popup { font-family: 'Inter', sans-serif !important; border-radius: 20px !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('form');
                const namaAset = this.getAttribute('data-nama');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Aset "${namaAset}" akan dihapus dari database lab!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    background: 'var(--bs-body-bg)',
                    color: 'var(--bs-body-color)',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: 'var(--bs-body-bg)',
                color: 'var(--bs-body-color)',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif
    });
</script>
@endsection