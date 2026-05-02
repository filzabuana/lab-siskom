<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjamans'; 

    protected $guarded = [];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Inventaris (Alat)
    public function inventaris(): BelongsTo
    {
        // Pastikan kolom di tabel peminjamans adalah inventaris_id
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }
}