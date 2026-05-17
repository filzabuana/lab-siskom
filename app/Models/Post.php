<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * Daftar Kategori Baku untuk Blog Laboratorium.
     * Ini digunakan sebagai acuan dropdown di Vue & validasi di Controller.
     */
    const CATEGORIES = ['Pengumuman', 'Tutorial', 'Kegiatan', 'Artikel'];

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     */
    protected $fillable = [
        'user_id',       // Tambahan: ID Penulis
        'title', 
        'slug', 
        'content', 
        'image', 
        'category',      // Tambahan: Kategori Blog
        'views_count',   // Tambahan: Hitungan View
        'is_published',
        'is_pinned'      // Tambahan: Status Pin Fitur Penting
    ];

    /**
     * Mengubah tipe data kolom saat diakses (Casting).
     * Memastikan boolean dibaca sebagai true/false asli di Vue/Inertia.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * Relasi ke Model User (One-to-Many).
     * Mengetahui siapa PLP/Admin yang memposting artikel ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}