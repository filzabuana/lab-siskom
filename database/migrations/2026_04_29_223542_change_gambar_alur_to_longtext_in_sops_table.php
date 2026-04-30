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
        $table->longText('gambar_alur')->change();
    });
}

public function down(): void
{
    Schema::table('sops', function (Blueprint $table) {
        $table->string('gambar_alur')->change();
    });
}
};
