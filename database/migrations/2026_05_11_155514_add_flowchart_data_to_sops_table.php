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
    Schema::table('sops', function (Blueprint $table) {
        // Tipe JSON sangat cocok untuk menyimpan state Vue Flow (Nodes & Edges)
        $table->json('flowchart_data')->after('gambar_alur')->nullable();
    });
}

public function down(): void
{
    Schema::table('sops', function (Blueprint $table) {
        $table->dropColumn('flowchart_data');
    });
}
};
