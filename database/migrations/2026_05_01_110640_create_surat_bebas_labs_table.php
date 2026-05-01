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
    Schema::create('surat_bebas_labs', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('nim')->unique();
        $table->string('prodi'); 
        $table->string('email')->index();
        $table->timestamp('email_verified_at')->nullable();
        
        // Status pengajuan
        $table->enum('status', ['pending', 'verified_email', 'disetujui', 'ditolak'])->default('pending');
        
        $table->text('catatan_admin')->nullable(); // Alasan jika ditolak
        $table->string('file_surat')->nullable(); // Nama file PDF jika sudah disetujui
        $table->timestamps();
    });
}
};
