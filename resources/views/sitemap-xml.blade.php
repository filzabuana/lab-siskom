{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<?xml-stylesheet type="text/xsl" href="{{ asset('sitemap.xsl') }}"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- URL Statis --}}
    @foreach($staticUrls as $url)
    <url>
        <loc>{{ $url }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- URL Dinamis (SOP) --}}
    @foreach($sops as $sop)
    <url>
        <loc>{{ route('sop.show', $sop->slug) }}</loc>
        <lastmod>{{ $sop->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>