<!DOCTYPE html>
<html>
	@include('site.common.header')
	<body>

		{{-- HTML::style('assets/css/font-awesome.min.css') --}}
		{{-- HTML::style('assets/css/bootstrap.min.css') --}}
		{{-- HTML::style('assets/css/style.css') --}}

		@include('site.common.style')

		{{ HTML::script('assets/js/jquery-2.1.4.min.js') }}
		{{ HTML::script('assets/js/bootstrap.min.js') }}
		{{ HTML::script('assets/js/script.js') }}

		<div class="container">
			<div class="row">

				<div class="main">

					@include('site.common.top')

					@yield('content')

				</div>

			</div>
	  	</div>

	  	<div class="container">
			<div class="row">

				@if(isset($model_name))
					@if(getDevice() == MOBILE)
						@include('site.common.ads', array('adPosition' => POSITION_MOBILE_FOOTER, 'model_name' => $model_name, 'model_id' => $model_id))
					@endif
				@endif

  				@include('site.common.footer')
	  		</div>
	  	</div>
		
		@if(isset($model_name))
			@if(getDevice() == COMPUTER)
				<div class="sticky_left sticky_column" id="ScrollLeft" data-top="110">
					@include('site.common.ads', array('adPosition' => POSITION_STICKY_LEFT, 'model_name' => $model_name, 'model_id' => $model_id))
				</div>
				<div class="sticky_right sticky_column" id="ScrollRight" data-top="110">
					@include('site.common.ads', array('adPosition' => POSITION_STICKY_RIGHT, 'model_name' => $model_name, 'model_id' => $model_id))
				</div>
				<script type="text/javascript">
					var scrollads_width = 160;
					checkPos($(window).width());
					$(function () {
						//checkPos($(window).width());
						$(window).resize(function () {
							checkPos($(window).width());
						});
					});
					function checkPos(windowWidth) {
						var posLeft = (windowWidth - 1000) / 2 - scrollads_width - 3;
						var posRight = (windowWidth - 1000) / 2 - scrollads_width + 1;
						if (windowWidth < 1300) {
							$('#ScrollRight').hide();
							$('#ScrollLeft').hide();
						} else {
							$('#ScrollRight').show();
							$('#ScrollLeft').show();
							$("#ScrollRight").css({ top: 110, right: posRight, position: "absolute",display:"block" });
							$("#ScrollLeft").css({ top: 110, left: posLeft, position: "absolute",display:"block" });
						}
						if (windowWidth < 1300)
						{
							$('.sticky_column').hide();
						}
						else
						{
							$('.sticky_column').show();
						}
					}
					$(document).scroll(function () {
						var scrollTop = $(document).scrollTop();
						$('#ScrollLeft').each(function () {
							var $ads = $(this);
							var parentTop = parseInt($ads.attr('data-top'));
							if (scrollTop > parentTop) {
								$ads.css('top', scrollTop - parentTop + parseInt($ads.attr('data-top')) + 10);
							}
							else {
								$ads.css('top', parseInt($ads.attr('data-top')));
							}
						});
						$('#ScrollRight').each(function () {
							var $ads = $(this);
							var parentTop = parseInt($ads.attr('data-top'));
							if (scrollTop > parentTop) {
								$ads.css('top', scrollTop - parentTop + parseInt($ads.attr('data-top')) + 10);
							}
							else {
								$ads.css('top', parseInt($ads.attr('data-top')));
							}
						});
					});
				</script>

			@endif
		@endif

	  	<div class="glass"></div>

		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.5&appId={{ APP_ID }}";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>

	</body>
</html>