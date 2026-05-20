<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InventarisItem extends Model
{
    use HasFactory;

    // Definisikan nama tabel secara eksplisit
    protected $table = 'inventaris_items';

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'inventaris_id',
        'uuid_code',
        'barcode_aset',
        'nomor_seri_pabrik',
        'status',
    ];

    /**
     * Otomatisasi generate UUID saat data item baru dibuat
     */
    protected static function booted()
    {
        static::creating(function ($item) {
            // Jika uuid_code belum diisi, otomatis buatkan UUID versi 4 yang acak dan aman
            if (empty($item->uuid_code)) {
                $item->uuid_code = (string) Str::uuid();
            }
        });
    }

    /**
     * RELASI (Belongs To): Unit fisik ini merujuk ke satu Katalog Inventaris utama
     */
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    /**
     * RELASI (Has Many): Melacak riwayat pemakaian on-the-spot di laboratorium
     */
    public function usageLogs()
    {
        return $this->hasMany(InventarisUsageLog::class, 'inventaris_item_id');
    }
    /**
     * RELASI (Has Many): Melacak riwayat peminjaman unit fisik ini
     */
    public function peminjamanDetails()
    {
        // Hubungkan ke PeminjamanDetail menggunakan foreign key 'inventaris_item_id'
        // (Pastikan nama kolom foreign key di tabel peminjaman_details Anda sesuai)
        return $this->hasMany(PeminjamanDetail::class, 'inventaris_item_id');
    }
}