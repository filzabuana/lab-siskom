<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Sop;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * Tanda tangan perintah terminal.
     */
    protected $signature = 'sitemap:generate';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Generate file sitemap.xml otomatis untuk Lab Pemrograman & Komputasi UNTAN';

    /**
     * Eksekusi perintah.
     */
    public function handle()
    {
        $this->info('Sedang memproses pembuatan sitemap...');

        // 1. Inisialisasi Sitemap
        $sitemap = Sitemap::create();

        // 2. Tambahkan Halaman Statis
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->add(Url::create('/about')
            ->setPriority(0.7)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        $sitemap->add(Url::create('/sop')
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // 3. Tambahkan Halaman Dinamis (SOP) dari Database
        try {
            $semua_sop = Sop::all();
            foreach ($semua_sop as $data) {
                $sitemap->add(
                    Url::create("/sop/{$data->slug}") 
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            }
            $this->info('Berhasil menarik ' . $semua_sop->count() . ' data SOP.');
        } catch (\Exception $e) {
            $this->error('Gagal mengambil database: ' . $e->getMessage());
        }

        // 4. Simpan ke file sitemap.xml
        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        // 5. Menyisipkan baris XSL secara manual (Manipulasi String)
        if (File::exists($path)) {
            $content = File::get($path);
            $xslLine = '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>';
            
            // Mencari tag penutup instruksi XML pertama
            if (str_contains($content, '?>')) {
                $newContent = str_replace('?>', '?>' . PHP_EOL . $xslLine, $content);
                File::put($path, $newContent);
            }
        }

        $this->info('Selesai! File sitemap.xml berhasil diperbarui dengan tampilan XSL.');
    }
} // Penutup Class - Pastikan ini ada