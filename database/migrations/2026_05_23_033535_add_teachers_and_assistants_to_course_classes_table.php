<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_classes', function (Blueprint $blueprint) {
            // Kita asumsikan teacher_id sudah ada, jika belum silakan un-comment baris di bawah
            // $blueprint->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            // Tambahkan dosen kedua (nullable)
            $blueprint->foreignId('teacher2_id')
                ->after('teacher_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Tambahkan asisten pertama (nullable)
            $blueprint->foreignId('assistant_id')
                ->after('teacher2_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Tambahkan asisten kedua (nullable)
            $blueprint->foreignId('assistant2_id')
                ->after('assistant_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('course_classes', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['teacher2_id']);
            $blueprint->dropForeign(['assistant_id']);
            $blueprint->dropForeign(['assistant2_id']);
            $blueprint->dropColumn(['teacher2_id', 'assistant_id', 'assistant2_id']);
        });
    }
};