<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cara paling aman di MySQL untuk update ENUM
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('pending', 'disetujui', 'sedang dipinjam', 'selesai', 'ditolak') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ke semula jika rollback (hati-hati jika ada data 'sedang dipinjam' akan error)
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak', 'selesai') DEFAULT 'pending'");
    }
};