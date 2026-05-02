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
    Schema::create('inventaris', function (Blueprint $table) {
        $table->id();
        
        // Data Sinkronisasi Tim TIK
        $table->string('kode_barang')->unique();
        $table->string('nama_aset');
        $table->string('kategori'); // e.g. PC, IoT, Alat Ukur
        $table->string('laboratorium')->default('Lab Pemrograman dan Komputasi');
        $table->string('ruangan');
        $table->string('nup')->nullable(); // Nomor Urut Pendaftaran
        $table->string('merk')->nullable();
        $table->year('tahun_perolehan');
        $table->string('sumber_dana')->nullable();
        $table->integer('jumlah_stok')->default(0);
        $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
        $table->text('catatan_lokasi')->nullable();

        // Fitur Tambahan Lab Kita
        // 'Bisa Dipinjam' (ESP32/Raspi) vs 'Hanya di Lab' (PC/PLC)
        $table->enum('tipe_peminjaman', ['Bisa Dipinjam', 'Hanya di Lab'])->default('Hanya di Lab');
        
        $table->timestamps();
    });
}
};
