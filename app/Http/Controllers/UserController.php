<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil semua user (kecuali admin jika ingin dipisah)
        // withCount akan membuat atribut baru 'peminjamans_count' secara otomatis
        $users = User::withCount(['peminjamans' => function($query) {
            $query->where('status', 'disetujui'); // Hanya hitung alat yang masih di tangan
        }])->orderBy('name', 'asc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
{
    $user = User::findOrFail($id);
    
    // SANGAT PENTING: Tambahkan with('inventaris')
    $riwayat = Peminjaman::with('inventaris') 
        ->where('user_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.users.show', compact('user', 'riwayat'));
}
}