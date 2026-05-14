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
    Schema::table('sops', function (Blueprint $table) {
        $table->dropColumn('gambar_alur');
    });
}

public function down()
{
    Schema::table('sops', function (Blueprint $table) {
        $table->json('gambar_alur')->nullable();
    });
}
};
