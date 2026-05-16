<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definisikan Role yang dibutuhkan
        $roles = [
            'mahasiswa',
            'asisten_praktikum',
            'plp',
            'kepala_lab',
            'dosen'
        ];

        // 2. Masukkan ke database
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 3. Daftarkan akun Anda (Filza) secara manual sebagai Super Admin
        // Ini penting agar saat login Google nanti, sistem mengenali Anda sebagai Admin
       $admin = User::updateOrCreate(
            ['email' => 'filzaputra@untan.ac.id'], // Email resmi UNTAN Anda
            [
                'name' => 'Filza Buana Putra',
                'is_admin' => 1, 
                'password' => Hash::make('SukaMengodong'), // Tetap isi untuk login manual
            ]
        );

        $admin->assignRole('plp');
    }
}