<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cara paling aman untuk ENUM di MySQL adalah menggunakan statement mentah
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'Sedang Dipinjam', 'Selesai', 'Ditolak', 'Dibatalkan') DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Jika rollback, kita kembalikan ke status awal (pastikan tidak ada data 'Dibatalkan' saat rollback)
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'Sedang Dipinjam', 'Selesai', 'Ditolak') DEFAULT 'Pending'");
    }
};