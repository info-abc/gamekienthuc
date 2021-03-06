<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu">
	  <li class="header">Menu</li>
	  <li class="active">
		<a href="#">
		  <i class="fa fa-dashboard"></i> <span>Dashboard</span>
		</a>
	  </li>
	  @if(Admin::isAdmin() || Admin::isSeo())
		<li class="treeview">
		  <a href="#">
			<i class="fa fa-list"></i> <span>Quản lý hiển thị</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li><a href="{{ action('CategoryParentController@index') }}"><i class="fa fa-circle-o"></i> Quản lý Menu</a></li>
			<li><a href="{{ action('CategoryParentController@contentIndex') }}"><i class="fa fa-circle-o"></i> Quản lý box hiển thị</a></li>
			@if(Admin::isAdmin())
			  <li><a href="{{ action('CategoryController@index') }}"><i class="fa fa-circle-o"></i> Quản lý kiểu game</a></li>
			@endif
			<li>
			  <a href="{{ action('GameTypeController@index') }}">
				  <i class="fa fa-gamepad"></i> <span>Quản lý thể loại game</span>
			  </a>
			</li>
		  </ul>
		</li>
	  @endif

	  	@if(!Admin::isReporter())
			<li>
				<a href="{{ action('AdminGameController@index') }}">
					<i class="fa fa-gamepad"></i> <span>Quản lý Game</span>
				</a>
			</li>
			<li>
				<a href="{{ action('AdminGameBoxController@index') }}">
					<i class="fa fa-gamepad"></i> <span>Quản lý Game Box nhúng</span>
				</a>
			</li>
	  	@endif

		@if(Admin::isAdmin() || Admin::isEditor())
		    <li class="treeview">
				<a href="#">
					<i class="fa fa-list"></i> <span>Quản lý nội dung</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ action('NewsTypeController@index') }}"><i class="fa fa-circle-o"></i> Quản thể loại tin</a></li>
					<li><a href="{{ action('NewsController@index') }}"><i class="fa fa-circle-o"></i> Quản lý tin thường</a></li>
					<li><a href="{{ action('AdminNewSlideController@index') }}"><i class="fa fa-circle-o"></i> Quản lý tin ảnh</a></li>
					<li><a href="{{ action('NewsIndexController@index') }}"><i class="fa fa-circle-o"></i> Quản lý tin trang chủ</a></li>
					<li><a href="{{ action('NewsReportController@index') }}"><i class="fa fa-circle-o"></i> Quản lý tin phóng viên ({{ NewsManager::countNewsReport() }})</a></li>
		  			<li><a href="{{ action('AdminPaginateController@index') }}"><i class="fa fa-circle-o"></i> Quản lý phân trang tin</a></li>
					<li><a href="{{ action('AdminSlideController@index') }}"><i class="fa fa-picture-o"></i> Quản lý Slider</a></li>
					<li><a href="{{ action('AdminExcelController@exportReporterForm') }}"><i class="fa fa-list"></i> Tải danh sách nhuận bút phóng viên</a></li>
					<li><a href="{{ action('AdminTagController@index') }}"><i class="fa fa-tags"></i> <span>Quản lý Tags</span></a></li>
				</ul>
		    </li>
	    @elseif(Admin::isSeo())
	    	<li><a href="{{ action('NewsController@index') }}"><i class="fa fa-list"></i> Quản lý tin</a></li>
	    @else
	    	<li><a href="{{ action('NewsReporterController@index') }}"><i class="fa fa-list"></i> Quản lý tin</a></li>
	    @endif

	  @if(Admin::isAdmin() || Admin::isEditor())
	  <li>
		<a href="{{ action('AdminGameController@statisticGame') }}">
		  <i class="fa fa-gamepad"></i> <span>Thống kê Game</span>
		</a>
	  </li>    
		 
	  @endif       
	  @if(Admin::isAdmin())
	  <li>
		<a href="{{ action('CommentController@index') }}">
		  <i class="fa fa-comments"></i> <span>Quản lý comment</span>
		</a>
	  </li>
	  @endif
	  @if(Admin::isAdmin() || Admin::isEditor())
	  <!-- <li>
		<a href="{{-- action('ScoreManagerController@index') --}}">
		  <i class="fa fa-comments"></i> <span>Quản lý điểm</span>
		</a>
	  </li> -->
	  @endif
	  @if(Admin::isAdmin() || Admin::isEditor())
	  <li class="treeview">
		<a href="#">
		  <i class="fa fa-newspaper-o"></i> <span>Quản lý góp ý báo lỗi</span>
		  <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		  <li><a href="{{ action('FeedbackController@index') }}">
			  <i class="fa fa-comment"></i> <span>Quản lý góp ý</span>
			</a>
		  </li>
		  <li>
			<a href="{{ action('FeedbackGameController@index') }}">
			  <i class="fa fa-comment"></i> <span>Quản lý báo lỗi game</span>
			</a>
		  </li>
		</ul>
	  @endif
	  @if(Admin::isAdmin())
	  <li>
		<a href="#">
			<i class="fa fa-newspaper-o"></i> <span>Quản lý quảng cáo</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<!-- <li><a href="{{-- action('AdvertiseController@index') --}}"><i class="fa fa-circle-o"></i>Header + Footer / Bên phải</a></li> -->
			<!-- @if(!Admin::isSeo()) -->
			<!-- <li><a href="{{-- action('AdvertiseController@indexChild') --}}"><i class="fa fa-circle-o"></i>Box hiển thị</a></li> -->
			<!-- @endif -->


			<li>

				<a href="#"><i class="fa fa-circle-o"></i>Quảng cáo trang chủ</a>
				<ul class="treeview-menu">
					<li><a href="{{ action('AdHomeController@index') }}"><i class="fa fa-circle-o"></i>Quản lý trang destop</a></li>
					<li><a href="{{ action('AdHomeMobileController@index') }}"><i class="fa fa-circle-o"></i>Quản lý trang mobile</a></li>
					<li><a href="{{ action('AdTypeMobileController@index') }}"><i class="fa fa-circle-o"></i>Quản lý type mobile</a></li>
				</ul>

			</li>

			<li>

				<a href="#"><i class="fa fa-circle-o"></i>Quảng cáo trang con</a>
				<ul class="treeview-menu">
					<li><a href="{{ action('AdNewDestopController@index') }}"><i class="fa fa-circle-o"></i>Quản lý trang destop</a></li>
					<li><a href="{{ action('AdNewMobileController@index') }}"><i class="fa fa-circle-o"></i>Quản lý trang mobile</a></li>
				</ul>

			</li>

			<li>
				<a href="#">
					<i class="fa fa-bars"></i>
					<span>Qc trang bài chi tiết</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>

				<ul class="treeview-menu">
					<li><a href="{{ action('AdPageDetailController@index') }}"><i class="fa fa-desktop"></i>Trang bài chi tiết desktop</a></li>
					<li><a href="{{ action('AdPageDetailMobileController@index') }}"><i class="fa fa-mobile"></i>Trang bài chi tiết mobile</a></li>
				</ul>
			</li>

			<li>
				<a href="#">
					<i class="fa fa-gamepad"></i>
					<span>Qc game play</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>

				<ul class="treeview-menu">
					<li><a href="{{ action('AdGamePlayDesktopController@index') }}"><i class="fa fa-desktop"></i>Qc game play desktop</a></li>
					<li><a href="{{ action('AdGamePlayMobileController@index') }}"><i class="fa fa-mobile"></i>Trang bài chi tiết mobile</a></li>
				</ul>
			</li>

			<li>
				<a href="#">
					<i class="fa fa-gamepad"></i>
					<span>Qc game mới nhất/hay nhất</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>

				<ul class="treeview-menu">
					<li><a href="{{ action('AdGameHotMostDesktopController@index') }}"><i class="fa fa-desktop"></i>mới nhất/hay nhất desktop</a></li>
					<li><a href="{{ action('AdGameHotMostMobileController@index') }}"><i class="fa fa-mobile"></i>mới nhất/hay nhất mobile</a></li>
				</ul>
			</li>

			<li>
				<a href="#">
					<i class="fa fa-gamepad"></i>
					<span>Quảng cáo game mini</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>

				<ul class="treeview-menu">
					<li><a href="{{ action('AdGameMiniDesktopController@index') }}"><i class="fa fa-desktop"></i>Quản lý game mini desktop</a></li>
					<li><a href="{{ action('AdGameMiniMobileController@index') }}"><i class="fa fa-mobile"></i>Quản lý game mini mobile</a></li>
				</ul>
			</li>

		  </ul>
	  </li>
	  @endif
	  @if(Admin::isAdmin() || Admin::isEditor())
	  <li>
		<a href="{{ action('UserController@index') }}">
		  <i class="fa fa-users"></i> <span>Quản lý users</span>
		</a>
	  </li>
	  @endif
	
	  @if(Admin::isAdmin() || Admin::isSeo())
	  <li class="treeview">
		<a href="#">
		  <i class="fa fa-cogs"></i> <span>Cấu hình chung</span>
		  <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
		  @if(Admin::isAdmin())
			<li><a href="{{ action('PolicyController@index') }}"><i class="fa fa-circle-o"></i> Chính sách</a></li>
		  @endif
		  @if(Admin::isAdmin() || Admin::isSeo())
		  <li><a href="{{ action('SeoController@index') }}"><i class="fa fa-circle-o"></i> Quản lý cấu hình SEO</a></li>
		  <li><a href="{{ action('AdminLogoController@index') }}"><i class="fa fa-circle-o"></i> Quản lý textlink logo</a></li>
		  @endif
		</ul>
	  </li>
	  @endif

	  @if(Admin::isAdmin())
	  <li>
		<a href="{{ action('ManagerController@index') }}">
		  <i class="fa fa-users"></i> <span>Quản lý thành viên</span>
		</a>
	  </li>
	  @endif
	  @if(Admin::isAdmin()|| Admin::isEditor())
	  <li>
		<a href="{{ action('ErrorsController@index') }}">
		  <i class="fa fa-lock"></i> <span>Quản lý Logs</span>
		</a>
	  </li>
	  @endif
	 <!--  @if(Admin::isAdmin())
	   <li>
		<a href="{{ action('RelationController@index') }}">
		  <i class="fa fa-users"></i> <span>Quản lý Relation</span>
		</a>
	  </li>
	  @endif -->
   
	</ul>
  </section>
  <!-- /.sidebar -->
</aside>