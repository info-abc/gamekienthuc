@extends('site.layout.default', array('seoMeta' => CommonSite::getMetaSeo('CategoryParent', 1), 'seoImage' => FOLDER_SEO_PARENT . '/' . 1))

@section('title')
{{ $title= 'Game Android'}}
@stop

@section('content')

<div class="box">
	<h1>Game Android</h1>
	<div class="row">
		@foreach($inputGameandroi as $game)
			@include('site.game.gameitem', array('game' => $game))
		@endforeach
	</div>

	<div class="row">
		<div class="col-xs-12">
			<ul class="pagination">
			{{ $inputGameandroi->appends(Request::except('page'))->links() }}
			</ul>
		</div>
	</div>
</div>
<!-- game play many todo -->


@stop