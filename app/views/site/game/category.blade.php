@extends('site.layout.default', array('seoMeta' => CommonSite::getMetaSeo('CategoryParent', $categoryParent->id), 'seoImage' => FOLDER_SEO_PARENT . '/' . $categoryParent->id))

@section('title')
	@if($title = CommonSite::getMetaSeo('CategoryParent', $categoryParent->id)->title_site)
		{{ $title= $title }}
	@else
		{{ $title = $categoryParent->name }}
	@endif
@stop

@section('content')

<?php
	$breadcrumb = array(
		['name' => 'Game ' . $categoryParent->name . ' hay nhất', 'link' => url($categoryParent->slug)]
	);
?>
@include('site.common.bar', ['breadcrumb' => $breadcrumb, 'isH1' => 1])

<div class="box">
	<h3>{{ $categoryParent->name }} hay nhất</h3>
	<?php
		$games = CommonGame::boxGameByCategoryParent($categoryParent);
		$count = ceil(count($games->get())/PAGINATE_BOXGAME);
	?>
	<div class="swiper-container">
		<div class="swiper-wrapper">
			@for($i = 0; $i < $count ; $i ++)
				<div class="swiper-slide boxgame">
					<div class="row">
					<?php
						$listGame = $games->orderBy('count_play', 'desc')->orderBy('start_date', 'desc')->take(PAGINATE_BOXGAME)->skip($i * PAGINATE_BOXGAME)->get();
					?>
						@foreach($listGame as $game)
							@include('site.game.gameitem', array('game' => $game))
						@endforeach
					</div>
				</div>
			@endfor
		</div>
		<div class="swiper-pagination"></div>
		<div class="boxgame-pagination">
			<a class="prev"><i class="fa fa-caret-left"></i> Trang trước</a>
			<div class="boxgame-pagenumber"><span class="numberPage">1</span>/{{ $count }}</div>
			<a class="next">Trang sau <i class="fa fa-caret-right"></i></a>
		</div>
	</div>
</div>

{{-- quang cao --}}
@include('site.common.ad', array('adPosition' => CHILD_PAGE_RELATION))

<div class="box">
	<h3>{{ $categoryParent->name }} mới nhất</h3>
	<?php
		$games = CommonGame::boxGameByCategoryParent($categoryParent);
		$count = ceil(count($games->get())/PAGINATE_BOXGAME);
	?>
	<div class="swiper-container">
		<div class="swiper-wrapper">
			@for($i = 0; $i < $count ; $i ++)
				<div class="swiper-slide boxgame">
					<div class="row">
					<?php
						$listGame = $games->orderBy('start_date', 'desc')->take(PAGINATE_BOXGAME)->skip($i * PAGINATE_BOXGAME)->get();
					?>
						@foreach($listGame as $game)
							@include('site.game.gameitem', array('game' => $game))
						@endforeach
					</div>
				</div>
			@endfor
		</div>
		<div class="swiper-pagination"></div>
		<div class="boxgame-pagination">
			<a class="prev"><i class="fa fa-caret-left"></i> Trang trước</a>
			<div class="boxgame-pagenumber"><span class="numberPage">1</span>/{{ $count }}</div>
			<a class="next">Trang sau <i class="fa fa-caret-right"></i></a>
		</div>
	</div>
</div>

@include('site.game.scriptbox')

@stop