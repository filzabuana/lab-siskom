@extends('layouts.app')

@section('content')
    @if(Auth::user()->is_admin)
        @include('admin.dashboard')
    @else
        {{-- Jika nanti ada file khusus mahasiswa, bisa letakkan di folder 'user' --}}
        @include('user.dashboard') 
    @endif
@endsection