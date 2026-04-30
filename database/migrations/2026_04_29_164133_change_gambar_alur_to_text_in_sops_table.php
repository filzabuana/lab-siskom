<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sops', function (Blueprint $blueprint) {
            // Mengubah tipe data menjadi text agar muat kode Mermaid yang panjang
            $blueprint->text('gambar_alur')->change();
        });
    }

    public function down(): void
    {
        Schema::table('sops', function (Blueprint $blueprint) {
            $blueprint->string('gambar_alur')->change();
        });
    }
};