<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Visual Lab Pemrograman & Komputasi</title>
    <style>
        /* ... (Style tetap sama seperti sebelumnya) ... */
        body { background: #f8fafc; font-family: 'Segoe UI', system-ui, sans-serif; }
        .tree-container { width: 100%; overflow-x: auto; padding: 50px 20px; text-align: center; }
        .tree ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; }
        .tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
        .tree li::before, .tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #cbd5e1; width: 50%; height: 20px; }
        .tree li::after { right: auto; left: 50%; border-left: 2px solid #cbd5e1; }
        .tree li:only-child::after, .tree li:only-child::before { display: none; }
        .tree li:only-child { padding-top: 0; }
        .tree li:first-child::before, .tree li:last-child::after { border: 0 none; }
        .tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 5px 0 0; }
        .tree li:first-child::after { border-radius: 5px 0 0 0; }
        .tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #cbd5e1; width: 0; height: 20px; }
        .tree li a { border: 2px solid #cbd5e1; padding: 10px 15px; text-decoration: none; color: #334155; font-size: 11px; display: inline-block; border-radius: 8px; background: white; transition: all 0.3s; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .tree li a:hover { background: #3b82f6 !important; color: white !important; border-color: #2563eb !important; transform: translateY(-3px); }
        .root-node { background: #1e293b !important; color: white !important; border-color: #0f172a !important; text-transform: uppercase; }
        .category-node { background: #eff6ff !important; border-color: #3b82f6 !important; color: #1e40af !important; text-transform: uppercase; font-size: 10px !important; }
        .system-node { background: #f0fdf4 !important; border-color: #22c55e !important; color: #166534 !important; }
    </style>
</head>
<body>

<div class="tree-container">
    <div class="tree">
        <ul>
            <li>
                <a href="/" class="root-node">LAB PEMROGRAMAN &amp; KOMPUTASI</a>
                <ul>
                    <!-- Cabang Profil -->
                    <li><a href="/about" class="category-node">Profil Lab</a></li>

                    <!-- Cabang Inventaris (Otomatis Database) -->
                    <li>
                        <a href="/katalog" class="category-node">Katalog Alat</a>
                        <ul>
                            @foreach($items->take(10) as $item) {{-- take(10) agar tidak terlalu lebar --}}
                                <li>
                                    <a href="{{ url('/katalog/' . $item->id) }}">{{ $item->nama_aset }}</a>
                                </li>
                            @endforeach
                            @if($items->count() > 10)
                                <li><a href="/katalog">... Lainnya</a></li>
                            @endif
                        </ul>
                    </li>

                    <!-- Cabang SOP (Otomatis Database) -->
                    <li>
                        <a href="/sop" class="category-node">SOP</a>
                        <ul>
                            @foreach($sops as $sop)
                                <li>
                                    <a href="{{ route('sop.show', $sop->slug) }}">{{ $sop->judul }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <!-- Cabang Layanan & Sistem -->
                    <li>
                        <a href="#" class="category-node">Sistem & Akses</a>
                        <ul>
                            <li><a href="/bebas-lab" class="system-node">Bebas Lab</a></li>
                            @foreach($pages as $page)
                                <li>
                                    <a href="{{ url($page['url']) }}">{{ $page['nama'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>

</body>
</html>