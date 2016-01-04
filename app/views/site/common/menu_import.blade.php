<link media="all" type="text/css" rel="stylesheet" href="/assets/css/font-awesome.min.css">
<link media="all" type="text/css" rel="stylesheet" href="/assets/css/style-import-menu.css">
<style type="text/css">
	div {
	    -webkit-transform: none !important;
	}
</style>

<script>
	function menushow() {
	    document.getElementById('cssmenu').style.display = 'block';
	}
	function menuhide() {
	    document.getElementById('cssmenu').style.display = 'none';
	}
</script>
<div class="menu-import">
	<div class="menu-button">
		<a onclick="menushow()" class="menu_show_list"><i class="fa fa-navicon"></i></a>
	</div>
	<div id="cssmenu" class="">
		<div class="search1">
			<form action="/tim-kiem-game">
				<input type="text" name="search" value="" title="search" placeholder="Tìm kiếm game">
				<input type="submit" value="search" title="submit">
			</form>
		</div>
		<ul>
			<li class="active"><a href="/" class="color1"><i class="fa fa-home"></i> <span>Trang chủ</span></a></li>
			<li class="has-sub"><a href="#" class="color2"><span>Game HTML5</span></a>
				<ul>
					<li class="even"><a href="/ban-gai"><span>Bạn gái</span></a></li>
					<li class="odd"><a href="/tri-tue"><span>Trí tuệ</span></a></li>
					<li class="even"><a href="/nau-an"><span>Nấu ăn</span></a></li>
					<li class="odd"><a href="/vui-nhon"><span>Vui nhộn </span></a></li>
					<li class="even"><a href="/chien-thuat"><span>Chiến thuật</span></a></li>
					<li class="odd"><a href="/dua-xe"><span>Đua xe</span></a></li>
					<li class="even"><a href="/hanh-dong"><span>Hành động</span></a></li>
					<li class="odd"><a href="/the-thao"><span>Thể thao</span></a></li>
					<li class="even"><a href="/thoi-trang"><span>Thời trang</span></a></li>
				</ul>
			</li>
			<li><a href="/game-android" class="color2"><span>Game Android</span></a></li>
		</ul>
		<div class="menu-hide"><a onclick="menuhide()"><i class="fa fa-times-circle-o"></i> Đóng lại</a></div>
	</div>
</div>

{{-- GA --}}
<?php
	if (Cache::has('script'.SEO_SCRIPT))
	{
	    $script = Cache::get('script'.SEO_SCRIPT);
	} else {
        $script = AdminSeo::where('model_name', SEO_SCRIPT)->first();
	    Cache::put('script'.SEO_SCRIPT, $script, CACHETIME);
	}
	if(isset($script)) {
        echo $script->header_script;
	}
?>
