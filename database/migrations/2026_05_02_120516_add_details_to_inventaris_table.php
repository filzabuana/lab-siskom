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
    Schema::table('inventaris', function (Blueprint $table) {
        // Menambah deskripsi untuk katalog mahasiswa
        $table->text('deskripsi')->nullable()->after('nama_aset');
        
        // Menambah kolom stok rusak agar bisa dipisah dari stok utama (jumlah_stok)
        $table->integer('jumlah_rusak')->default(0)->after('jumlah_stok');
    });
}

public function down()
{
    Schema::table('inventaris', function (Blueprint $table) {
        $table->dropColumn(['deskripsi', 'jumlah_rusak']);
    });
}
};
