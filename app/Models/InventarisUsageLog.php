<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisUsageLog extends Model
{
    use HasFactory;

    protected $table = 'inventaris_usage_logs';

    protected $fillable = [
        'inventaris_item_id',
        'user_id',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'kondisi_setelah_pakai',
        'catatan_kondisi',
    ];

    // Mengubah string datetime dari database menjadi objek Carbon secara otomatis
    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];

    /**
     * RELASI (Belongs To): Log ini mencatat satu unit fisik barang yang spesifik
     */
    public function item()
    {
        return $this->belongsTo(InventarisItem::class, 'inventaris_item_id');
    }

    /**
     * RELASI (Belongs To): Log ini mencatat satu user (mahasiswa/dosen) yang memakai alat
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}