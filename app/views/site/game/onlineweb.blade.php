@extends('site.layout.default', array('seoMeta' => CommonSite::getMetaSeo('Game', $game->id), 'seoImage' => FOLDER_SEO_GAME . '/' . $game->id, 'model_name' => 'Game', 'model_id' => $game->id))

@section('title')
	@if($title = CommonSite::getMetaSeo('Game', $game->id)->title_site)
		{{ $title= $title }}
	@else
		{{ $title = $game->name }}
	@endif
@stop

@section('content')

@include('site.game.breadcrumbgame', array('game' => $game, 'model_name' => 'Game', 'model_id' => $game->id))

<div class="box">

	<div class="playgame">
		<h1>Game {{ $game->name }}</h1>
		<div class="playbox">
			
			{{ CommonGame::getLinkGame($game) }}

			@if(getDevice() == COMPUTER)
				@include('site.common.ads', array('adPosition' => POSITION_PLAYGAME_SHARE, 'model_name' => 'Game', 'model_id' => $game->id))
			@endif

			<div class="social-box">
				@include('site.game.socialbox', array('id' => $game->id))
			</div>
		</div>
	</div>

	@if(getDevice() == COMPUTER)
		@include('site.common.ads', array('adPosition' => POSITION_INFO, 'model_name' => 'Game', 'model_id' => $game->id))
	@endif

	<div class="row">
		<div class="col-sm-8">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs gamenav" role="tablist">
				<li role="presentation" class="active"><a href="#gametab" aria-controls="gametab" role="tab" data-toggle="tab">Thông tin trò chơi</a></li>
				<li role="presentation"><a href="#gameerror" aria-controls="gameerror" role="tab" data-toggle="tab">Báo lỗi</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content gamecontent">
				<div role="tabpanel" class="tab-pane active" id="gametab">
					<div class="web">

						<div class="game_avatar">
							<img alt="{{ $game->name }}" src="{{ url(UPLOAD_GAME_AVATAR . '/' . $game->image_url) }}" />
						</div>
						<div class="game_title">

							<h2 class="title">{{ $game->name }}</h2>

							@include('site.common.rate', array('vote_average' => $game->vote_average))

							<p>{{ getZero($game->count_play) }} lượt chơi</p>

						</div>

						<div class="slideGame">
							@include('site.game.slide', array('slideId' => $game->slide_id))
						</div>

						<div class="detail">{{ $game->description }}</div>

					</div>

					@include('site.game.comment')
				</div>
				<div role="tabpanel" class="tab-pane" id="gameerror">
					<div class="gameerror">
						@include('site.game.errorgame', array('id' => $game->id))
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="gamecontent-right">
				@include('site.game.score', array('id' => $game->id))

				{{ $gametop }}
			</div>

			@if(getDevice() == COMPUTER)
				@include('site.common.ads', array('adPosition' => POSITION_INFO_RIGHT, 'model_name' => 'Game', 'model_id' => $game->id))
			@endif

		</div>
	</div>
</div>

@include('site.game.related', array('parentId' => $game->parent_id, 'limit' => GAME_RELATED_WEB, 'typeId' => $game->type_main, 'model_name' => 'Game', 'model_id' => $game->id))

@stop