@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen User</h4>
            <p class="text-secondary small">Daftar mahasiswa terdaftar di sistem Lab RSK</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nama Mahasiswa</th>
                        <th>Email</th>
                        <th class="text-center">Alat di Tangan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $user->name }}</div>
                            <small class="text-secondary">{{ $user->nim ?? 'NIM tidak terdata' }}</small>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            @if($user->peminjamans_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $user->peminjamans_count }} Alat</span>
                            @else
                                <span class="badge bg-light text-secondary rounded-pill border">Kosong</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection