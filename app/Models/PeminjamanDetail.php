<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanDetail extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_details';

    protected $guarded = [];

    // Kembali ke header peminjaman
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // Relasi ke alatnya
    public function inventaris(): BelongsTo
    {
        return $this->belongsTo(Inventaris::class);
    }
    public function inventarisItem(): BelongsTo
    {
        return $this->belongsTo(InventarisItem::class, 'inventaris_item_id');
    }
    public function detailItems()
    {
        return $this->hasMany(PeminjamanDetailItem::class, 'peminjaman_detail_id');
    }
}