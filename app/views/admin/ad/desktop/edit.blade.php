@extends('admin.layout.default')

@section('title')
{{ $title='Sửa quảng cáo trang chủ' }}
@stop

@section('content')

<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ action('AdHomeController@index') }} " class="btn btn-success">Danh sách quảng cáo trang chủ</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			<!-- form start -->
			{{ Form::open(array('action' => array('AdHomeController@update', $advertise->id), 'method' => 'PUT')) }}
			{{ Form::hidden('is_mobile', IS_NOT_MOBILE) }}
			<div class="box-body">
				<div class="form-group">
					<label for="title">Tiều đề</label>
					<div class="row">
						<div class="col-sm-6">
						     {{ Form::text('title', $advertise->title , textParentCategory('Tiêu đề')) }}
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="name">Vị trí</label>
					<div class="row">
						<div class="col-sm-6">
						   {{ Form::select('position', AdCommon::getPositionClassAd('home'), $advertise->position) }}
						</div>
					</div>
				</div>
				<div class="form-group ad-adsense">
					<label for="name">Adsense</label>
					<div class="row">
						<div class="col-sm-6">
							{{ Form::textarea('adsense', $advertise->adsense, textParentCategory('code adsense')) }}
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="name">Status</label>
					<div class="row">
						<div class="col-sm-6">
						   {{ Form::select('status', [DISABLED => 'Ẩn', ENABLED => 'Hiển thị'], $advertise->status) }}
						</div>
					</div>
				</div>

			  <div class="box-footer">
				{{ Form::submit('Lưu lại', array('class' => 'btn btn-primary')) }}
			  </div>
			{{ Form::close() }}
		  </div>
		  <!-- /.box -->
		</div>
	</div>
</div>

@stop
