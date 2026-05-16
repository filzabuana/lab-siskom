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
        'deskripsi',     
        'catatan_lokasi',
        'foto_barang',
    ];

    protected $casts = [
        'tahun_perolehan' => 'integer',
        'jumlah_stok' => 'integer',
        'jumlah_rusak' => 'integer',
    ];

    /**
     * Relasi ke PeminjamanDetail (Tabel Transaksi Detail)
     * Digunakan untuk menghitung stok yang sedang keluar.
     */
    public function peminjamanDetails(): HasMany
    {
        // Hubungkan ke PeminjamanDetail menggunakan foreign key 'inventaris_id'
        return $this->hasMany(PeminjamanDetail::class, 'inventaris_id');
    }

    /**
     * Opsi Tambahan: Jika Anda masih ingin mengakses data Peminjaman (Header) secara langsung,
     * Anda bisa menggunakan hasManyThrough.
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