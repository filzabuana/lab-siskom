<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['peminjamans' => function($query) {
            $query->where('status', 'disetujui'); 
        }])->orderBy('name', 'asc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::withCount(['peminjamans' => function($query) {
            $query->where('status', 'disetujui');
        }])->findOrFail($id);
        
        $riwayat = Peminjaman::with('inventaris') 
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.show', compact('user', 'riwayat'));
    }

    /**
     * FORM CREATE: Mengambil semua role untuk checkbox
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $roles = Role::all(); 
        return view('admin.users.create', compact('roles'));
    }

    /**
     * STORE: Menyimpan user baru dengan multi-role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'roles'    => 'required|array', 
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Menggunakan Hash::make (lebih modern dari bcrypt)
            'is_admin' => $request->has('is_admin') ? 1 : 0,
        ]);

        // Menggunakan syncRoles agar lebih aman saat assign banyak role sekaligus
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('status', 'User baru berhasil didaftarkan!');
    }

    /**
     * EDIT: Menampilkan form edit user (jika Anda memisahkan form edit dari halaman profile)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * UPDATE ROLE: Memperbarui akses dan status admin
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($id);
        
        // Update status super admin
        $user->update([
            'is_admin' => $request->has('is_admin') ? 1 : 0,
        ]);

        // Sinkronisasi multi-role (menambah yang baru, menghapus yang tidak dicentang)
        $user->syncRoles($request->roles);

        return back()->with('success', 'Akses pengguna berhasil diperbarui!');
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);
        session()->put('impersonate', Auth::id());
        Auth::login($user);
        return redirect()->route('dashboard')->with('status', 'Mode impersonasi: ' . $user->name);
    }

    public function stopImpersonate()
    {
        if (session()->has('impersonate')) {
            $admin = User::find(session()->get('impersonate'));
            if ($admin) {
                auth()->login($admin);
                session()->forget('impersonate');
                return redirect()->route('admin.users.index')->with('status', 'Kembali sebagai Admin.');
            }
        }
        return redirect('/dashboard');
    }
}