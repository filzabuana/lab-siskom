<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Impor DB untuk melakukan TRUNCATE

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan cache Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. TRUNCATE DATA LAMA (Mencegah permission double / usang)
        // Mematikan foreign key checks sejenak agar proses truncate tidak dicegat oleh MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Permission::truncate();
        Role::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Definisikan Permission dengan standar Kebab-Case (-) secara total
        $permissions = [
            'access-admin-area',     // Masuk ke dashboard admin/staff
            'request-bebas-lab',     // Untuk Mahasiswa (Alumni/Tingkat Akhir)
            'view-katalog-alat',     // Melihat daftar alat siap pinjam
            'view-riwayat-pinjam',   // Melihat history peminjaman personal
            'review-peminjaman',     // Menyetujui/menolak peminjaman (PLP/Kalab)
            'view-inventaris',       // Melihat daftar inventaris internal
            'manage-inventaris',     // CRUD alat, nup, kategori, lokasi, dll
            'manage-bebas-lab',      // Verifikasi & terbitkan surat bebas laboratorium
            'manage-posts',          // CRUD artikel blog / pengumuman lab
            'manage-sop',            // Manajemen Standar Operasional Prosedur
            'manage-users',          // Mengatur role & permission user lain
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 4. Buat Roles
        $roleMahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $roleAsisten   = Role::firstOrCreate(['name' => 'asisten_praktikum']);
        $roleDosen     = Role::firstOrCreate(['name' => 'dosen']);
        $roleKaleb     = Role::firstOrCreate(['name' => 'kepala_lab']);
        $rolePlp       = Role::firstOrCreate(['name' => 'plp']);

        // 5. Sinkronisasi Hak Akses Peran
        $roleMahasiswa->syncPermissions([
            'request-bebas-lab',
            'view-katalog-alat',
            'view-riwayat-pinjam',
        ]);

        $roleAsisten->syncPermissions([
            'request-bebas-lab',
            'view-katalog-alat',
            'view-riwayat-pinjam',
        ]);

        $roleDosen->syncPermissions([]);

        $roleKaleb->syncPermissions([
            'access-admin-area',
            'view-inventaris',
            'review-peminjaman',
            'manage-bebas-lab',
            'manage-posts',
            'manage-sop',
            'manage-users',
        ]);

        $rolePlp->syncPermissions([
            'access-admin-area',
            'view-inventaris',
            'manage-inventaris',
            'review-peminjaman',
            'manage-bebas-lab',
            'manage-posts',
            'manage-sop',
        ]);

        // 6. Update Akun Filza (PLP & Super Admin)
        $admin = User::updateOrCreate(
            ['email' => 'filzaputra@untan.ac.id'],
            [
                'name' => 'Filza Buana Putra',
                'is_admin' => 1, 
                'password' => Hash::make('SukaMengodong'),
            ]
        );

        $admin->syncRoles(['plp']);
    }
}