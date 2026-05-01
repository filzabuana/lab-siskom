<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class SiteMapController extends Controller
{
    public function index()
    {
        $path = public_path('sitemap.xml');

        if (!File::exists($path)) {
            return "File sitemap.xml tidak ditemukan. Jalankan 'php artisan sitemap:generate' dulu.";
        }

        $xmlString = File::get($path);
        $xmlObject = simplexml_load_string($xmlString);
        
        $urls = [];
        foreach ($xmlObject->url as $url) {
            $urls[] = (string) $url->loc;
        }

        return view('sitemap-visual', compact('urls'));
    }
}