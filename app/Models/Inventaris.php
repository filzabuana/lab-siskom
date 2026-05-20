<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'kode_barang',
        'nama_aset',
        'kategori',
        'merk',
        'nup',
        'ruangan',
        'tahun_perolehan',
        'jumlah_stok',   
        'jumlah_rusak',  
        'kondisi',
        'tipe_peminjaman',
        'is_serialized', // Ditambahkan untuk flag kontrol hibrida aset
        'deskripsi',     
        'catatan_lokasi',
        'foto_barang',
    ];

    protected $casts = [
        'tahun_perolehan' => 'integer',
        'jumlah_stok' => 'integer',
        'jumlah_rusak' => 'integer',
        'is_serialized' => 'boolean', // Cast otomatis ke true/false
    ];

    /**
     * ACCESSOR: Mengubah nilai output 'jumlah_stok' secara dinamis.
     * Jika alat dikelola per unit (is_serialized = true), stok dihitung real-time dari item berstatus tersedia.
     */
    public function getJumlahStokAttribute($value)
    {
        if ($this->is_serialized) {
            return $this->items()->where('status', 'tersedia')->count();
        }

        // Jika barang habis pakai (bulk), kembalikan nilai angka static bawaan tabel
        return $value;
    }

    /**
     * ACCESSOR: Mengubah nilai output 'jumlah_rusak' secara dinamis.
     * Jika alat dikelola per unit (is_serialized = true), jumlah rusak dihitung dari unit fisik bermasalah.
     */
    public function getJumlahRusakAttribute($value)
    {
        if ($this->is_serialized) {
            return $this->items()->where('status', 'rusak')->count();
        }

        return $value;
    }

    /**
     * Relasi ke InventarisItem (Unit Fisik Ber-ID Unik)
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventarisItem::class, 'inventaris_id');
    }

    /**
     * Relasi ke PeminjamanDetail (Tabel Transaksi Detail)
     * Digunakan untuk menghitung stok yang sedang keluar (khusus barang bulk).
     */
    public function peminjamanDetails(): HasMany
    {
        return $this->hasMany(PeminjamanDetail::class, 'inventaris_id');
    }

    /**
     * Mengakses data Peminjaman (Header) secara langsung melalui tabel detail
     */
    public function peminjaman()
    {
        return $this->hasManyThrough(
            Peminjaman::class, 
            PeminjamanDetail::class,
            'inventaris_id', // Foreign key di peminjaman_details
            'id',            // Foreign key di peminjamans (owner key)
            'id',            // Local key di inventaris
            'peminjaman_id'  // Local key di peminjaman_details
        );
    }
}