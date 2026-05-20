<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_detail_items', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan ke baris item detail peminjaman
            $table->foreignId('peminjaman_detail_id')
                  ->constrained('peminjaman_details')
                  ->onDelete('cascade');
            
            // Menghubungkan ke ID unik di tabel inventaris_items (unit fisik berkode/barcode)
            $table->foreignId('inventaris_item_id')
                  ->constrained('inventaris_items')
                  ->onDelete('restrict'); 
            
            // Fitur masa depan: mencatat kondisi barang per unit saat dipulangkan
            $table->string('kondisi_kembali')->nullable(); // misal: Bagus, Rusak, Hilang
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_detail_items');
    }
};