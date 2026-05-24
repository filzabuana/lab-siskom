<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan cache Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. TRUNCATE DATA LAMA
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Permission::truncate();
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Definisikan SEMUA Permission
        $permissions = [
            // Dasar & Dashboard
            'access-admin-area'    => 'Memberikan akses masuk ke area dashboard administrasi.',
            
            // Peminjaman & Katalog
            'request-bebas-lab'    => 'Memungkinkan mahasiswa mengajukan surat keterangan bebas laboratorium.',
            'view-katalog-alat'    => 'Melihat daftar alat laboratorium yang tersedia untuk dipinjam.',
            'view-riwayat-pinjam'  => 'Melihat riwayat peminjaman alat secara personal.',
            'review-peminjaman'    => 'Menyetujui atau menolak pengajuan peminjaman alat (PLP/Kalab).',
            
            // Inventaris
            'view-inventaris'      => 'Melihat daftar inventaris barang secara internal.',
            'manage-inventaris'    => 'Menambah, mengedit, dan menghapus data inventaris alat.',
            
            // Manajemen Dokumen & Konten
            'manage-bebas-lab'     => 'Melakukan verifikasi dan menerbitkan surat bebas lab.',
            'manage-posts'         => 'Mengelola artikel berita atau pengumuman di website.',
            'manage-sop'           => 'Mengunggah dan mengelola dokumen Standar Operasional Prosedur.',
            
            // Sistem & User
            'manage-users'         => 'Mengatur hak akses (role & permission) untuk pengguna lain.',
            
            // Praktikum & Presensi
            'scan-presensi'        => 'Mengisi kehadiran praktikum melalui scan QR Code.',
            'create-presensi'      => 'Membuka sesi kehadiran dan menampilkan QR Code untuk discan.',
            'view-rekap-presensi'  => 'Melihat dan mengunduh laporan rekapitulasi kehadiran mahasiswa.',
            'import-mahasiswa'     => 'Mengunggah daftar mahasiswa ke dalam kelas praktikum via Excel.',    
            'view-all-attendance'  => 'Melihat semua monitoring praktikum (Khusus PLP/Kalab).',
            'manage-praktikum'     => 'Menambah, mengedit, dan menghapus kelas praktikum serta ploting pengajar.'
        ];

        foreach ($permissions as $name => $desc) {
            Permission::create([
                'name' => $name, 
                'description' => $desc
            ]);
        }

        // 4. Buat Roles
        $roleMahasiswa = Role::create(['name' => 'mahasiswa', 'description' => 'Pengguna umum (mahasiswa)']);
        $roleAsisten   = Role::create(['name' => 'asisten_praktikum', 'description' => 'Asisten praktikum']);
        $roleDosen     = Role::create(['name' => 'dosen', 'description' => 'Tenaga pengajar']);
        $rolePLP       = Role::create(['name' => 'plp', 'description' => 'Pranata Laboratorium Pendidikan']);
        $roleKalab     = Role::create(['name' => 'kepala_lab', 'description' => 'Pimpinan laboratorium']);

        // 5. SINKRONISASI PERMISSION KE ROLE

        // Mahasiswa
        $roleMahasiswa->syncPermissions([
            'request-bebas-lab', 
            'view-katalog-alat', 
            'view-riwayat-pinjam', 
            'scan-presensi'
        ]);

        // Asisten Praktikum
        $roleAsisten->syncPermissions([
            'access-admin-area',
            'request-bebas-lab',
            'view-katalog-alat',
            'view-riwayat-pinjam',
            'scan-presensi',
            'create-presensi',
            'view-rekap-presensi'
        ]);

        // Dosen
        $roleDosen->syncPermissions([
            'access-admin-area',
            'create-presensi',
            'view-rekap-presensi'
        ]);

        // PLP (Full Akses Operasional)
        $rolePLP->syncPermissions([
            'access-admin-area',
            'review-peminjaman',
            'view-inventaris',
            'manage-inventaris',
            'manage-bebas-lab',
            'manage-posts',
            'manage-sop',
            'manage-praktikum',
            'create-presensi',
            'view-rekap-presensi',
            'import-mahasiswa',
            'view-all-attendance'
        ]);

        // Kepala Lab (Full Akses Monitoring & Approval)
        $roleKalab->syncPermissions(Permission::all()); // Kalab biasanya punya akses ke semua

        // 6. Update Akun Utama
        $admin = User::updateOrCreate(
            ['email' => 'filzaputra@untan.ac.id'],
            [
                'name' => 'Filza Buana Putra',
                'is_admin' => 1, 
                'password' => Hash::make('SukaMengodong'),
                'nim_nip' => 'PLP001'
            ]
        );
        
        // Pastikan role disinkronkan (bisa lebih dari satu role jika diperlukan)
        $admin->syncRoles(['plp']);
    }
}