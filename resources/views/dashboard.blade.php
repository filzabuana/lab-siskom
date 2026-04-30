@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4 p-4">
            <h2 class="fw-bold">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white p-3 mb-3">
                        <h5>Kelola SOP</h5>
                        <p>Tambah atau edit prosedur laboratorium.</p>
                        <a href="/sop/tambah" class="btn btn-light btn-sm text-primary">Buka Form</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection