@extends('admin.layout.default')

@section('title')
{{ $title='Cập nhật tin' }}
@stop

@section('content')

@include('admin.newsreporter.common')

<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			{{ Form::open(array('action' => array('NewsReporterController@update', $inputNew->id), 'method' => 'PUT', 'files' => true)) }}
				<div class="box-body">
					<div class="form-group">
						<label for="title">Tiêu đề</label>
						<div class="row">
							<div class="col-sm-6">
							   {{ Form::text('title', $inputNew->title, textParentCategory('Tiêu đề tin')) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Tác giả (hiển thị cuối bài)</label>
						<div class="row">
							<div class="col-sm-6">
								{{ Form::text('author', $inputNew->author, textParentCategory('Tác giả')) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="name">Chuyên mục tin</label>
						<div class="row">
							<div class="col-sm-6">
							   {{  Form::select('type_new_id', returnListReporter('TypeNew'), $inputNew->type_new_id ,array('class' => 'form-control' )) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="image_url">Ảnh đại diện(640x410)</label>
						<div class="row">
							<div class="col-sm-6">
								{{ Form::file('image_url') }}
								<img class="image_fb" src="{{ url(UPLOADIMG . '/news'.'/'. $inputNew->id . '/' . $inputNew->image_url) }}" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Nguồn (hiển thị trước sapo)</label>
						<div class="row">
							<div class="col-sm-6">
								{{ Form::text('sapo_text', $inputNew->sapo_text, textParentCategory('Nguồn')) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="sapo">Mô tả ngắn</label>
						<div class="row">
							<div class="col-sm-12">
								 {{ Form::textarea('sapo', $inputNew->sapo, array('placeholder' => 'Mô tả ngắn', 'maxlength' => 250, 'rows' => 4, 'class' => 'form-control')) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="description">Nội dung tin</label>
						<div class="row">
							<div class="col-sm-12">	 
								{{ Form::textarea('description', $inputNew->description, array('class' => 'form-control', "rows"=>6, 'id' => 'editor1'  )) }}
							</div>
						</div>
					</div>
					
					{{ Form::hidden('status', SCRATCH_PAPER) }}
					
					<div class="row">
						<div class="col-sm-12">
							<hr />
							<h1>SEO META</h1>
							@include('admin.common.meta', array('inputSeo' => $inputSeo, 'pathToImageSeo' => UPLOADIMG . '/'.FOLDER_SEO_NEWS.'/'. $inputNew->id . '/'))
						</div>
					</div>
					<div class="box-footer">
						{{ Form::submit('Lưu lại', array('class' => 'btn btn-primary')) }}
					</div>
			  	</div>
		  	{{ Form::close() }}
		</div>
	</div>
</div>
@include('admin.common.ckeditor')
@stop
