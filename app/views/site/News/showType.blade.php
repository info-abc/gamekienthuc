@extends('site.layout.default')

@section('title')
{{ $title='Danh sách tin tức' }}
@stop

@section('content')

<div class="list">

	<div class="title_center">
		<h1>Danh sách tin tức của {{ $newType->name }}</h1>
	</div>

	@foreach($news as $value)
		<div class="list-item">
			<div class="list-image">
				<a href="{{ action('SiteNewsController@showDetail', [$newType->slug, $value->slug]) }}">
					<img class="image_fb" src="{{ url(UPLOADIMG . '/news'.'/'. $value->id . '/' . $value->image_url) }}" />
				</a>
			</div>
			<div class="list-text">
				<h3>
					<a href="{{ action('SiteNewsController@showDetail', [$newType->slug, $value->slug]) }}">
						[{{ $value->typeNew->name }}] {{ $value->title }}
					</a>
				</h3>
				<p>{{ limit_text(strip_tags($value->description), TEXTLENGH_DESCRIPTION) }}</p>
			</div>
		</div>
	@endforeach
</div>

@include('site.common.paginate', array('input' => $news))

@stop