<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use Illuminate\Http\Request;

class SiteMapController extends Controller
{
    // Untuk sitemap.xml (Bot Google)
    public function index()
    {
        $sops = Sop::all();
        $staticUrls = [url('/'), url('/about'), url('/sop'), url('/bebas-lab')];

        return response()->view('sitemap-xml', [
            'sops' => $sops,
            'staticUrls' => $staticUrls
        ])->header('Content-Type', 'text/xml');
    }

    // Untuk /peta-situs (Visual untuk Manusia)
    public function visual()
    {
        // Ambil data langsung dari Database agar selalu otomatis
        $sops = Sop::all();
        
        // Daftar halaman statis lainnya
        $pages = [
            ['url' => '/bebas-lab', 'nama' => 'BEBAS LAB'],
            ['url' => '/dashboard', 'nama' => 'DASHBOARD'],
        ];

        return view('sitemap-visual', compact('sops', 'pages'));
    }
}