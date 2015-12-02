<!DOCTYPE html>
<html>
	@include('site.common.header')
	<body>

		@include('site.common.menu')
		@include('site.common.topbar')

		<div class="container">
			<div class="row">

		  	@include('site.common.navbar')

			<div class="clearfix"></div>

			<div class="main">

				@include('site.common.ad', array('adPosition' => HEADER))

				@yield('content')

				@include('site.common.ad', array('adPosition' => Footer))

			</div>

			@include('site.common.footer')

			</div>
	  	</div>

		@if($script)
			{{ $script->footer_script }}
		@endif

		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : {{ APP_ID }},
		      xfbml      : true,
		      version    : 'v2.5'
		    });
		  };

		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/en_US/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>

	</body>
</html>