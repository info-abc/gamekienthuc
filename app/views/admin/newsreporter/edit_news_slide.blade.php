@extends('admin.layout.default')

@section('title')
{{ $title='Cập nhật tin' }}
@stop

@section('content')

@include('admin.newsreporter.common')

<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			{{ Form::open(array('action' => array('NewsReporterController@update_news_slide', $inputNew->id), 'method' => 'PUT', 'files' => true)) }}
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
						<label>Hình tin ảnh</label>
						<div class="row">
							<div class="col-sm-6">
							   {{ Form::file('image_urls[]', array('id' => 'image_url', 'multiple' => true)) }}
							</div>
						</div>
						<br />
						@foreach(NewSlide::where('new_id', $inputNew->id)->get() as $keySlide => $valueSlide)
							<div class="row">
								<div class="col-xs-12">
									<img src="{{ url(UPLOAD_NEWS_SLIDE . '/' . $inputNew->id . '/' . $valueSlide->image_url) }}"  width="550px" style="margin-bottom: 10px; margin-top: 10px;" />
								</div>
							</div>
							<div class="row">
								<div class="col-xs-10">
									{{ Form::textarea('image_sapo[]', $valueSlide->sapo, array('placeholder' => 'Mô tả ngắn hình', 'maxlength' => 250, 'class' => 'textarea form-control', 'rows' => '4', 'style' => 'width: 550px;' )) }}
								</div>
								<div class="col-xs-2">
									<a href="{{ action('AdminNewSlideController@deleteImageSlide', [$inputNew->id, $valueSlide->id]) }}" class="btn btn-danger">Xóa</a>
								</div>
							</div>
						@endforeach
					</div>
					<div class="form-group">
						<label for="name">Chuyên mục tin</label>
						<div class="row">
							<div class="col-sm-6">
							   {{ Form::select('type_new_id', returnListReporter('TypeNew'), $inputNew->type_new_id,array('class' => 'form-control' )) }}
							 
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
