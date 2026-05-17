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
        Schema::table('posts', function (Blueprint $table) {
            // 1. Relasi ke tabel users (mencatat ID admin/PLP yang menulis)
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            
            // 2. Kategori terstruktur (Default awal ke 'Artikel')
            $table->string('category')->default('Artikel')->after('slug');
            
            // 3. Counter pembaca untuk melihat statistik minat mahasiswa
            $table->unsignedInteger('views_count')->default(0)->after('image');
            
            // 4. Fitur pin postingan agar informasi penting tidak tenggelam
            $table->boolean('is_pinned')->default(false)->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'category', 'views_count', 'is_pinned']);
        });
    }
};