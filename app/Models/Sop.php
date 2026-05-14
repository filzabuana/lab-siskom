<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'judul', 
        'slug', 
        'kategori', 
        'deskripsi', 
        'file_pdf', 
        'flowchart_data' 
    ];

    /**
     * Casting kolom agar Laravel otomatis mengubah JSON menjadi Array PHP (dan sebaliknya).
     */
    protected $casts = [
        'flowchart_data' => 'array', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}