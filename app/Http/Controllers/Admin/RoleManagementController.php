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
            // Mengambil deskripsi agar bisa tampil di form modal
            'permissions' => Permission::select('id', 'name', 'description')->get(),
        ]);
    }

    /**
     * Menyimpan Role Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|lowercase|alpha_dash|unique:roles,name',
            'description' => 'nullable|string|max:255', // Tambahkan validasi deskripsi
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'guard_name' => 'web'
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->back()->with('message', 'Role baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui Hak Akses & Deskripsi
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255', // Izinkan update deskripsi
            'permissions' => 'nullable|array',
        ]);

        // Update deskripsi jika ada
        if ($request->has('description')) {
            $role->update(['description' => $validated['description']]);
        }

        // Sinkronisasi permission berdasarkan nama
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->back()->with('message', 'Hak akses role berhasil diperbarui.');
    }
}