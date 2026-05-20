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
        Schema::create('inventaris_items', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel induk (Katalog dari aplikasi SKALA)
            $table->foreignId('inventaris_id')
                  ->constrained('inventaris')
                  ->onDelete('cascade'); // Jika katalog dihapus, unit fisik otomatis terhapus
            
            // UUID unik untuk keamanan QR Code saat di-scan
            $table->uuid('uuid_code')->unique();
            
            // Kode unik internal LAB SISKOM (Contoh: LAB-SISKOM-RSP-001)
            $table->string('barcode_aset')->unique();
            
            // Nomor seri bawaan pabrik (opsional, jika ada di box alat)
            $table->string('nomor_seri_pabrik')->nullable();
            
            // Status kondisi operasional spesifik per unit fisik alat
            $table->enum('status', [
                'tersedia', 
                'dipinjam', 
                'dipakai_di_lab', 
                'rusak', 
                'maintenance'
            ])->default('tersedia');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_items');
    }
};