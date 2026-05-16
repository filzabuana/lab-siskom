<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Kita hapus kolom yang sekarang sudah diwakili oleh tabel detail
            $table->dropForeign(['inventaris_id']); // Hapus foreign key-nya dulu
            $table->dropColumn(['inventaris_id', 'jumlah_pinjam']);
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('inventaris_id')->nullable()->constrained('inventaris');
            $table->integer('jumlah_pinjam')->nullable();
        });
    }
};