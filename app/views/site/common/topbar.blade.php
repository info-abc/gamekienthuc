<div class="top">
	<div class="container">
		<div class="row">
			<div class="menu-show"><a onclick="menushow()"><i class="fa fa-navicon"></i></a></div>
			<div class="logo">
				<a href="{{ url('/') }}"><img src="{{ url('/assets/images/logo.jpg') }}" alt="" /></a>
			</div>
			<div class="search">
				<a class="iconfacebook" href="#"><i class="fa fa-facebook"></i></a>
				<a class="icongoogleplus" ><i class="fa fa-google-plus"></i></a>
				<form action="{{ action('SearchGameController@index') }}">
					<input type="text" name="search" value="" title="search" placeholder="Tìm kiếm game" />
					<input type="submit" value="search" title="submit" />
				</form>
				<a id="iconseach" onclick="menushow()" class="menu_show_list"><i class="fa fa-search"></i></a>
				<a onclick="menushow()" class="menu_show_list"><i class="fa fa-navicon"></i></a>

			</div>
	 </div>
	</div>
</div>