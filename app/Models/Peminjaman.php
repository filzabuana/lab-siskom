<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $guarded = [];

    // Relasi ke Mahasiswa yang meminjam
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke rincian alat yang dipinjam (Opsi B: Bisa disetujui sebagian)
    public function details(): HasMany
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }
}