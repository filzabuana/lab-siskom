<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'inventaris';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     * Pastikan semua kolom yang dikirim dari form ada di sini.
     *
     * @var array
     */
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

    /**
     * Casting tipe data kolom.
     * Berguna agar Laravel otomatis mengubah tipe data saat diakses.
     */
    protected $casts = [
        'tahun_perolehan' => 'integer',
        'jumlah_stok' => 'integer',
    ];
    public function peminjaman()
{
    // Satu jenis alat bisa dipinjam berkali-kali oleh orang yang berbeda
    return $this->hasMany(Peminjaman::class);
}
}