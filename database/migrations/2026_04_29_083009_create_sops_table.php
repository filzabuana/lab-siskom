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
    Schema::create('sops', function (Blueprint $table) {
        $table->id();
        $table->string('judul');         // Nama SOP, misal: "SOP Peminjaman Alat"
        $table->string('slug');          // Untuk URL yang rapi, misal: website.com/sop/peminjaman-alat
        $table->string('kategori');      // Kategori: 'peminjaman', 'penelitian', atau 'umum'
        $table->text('deskripsi');       // Penjelasan singkat tentang SOP tersebut
        $table->string('file_pdf');      // Nama file PDF yang diupload
        $table->string('gambar_alur');   // (Opsional) Jika ingin menampilkan gambar flowchart alur
        $table->timestamps();            // Otomatis membuat kolom created_at dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sops');
    }

    
};
