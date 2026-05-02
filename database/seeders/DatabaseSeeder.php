<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Memanggil semua seeder dalam satu array
        $this->call([
            SopSeeder::class,
            InventarisSeeder::class,
        ]);

        // 2. Membuat akun uji coba (di luar array call)
        // Pastikan User factory tidak error, atau jika sudah ada user ini, 
        // Bapak bisa berikan komentar (comment out) jika tidak diperlukan lagi.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}