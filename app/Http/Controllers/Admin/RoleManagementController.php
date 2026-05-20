<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleManagementController extends Controller
{
    /**
     * Menampilkan daftar role beserta permission-nya
     */
    public function index()
    {
        return Inertia::render('Admin/Roles/Index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::select('id', 'name')->get(),
        ]);
    }

    /**
     * Menyimpan Role Baru dari Modal Tambah
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|lowercase|alpha_dash|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        // 1. Buat Master Role baru
        $role = Role::create([
            'name' => $validated['name']
        ]);

        // 2. Sinkronisasikan permission pilihan (mengambil ID berdasarkan nama permission)
        if (!empty($validated['permissions'])) {
            $permissionIds = Permission::whereIn('name', $validated['permissions'])->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        return redirect()->back()->with('success', 'Role baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui Hak Akses (Permissions) dari Role yang Sudah Ada
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            // Nama tidak diubah (disabled di frontend) demi keamanan integrity code referensi
            'permissions' => 'nullable|array',
        ]);

        // Sinkronisasi ulang permission terbaru (menghapus yang tidak dicentang, menambah yang dicentang)
        $permissionIds = Permission::whereIn('name', $validated['permissions'] ?? [])->pluck('id');
        $role->permissions()->sync($permissionIds);

        return redirect()->back()->with('success', 'Hak akses role berhasil diperbarui.');
    }
}