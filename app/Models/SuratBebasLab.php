<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratBebasLab extends Model
{
    use HasFactory;

    // Menentukan nama tabel (Opsional jika nama tabel jamak/plural)
    protected $table = 'surat_bebas_labs';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama',
        'nim',
        'prodi',
        'email',
        'email_verified_at',
        'status',
        'catatan_admin',
        'file_surat',
    ];
}