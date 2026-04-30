<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sop;
use Illuminate\Support\Str;

class SopSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'judul' => 'SOP Peminjaman Alat Praktikum',
                'kategori' => 'Peminjaman',
                'deskripsi' => 'Prosedur resmi untuk meminjam alat laboratorium untuk keperluan praktikum mahasiswa.',
                'file_pdf' => 'sop-peminjaman-alat.pdf',
                'gambar_alur' => 'alur-peminjaman.png',
            ],
            [
                'judul' => 'SOP Pelayanan Surat Bebas Laboratorium',
                'kategori' => 'Surat Bebas Lab',
                'deskripsi' => 'Alur pengurusan surat keterangan bebas pinjam alat untuk syarat yudisium atau wisuda.',
                'file_pdf' => 'sop-bebas-lab.pdf',
                'gambar_alur' => 'alur-bebas-lab.png',
            ],
            [
                'judul' => 'SOP Penggunaan Ruang Komputasi',
                'kategori' => 'Umum',
                'deskripsi' => 'Aturan umum bagi mahasiswa dan pihak luar yang ingin menggunakan fasilitas ruang laboratorium.',
                'file_pdf' => 'sop-ruang-lab.pdf',
                'gambar_alur' => 'alur-ruang-umum.png',
            ],
        ];

        foreach ($data as $item) {
            Sop::create([
                'judul' => $item['judul'],
                'slug' => Str::slug($item['judul']),
                'kategori' => $item['kategori'],
                'deskripsi' => $item['deskripsi'],
                'file_pdf' => $item['file_pdf'],
                'gambar_alur' => $item['gambar_alur'],
            ]);
        }
    }
}