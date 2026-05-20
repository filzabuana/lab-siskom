<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan RoleSeeder pertama kali (karena user butuh role)
        $this->call([
            RoleSeeder::class,
            SopSeeder::class,
            InventarisSeeder::class,
            RoleAndPermissionSeeder::class,
        ]);

        // 2. Membuat akun uji coba jika belum ada
        // Menggunakan updateOrCreate agar tidak error jika dijalankan berkali-kali
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );
    }
}