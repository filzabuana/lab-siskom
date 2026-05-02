<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventaris;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        // Data Barang yang Bisa Dipinjam (IoT/Elektronika)
        Inventaris::create([
            'kode_barang' => 'LAB-IOT-001',
            'nama_aset' => 'ESP32 DevKit V1',
            'kategori' => 'Mikrokontroler',
            'laboratorium' => 'Lab Pemrograman dan Komputasi',
            'ruangan' => 'Ruang Workshop',
            'nup' => '1001',
            'merk' => 'Espressif',
            'tahun_perolehan' => 2024,
            'sumber_dana' => 'Hibah Kompetisi',
            'jumlah_stok' => 15,
            'kondisi' => 'Baik',
            'catatan_lokasi' => 'Laci A1',
            'tipe_peminjaman' => 'Bisa Dipinjam',
        ]);

        Inventaris::create([
            'kode_barang' => 'LAB-IOT-002',
            'nama_aset' => 'Raspberry Pi 4 Model B (8GB)',
            'kategori' => 'Single Board Computer',
            'laboratorium' => 'Lab Pemrograman dan Komputasi',
            'ruangan' => 'Ruang Workshop',
            'nup' => '1002',
            'merk' => 'Raspberry Pi Foundation',
            'tahun_perolehan' => 2024,
            'sumber_dana' => 'DIPA Untan',
            'jumlah_stok' => 5,
            'kondisi' => 'Baik',
            'catatan_lokasi' => 'Laci A2',
            'tipe_peminjaman' => 'Bisa Dipinjam',
        ]);

        // Data Barang yang Hanya di Lab (Statis)
        Inventaris::create([
            'kode_barang' => 'LAB-PC-001',
            'nama_aset' => 'PC Workstation i7 Gen 13',
            'kategori' => 'Komputer',
            'laboratorium' => 'Lab Pemrograman dan Komputasi',
            'ruangan' => 'Ruang Utama',
            'nup' => '2001',
            'merk' => 'Dell',
            'tahun_perolehan' => 2023,
            'sumber_dana' => 'DIPA Untan',
            'jumlah_stok' => 20,
            'kondisi' => 'Baik',
            'catatan_lokasi' => 'Meja User 1-20',
            'tipe_peminjaman' => 'Hanya di Lab',
        ]);

        Inventaris::create([
            'kode_barang' => 'LAB-PLC-001',
            'nama_aset' => 'PLC Trainer Omron CP1E',
            'kategori' => 'Trainer Kit',
            'laboratorium' => 'Lab Pemrograman dan Komputasi',
            'ruangan' => 'Ruang Utama',
            'nup' => '3001',
            'merk' => 'Omron',
            'tahun_perolehan' => 2022,
            'sumber_dana' => 'Pengadaan Lab',
            'jumlah_stok' => 2,
            'kondisi' => 'Baik',
            'catatan_lokasi' => 'Lemari Alat Utama',
            'tipe_peminjaman' => 'Hanya di Lab',
        ]);
    }
}