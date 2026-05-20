<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanDetailItem extends Model
{
    // Mengizinkan kolom-kolom ini diisi via Eloquent::create()
    protected $fillable = [
        'peminjaman_detail_id',
        'inventaris_item_id',
        'kondisi_kembali',
    ];

    /**
     * Relasi balik ke PeminjamanDetail
     * Ditambahkan 'peminjaman_detail_id' secara eksplisit agar relasi Eloquent presisi
     */
    public function peminjamanDetail()
    {
        return $this->belongsTo(PeminjamanDetail::class, 'peminjaman_detail_id');
    }

    /**
     * Relasi ke InventarisItem (Data fisik unit/barcode alat di laboratorium)
     */
    public function inventarisItem()
    {
        return $this->belongsTo(InventarisItem::class, 'inventaris_item_id');
    }
}