<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    // Kolom mana saja yang boleh diisi manual
    protected $fillable = [
        'judul', 
        'slug', 
        'kategori', 
        'deskripsi', 
        'file_pdf', 
        'gambar_alur'
    ];
}