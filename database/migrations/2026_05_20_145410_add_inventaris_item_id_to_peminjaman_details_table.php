<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman_details', function (Blueprint $table) {
            // Menambahkan kolom foreign key setelah kolom inventaris_id
            // Dibuat nullable karena barang bertipe Bulk tidak memerlukan id sub-unit fisik
            $table->foreignId('inventaris_item_id')
                  ->nullable()
                  ->after('inventaris_id')
                  ->constrained('inventaris_items')
                  ->nullOnDelete(); // Jika record fisik dihapus demi pembersihan data, detail peminjaman tetap aman (menjadi null)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_details', function (Blueprint $table) {
            // Drop foreign key dan kolom jika migration di-rollback
            $table->dropForeign(['inventaris_item_id']);
            $table->dropColumn('inventaris_item_id');
        });
    }
};