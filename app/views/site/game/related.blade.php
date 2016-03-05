@include('site.common.ad', array('adPosition' => POSITION_GAME_RELATED))
@include('site.common.ad', array('adPosition' => POSITION_MOBILE_GAME_RELATED))

@if($games = CommonGame::getRelateGame($parentId, $limit, $typeId))
<div class="box mobile">
	<h3>Game khác</h3>
	<div class="row">
		@foreach($games as $game)
			@include('site.game.gameitem', array('game' => $game))
		@endforeach
	</div>
</div>
@endif