@extends('site.layout.default', array('seoMeta' => CommonSite::getMetaSeo('TypeNew', $newType->id), 'model_name' => 'TypeNew', 'model_id' => $newType->id))

@section('title')
	@if($title = CommonSite::getMetaSeo('TypeNew', $newType->id)->title_site)
		{{ $title = $title }}
	@else
		{{ $title = $newType->name }}
	@endif
@stop
@section('content')

<?php
	$breadcrumb = array(
		// ['name' => 'Tin tức', 'link' => action('SiteNewsController@index')],
		['name' => $newType->name, 'link' => url($newType->slug)]
	);
?>
@include('site.common.bar', ['breadcrumb' => $breadcrumb, 'isH1' => 1, 'model_name' => 'TypeNew', 'model_id' => $newType->id])

<div class="row">
	<div class="col-sm-12">
		@include('site.News.highlightType', ['newTypeId' => $newType->id])
	</div>
	<div class="col-sm-8">
		<div class="box-main">
			
			@if(count($news) > 0)
				<div class="list">
					@foreach($news as $value)
					<div class="row list-item">
						<div class="col-xs-4 list-image">
							<a href="{{ action('SlugController@detailData', [$newType->slug, $value->slug]) }}">
								<img class="image_fb" src="{{ url(UPLOADIMG . '/news'.'/'. $value->id . '/' . $value->image_url) }}" />
							</a>
						</div>
						<div class="col-xs-8 list-text">
							<h2>
								<a href="{{ action('SlugController@detailData', [$newType->slug, $value->slug]) }}">
									{{ $value->title }}
								</a>
							</h2>
							@if(getDevice() == COMPUTER)
								<p>{{ getSapo($value->description, $value->sapo) }}</p>
							@endif
						</div>
					</div>
					@endforeach
				</div>
				@if($news->getTotal() >= FRONENDPAGINATE)
					@include('site.common.paginate', array('input' => $news))
				@endif
			@endif

		</div>
	</div>
	<div class="col-sm-4">
		<div class="side">
			@if(getDevice() == COMPUTER)
				@include('site.common.ads', array('adPosition' => POSITION_RIGHT, 'model_name' => 'TypeNew', 'model_id' => $newType->id, 'limit' => LIMIT))
			@endif
		</div>
	</div>
</div>

@include('site.common.gamebox', array('model_name' => 'TypeNew', 'model_id' => $newType->id))

@stop