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
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_class_id')->constrained('course_classes')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade'); // Siapa yang buka absen
            $table->string('title'); // Contoh: Pertemuan 1 - Pengenalan Kabel
            $table->string('qr_token')->unique(); // Token untuk validasi QR
            $table->timestamp('expires_at')->nullable(); // Batas waktu QR valid
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
