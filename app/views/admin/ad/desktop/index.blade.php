@extends('admin.layout.default')

@section('title')
{{ $title='Quản lý quảng cáo trang chủ' }}
@stop

@section('content')
<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ action('AdHomeController@create') }}" class="btn btn-primary">Thêm mới quảng cáo trang chủ</a>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
	  <div class="box">
		<div class="box-header">
		  <h3 class="box-title">Danh sách quảng cáo trang chủ</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive no-padding">
		  <table class="table table-hover">
			<tr>
			  <th>ID</th>
			  <th>Tiêu đề</th>
			  <th>Vị trí</th>
			  <th>Status</th>
			  <th style="width:200px;">Action</th>
			</tr>
			@foreach($advertise as $value)
				<tr>
				  	<td>{{ $value->id }}</td>
				  	<td>{{ $value->title }}</td>
					<td>{{ AdCommon::getNamePositionClassAd($value->position, 'home') }}</td>
					<td>{{ getStatusAdvertise($value->status) }} </td>
					<td>
					<a href="{{  action('AdHomeController@edit', $value->id) }}" class="btn btn-primary">Sửa</a>
						{{ Form::open(array('method'=>'DELETE', 'action' => array('AdHomeController@destroy', $value->id), 'style' => 'display: inline-block;')) }}
							<button class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</button>
						{{ Form::close() }}
				  	</td>
				</tr>
			@endforeach
		  </table>
		</div>
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
	</div>
</div>

@stop

