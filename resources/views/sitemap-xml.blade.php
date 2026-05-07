{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<?xml-stylesheet type="text/xsl" href="{{ asset('sitemap.xsl') }}"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($staticPages as $page)
    <url>
        <loc>{{ url($page['url']) }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach

@foreach($sops as $sop)
    <url>
        <loc>{{ route('sop.show', $sop->slug) }}</loc>
        <lastmod>{{ $sop->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach

@foreach($items as $item)
    <url>
        <loc>{{ url('/katalog/' . $item->id) }}</loc>
        <lastmod>{{ $item->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>