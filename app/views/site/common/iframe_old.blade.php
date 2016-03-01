{{ HTML::style('assets/css/bootstrap.min.css') }}
<style>
	.container {
	    padding-left: 5px;
	    padding-right: 5px;
	}
	.row {
	    margin-left: -5px;
	    margin-right: -5px;
	}
	.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
	    padding-left: 5px;
	    padding-right: 5px;
	}
	.kt-menu {
		background: #E7E7E7;
	    display: block;
	    padding-left: 0;
	    overflow: hidden;
	    margin: 0;
	}
	.kt-menu li {
		float: left;
	    list-style: outside none none;
	    padding: 10px 15px;
	}
	.kt-menu li:first-child {
	    color: #fff;
	    cursor: default;
	    background-color: #2B74A1;
	    padding-bottom: 5px;
	    padding-top: 10px;
	    border: none;
	}
	.kt-menu li a {
		color: #333;
    	text-decoration: none;
	}
	.kt-menu li:first-child a {
	    color: #fff;
	    font-weight: bold;
	    font-size: 18px;
	    text-transform: uppercase;
	}
	.kt-menu li.kt-type {
		padding-left: 10px;
    	padding-right: 0;
	}
	.kt-menu li.kt-type a {
		border-right: 1px solid #888;
    	padding-right: 10px;
	}
	.kt-menu li:last-child a {
		border-right: none;
	}
	@if(getDevice() == COMPUTER)
	.kt-boxgame {
	    background: #2B74A1;
	    margin: 0 auto;
	    width: 670px;
	    height: 430px;
	}
	@else
	.kt-boxgame {
		background: #2B74A1;
	    margin: 0 auto;
	    width: 100%;
	    height: auto;
	}
	@endif
	.kt-boxgame-right > .row {
		margin-bottom: 5px;
	}
	.kt-boxgame-left a,
	.kt-boxgame-right a {
		color: #fff;
		text-decoration: none;
	}
	.kt-boxgame-left {
		padding: 15px;
	}
	.kt-boxgame-left img {
		width: 100%;
		max-width: 100%;
		height: auto;
		display: block;
		border-radius: 5px;
		border: 3px solid #fff;
	}
	.kt-boxgame-left strong {
		display: block;
		font-weight: bold;
		font-size: 16px;
		color: #fff;
		margin-top: 5px;
		margin-bottom: 5px;
	}
	.kt-boxgame-left p {
	    text-align: justify;
	    color: #fff;
	    font-size: 13px;
	}
	.kt-boxgame-right {
		padding: 15px 5px;
	}
	.kt-boxgame-right-images img {
		width: 100%;
		max-width: 100%;
		height: auto;
		display: block;
		border-radius: 5px;
		border: 3px solid #fff;
	}
	.kt-boxgame-right-text {
		
	}
	.kt-boxgame-right-text strong {
		display: block;
	    font-weight: bold;
	    font-size: 14px;
	    color: #fff;
	    margin-top: 3px;
	    margin-bottom: 3px;
	}
	.kt-boxgame-right-text p {
	    color: #fff;
	    font-size: 12px;
	    line-height: 16px;
	}
	
</style>
<div class="kt-boxgame">
	<ul class="kt-menu">
		<li>
			<a href="{{ url('/') }}">Tin Game</a>
		</li>
		@foreach(Type::whereIn('id', array(6, 11, 4, 7, 9, 5))->get() as $value)
			<li class="kt-type">
				<a href="{{ url('/' . $value->slug) }}" target="_top">
					{{ ($value->name) }}
				</a>
			</li>
		@endforeach
	</ul>
	<div class="clearfix"></div>
	<div class="kt-content">
		<div class="row">
			<div class="col-xs-6">
				<div class="kt-boxgame-left">
					@if(!empty($dataFirst))
						<?php $url = CommonGame::getUrlGame($dataFirst); ?>
						<a href="{{ $url }}" target="_top">
							<img src="{{ url(UPLOAD_GAME_AVATAR . '/' .  $dataFirst->image_url) }}" alt="{{ $dataFirst->name }}" />
						</a>
						<strong><a href="{{ $url }}" target="_top">{{ limit_text($dataFirst->name, TEXTLENGH) }}</a></strong>
						<p>
							{{ limit_text(strip_tags($dataFirst->description), TEXTLENGH_DESCRIPTION) }}
						</p>
					@endif
				</div>
			</div>
			<div class="col-xs-6">
				<div class="kt-boxgame-right">
				@foreach($dataList as $key => $value)
					<?php $url = CommonGame::getUrlGame($value); ?>
					<div class="row">
						<div class="col-xs-4 kt-boxgame-right-images">
							<a href="{{ $url }}" target="_top">
								<img src="{{ url(UPLOAD_GAME_AVATAR . '/' .  $value->image_url) }}" alt="{{ $value->name }}" />
							</a>
						</div>
						<div class="col-xs-8 kt-boxgame-right-text">
							<strong><a href="{{ $url }}" target="_top">{{ limit_text($value->name, TEXTLENGH) }}</a></strong>
							<p>
								{{ limit_text(strip_tags($value->description), TEXTLENGH_DESCRIPTION_CODE) }}
							</p>
						</div>
					</div>
				@endforeach
				</div>
			</div>
		</div>
	</div>
</div>