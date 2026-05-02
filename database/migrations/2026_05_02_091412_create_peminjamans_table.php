<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
        $table->integer('jumlah_pinjam');
        $table->date('tgl_pinjam');
        $table->date('tgl_kembali_rencana');
        $table->dateTime('tgl_kembali_aktual')->nullable();
        $table->text('keperluan')->nullable();
        
        // Status: pending, disetujui, ditolak, selesai (sudah dikembalikan)
        $table->enum('status', ['pending', 'disetujui', 'ditolak', 'selesai'])->default('pending');
        
        $table->text('catatan_admin')->nullable(); // Untuk alasan penolakan dsb.
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
