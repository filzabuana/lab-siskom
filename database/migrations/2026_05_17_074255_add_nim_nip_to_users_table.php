<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom nim_nip ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim_nip', 30)->nullable()->unique()->after('email');
        });

        // 2. Logika Otomatisasi: Isi data NIM untuk mahasiswa yang sudah terlanjur terdaftar
        // Kita ambil semua user yang kolom nim_nip-nya masih kosong
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $emailPrefix = Str::before($user->email, '@');
            
            // Cek apakah email berawalan format NIM mahasiswa FMIPA UNTAN (H101...)
            if (preg_match('/^[Hh]\d+/', $emailPrefix)) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['nim_nip' => strtoupper($emailPrefix)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nim_nip');
        });
    }
};