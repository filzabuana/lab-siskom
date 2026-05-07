<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use App\Models\Inventaris; // Tambahkan ini
use Illuminate\Http\Request;

class SiteMapController extends Controller
{
    // Fungsi bantuan untuk list halaman statis agar konsisten
    private function getStaticPages()
    {
        return [
            ['url' => '/', 'nama' => 'Beranda'],
            ['url' => '/about', 'nama' => 'Profil Lab'],
            ['url' => '/sop', 'nama' => 'Daftar SOP'],
            ['url' => '/bebas-lab', 'nama' => 'Bebas Lab'],
            ['url' => '/katalog', 'nama' => 'Katalog Alat'],
        ];
    }

    public function index()
    {
        $sops = Sop::all();
        $items = Inventaris::all(); // Tambahkan inventaris agar terindeks Google
        $staticPages = $this->getStaticPages();

        return response()->view('sitemap-xml', [
            'sops' => $sops,
            'items' => $items,
            'staticPages' => $staticPages
        ])->header('Content-Type', 'text/xml');
    }

   public function visual()
{
    $sops = Sop::all();
    $items = Inventaris::all(); // Tambahkan ini
    
    // Filter halaman statis agar tidak duplikat dengan yang sudah ada di tree
    $pages = [
        ['url' => '/dashboard', 'nama' => 'DASHBOARD'],
    ];

    return view('sitemap-visual', compact('sops', 'items', 'pages'));
}
}