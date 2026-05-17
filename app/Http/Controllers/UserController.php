<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * INDEX: List semua user untuk manajemen laboratorium.
     */
    public function index()
    {
        $users = User::with('roles')
            ->withCount(['peminjamans' => function($query) {
                $query->where('status', 'disetujui'); 
            }])
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($user) {
                return [
                    'id'                => $user->id,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'avatar'            => $user->avatar,
                    'is_admin'          => (bool) $user->is_admin,
                    'roles_list'        => $user->getRoleNames(), 
                    'peminjamans_count' => $user->peminjamans_count,
                    'bebas_lab'         => (bool) $user->bebas_lab_status, 
                ];
            });

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * CREATE: Menampilkan form registrasi user baru (SOLUSI ERROR 500)
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::all()->pluck('name')
        ]);
    }

    /**
     * STORE: Mendaftarkan user baru ke database
     */
    public function store(Request $request)
    {
        // 1. Intersepsi input email: Paksa menjadi lowercase sebelum masuk ke validasi 'unique' atau 'lowercase'
        if ($request->has('email')) {
            $request->merge([
                'email' => strtolower($request->email)
            ]);
        }

        // 2. Jalankan validasi pada data yang telah disinkronkan
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|lowercase|unique:users,email',
            'password' => 'required|min:8',
            'roles'    => 'array', 
        ]);

        // 3. Simpan ke database dengan email yang dipastikan bersih dari huruf kapital
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'is_admin'          => $request->is_admin ? 1 : 0,
            'email_verified_at' => now(), // Memastikan rute profile langsung terbuka tanpa hambatan middleware verified
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('admin.users.index')
            ->with('message', 'User ' . $user->name . ' berhasil ditambahkan.');
    }

    /**
     * SHOW: Detail otoritas dan riwayat peminjaman user.
     */
    public function show(User $user)
    {
        $user->loadCount(['peminjamans' => function($query) {
            $query->where('status', 'disetujui');
        }]);

        $riwayat = Peminjaman::with('inventaris') 
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Users/Show', [
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'avatar'            => $user->avatar,
                'is_admin'          => (bool) $user->is_admin,
                'roles_list'        => $user->getRoleNames(),
                'peminjamans_count' => $user->peminjamans_count,
                'bebas_lab'         => (bool) $user->bebas_lab_status,
            ],
            'riwayat'        => $riwayat,
            'availableRoles' => Role::all()->pluck('name') 
        ]);
    }

    /**
     * UPDATE ROLE: Update otoritas user
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
        ]);

        $user->update([
            'is_admin' => $request->is_admin ? 1 : 0,
        ]);

        $user->syncRoles($request->roles ?? []);

        return back()->with('message', 'Otoritas ' . $user->name . ' berhasil diperbarui!');
    }

    /**
     * IMPERSONATION: Bertindak sebagai user lain
     */
    public function impersonate(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('message', 'Anda sudah login sebagai diri sendiri.');
        }

        session()->put('impersonate', Auth::id());
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('message', 'Sekarang bertindak sebagai: ' . $user->name);
    }

    /**
     * STOP IMPERSONATE: Kembali ke akun Admin
     */
    public function stopImpersonate()
    {
        if (session()->has('impersonate')) {
            $adminId = session()->get('impersonate');
            $admin = User::find($adminId);
            
            if ($admin) {
                session()->forget('impersonate');
                Auth::login($admin);
                session()->save(); 
                
                return redirect()->route('admin.users.index')
                    ->with('message', 'Kembali sebagai: ' . $admin->name);
            }
        }
        
        return redirect('/dashboard');
    }

    /**
     * DESTROY: Hapus user
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('message', 'User ' . $userName . ' telah dihapus.');
    }
}