<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Visual Lab Pemrograman</title>
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
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
        .tree li a { border: 2px solid #cbd5e1; padding: 12px 18px; text-decoration: none; color: #334155; font-size: 13px; display: inline-block; border-radius: 8px; background: white; transition: all 0.3s; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-transform: uppercase; }
        .tree li a:hover { background: #3b82f6; color: white; border-color: #2563eb; transform: translateY(-3px); }
        .root-node { background: #1e293b !important; color: white !important; border-color: #0f172a !important; }
        .category-node { background: #eff6ff !important; border-color: #3b82f6 !important; color: #1e40af !important; }
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
                    <li><a href="/about" class="category-node">PROFIL LAB</a></li>

                    <!-- Cabang SOP (Otomatis dari XML) -->
                    <li>
                        <a href="/sop" class="category-node">STANDAR OPERASIONAL (SOP)</a>
                        <ul>
                            @foreach($urls as $url)
                                @if(str_contains($url, '/sop/'))
                                    @php
                                        $path = parse_url($url, PHP_URL_PATH);
                                        $slug = str_replace('/sop/', '', $path);
                                        $judul = str_replace('-', ' ', $slug);
                                    @endphp
                                    <li>
                                        <a href="{{ $path }}">{{ $judul }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <!-- Cabang Lainnya -->
                    <li>
                        <a href="#" class="category-node">HALAMAN LAIN</a>
                        <ul>
                            @foreach($urls as $url)
                                @php $path = parse_url($url, PHP_URL_PATH); @endphp
                                @if(!str_contains($url, '/sop/') && $path != '/' && $path != '/about' && $path != '/sop')
                                    <li>
                                        <a href="{{ $path }}">{{ str_replace('/', '', $path) }}</a>
                                    </li>
                                @endif
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