<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{url()}}</loc>
        <lastmod>2016-03-15</lastmod>
		<changefreq>always</changefreq>
		<priority>1.0</priority>
    </url>
    @foreach(SiteMap::getTypeUrlSiteMap() as $type)
    <url>
    	<loc>{{ url().'/'.$type->slug }}</loc>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
    </url>
    @endforeach

    @foreach(SiteMap::getGameUrlSiteMap() as $game)
    <?php $urlGame = CommonGame::getUrlGame($game); ?>
	    <url>
	    	<loc>{{ $urlGame }}</loc>
			<lastmod>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $game->start_date)->format('Y-m-d') }}</lastmod>
			<changefreq>daily</changefreq>
			<priority>0.7</priority>
	    </url>
	@endforeach

	@foreach(SiteMap::getNewUrlSiteMap() as $new)
	<?php $typeSlug = TypeNew::find($new->type_new_id)->slug; ?>
	    <url>
	    	<loc>{{ url().'/'.$typeSlug.'/'.$new->slug }}</loc>
			<lastmod>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $new->start_date)->format('Y-m-d') }}</lastmod>
			<changefreq>daily</changefreq>
			<priority>0.7</priority>
	    </url>
	@endforeach
</urlset>
