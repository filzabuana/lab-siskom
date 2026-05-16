<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Prunable; // Tambahkan ini

class Keranjang extends Model
{
    use HasFactory, Prunable; // Tambahkan Prunable di sini

    protected $table = 'keranjangs';

    protected $guarded = [];

    /**
     * Tentukan kriteria data yang dianggap sudah kedaluwarsa.
     * Dalam hal ini, data yang tidak disentuh selama 24 jam.
     */
    public function prunable()
    {
        return static::where('updated_at', '<=', now()->subHours(24));
    }

    // Mengetahui siapa pemilik keranjang ini
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Mengetahui alat apa yang ada di keranjang
    public function inventaris(): BelongsTo
    {
        return $this->belongsTo(Inventaris::class);
    }
}