@extends('admin.layout.default')

@section('title')
{{ $title='Quản lý loại tin' }}
@stop

@section('content')

<!-- inclue Search form 

-->
<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ action('NewsTypeController@create') }}" class="btn btn-primary">Thêm category</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
	  <div class="box">
		<div class="box-header">
		  <h3 class="box-title">Danh sách loại tin</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive no-padding">
		  <table class="table table-hover">
			<tr>
			  <th>ID</th>
			  <th>Tên thể loai</th>
			  <th style="width:200px;">&nbsp;</th>
			</tr>
			 @foreach($inputNewType as $newstype)
			<tr>
			  <td>{{ $newstype->id }}</td>
			  <td>{{ $newstype->name }}</td>
			  <td>
				<a href="{{ action('NewsTypeController@edit', $newstype->id) }}" class="btn btn-primary">Sửa</a>
				<button class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</button>
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

<div class="row">
	<div class="col-xs-12">
		<ul class="pagination">
		{{ $inputNewType->appends(Request::except('page'))->links() }}
		</ul>
	</div>
</div>

@stop

