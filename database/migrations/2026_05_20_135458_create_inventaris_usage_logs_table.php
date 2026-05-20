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
        Schema::create('inventaris_usage_logs', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel unit fisik (Bukan ke katalog utama)
            $table->foreignId('inventaris_item_id')
                  ->constrained('inventaris_items')
                  ->onDelete('cascade');
                  
            // Relasi ke user / mahasiswa yang melakukan scan pakai
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Waktu mulai pakai (saat pertama kali scan QR)
            $table->timestamp('jam_mulai');
            
            // Waktu selesai pakai (nullable, akan terisi saat scan ulang untuk menyudahi)
            $table->timestamp('jam_selesai')->nullable();
            
            // Keperluan pemakaian di lab (Misal: Praktikum Komputasi, Garap Skripsi)
            $table->string('keperluan')->nullable();
            
            // Kondisi unit fisik sesaat setelah selesai digunakan
            $table->enum('kondisi_setelah_pakai', ['bagus', 'rusak'])->default('bagus');
            
            // Catatan tambahan jika ada trouble saat praktikum
            $table->text('catatan_kondisi')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_usage_logs');
    }
};