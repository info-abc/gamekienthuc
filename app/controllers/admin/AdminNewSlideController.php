<?php

class AdminNewSlideController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$inputNew = AdminNew::where('type', ACTIVE)
			->where('status', APPROVE)
			// ->where('status', '!=', SCRATCH_PAPER)
			// ->where('status', '!=', REJECT)
			// ->where('status', '!=', SEND)
			// ->where('status', '!=', BACK)
			// ->where('status', '!=', NO_APPROVE)
			// ->orderBy('highlight', 'desc')
			->orderBy('start_date', 'desc')
			->orderBy('id', 'desc')->paginate(PAGINATE);
		return View::make('admin.news_slide.index')->with(compact('inputNew'));
	}

	public function search()
	{
		$input = Input::all();
		$input['type'] = ACTIVE;
		$inputNew = NewsManager::searchNews($input);
		return View::make('admin.news_slide.index')->with(compact('inputNew'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.news_slide.create');
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// $rules = NewsManager::getRuleByType(ACTIVE);
		$rules = ['image_urls' => 'not_empty'];
		$input = Input::except('_token');
		$validator = Validator::make($input,$rules);
		if($validator->fails()) {
			return Redirect::action('AdminNewSlideController@create')
	            ->withErrors($validator)
	            ->withInput(Input::except('_token'));
        } else {
        	//create news
        	$inputNews = Input::only('type_new_id', 'title', 
        		'description','start_date', 
        		'weight_number', 'position', 'sapo', 'sapo_text', 
        		'status', 'is_hot', 'author', 
        		'author_money', 'highlight', 'sensitive');
        	if($inputNews['start_date'] == '') {
        		$inputNews['start_date'] = Carbon\Carbon::now();
        	}
        	// dd(Input::all());
        	$input = Input::all();
        	//insert role_id into news
			$inputNews['user_id'] = Auth::admin()->get()->id;
        	$inputNews['role_id'] = Auth::admin()->get()->role_id;
        	$inputNews['type'] = ACTIVE;
        	$inputNews['highlight'] = INACTIVE;
			$id = AdminNew::create($inputNews)->id;

			//upload image new
			$input['image_url'] = CommonSeo::uploadImage($id, UPLOADIMG, 'image_url',UPLOAD_NEWS);
			AdminNew::find($id)->update(['image_url' => $input['image_url']]);
			//upload image for new slide
			$listImage = $input['image_urls'];
			foreach ($listImage as $key => $value) {
				if ($value) {
					$path = UPLOAD_NEWS_SLIDE;
					$destinationPath = public_path().$path . '/' . $id;
					$filename = $value->getClientOriginalName();
					$uploadSuccess   =  $value->move($destinationPath, $filename);
					$slides['new_id'] = $id;
					$slides['image_url'] = $filename;
					$imageRelateId[] = NewSlide::create($slides)->id;
				}
			}
			//create history
			$history_id = CommonLog::insertHistory('AdminNew', $id);

			//insert log_edits: history_id, Auth::admin()->get()->id; editor_name, editor_time, editor_ip
			CommonLog::insertLogEdit('AdminNew', $id, $history_id, CREATE);

			// insert ceo
			CommonSeo::createSeo('AdminNew', $id, FOLDER_SEO_NEWS, $input['image_url']);
			// if (Input::get('type') == ACTIVE) {
				return Redirect::action('AdminNewSlideController@edit', $id);
			// }
			// return Redirect::action('AdminNewSlideController@index');
        }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$inputNew = AdminNew::find($id);
		$inputSeo = AdminSeo::where('model_id', $id)->where('model_name', 'AdminNew')->first();
		return View::make('admin.news_slide.edit')->with(compact('inputNew','inputSeo'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(!Admin::isSeo()){
			$rules = NewsManager::getRuleByType(ACTIVE);
			$input = Input::except('_token');
			$inputNews = Input::only('type_new_id', 'title', 'description','start_date',
	        		'weight_number', 'position', 'sapo', 'sapo_text', 
	        		'status', 'is_hot', 'author', 'author_money', 'sensitive');
			$validator = Validator::make($input,$rules);
			if($validator->fails()) {
				return Redirect::action('AdminNewSlideController@edit',$id)
		            ->withErrors($validator)
		            ->withInput($inputNews);
	        } else {
	        	//update News
	        
	        	if($inputNews['start_date'] == '') {
	        		$inputNews['start_date'] = Carbon\Carbon::now();
	        	}
	        	$inputNews['type'] = ACTIVE;
	        	$imageNews = AdminNew::find($id);
				$imageNews->update($inputNews);
				//update upload image
				$input['image_url'] = CommonSeo::uploadImage($id, UPLOADIMG, 'image_url',UPLOAD_NEWS,$imageNews->image_url);
				$imageNews->update(['image_url' => $input['image_url']] );
				NewsManager::uploadImageSlide(ACTIVE, $input, $id);
			}
        }
        	//create history
		$history_id = CommonLog::updateHistory('AdminNew', $id);
		//update log_edits: history_id, Auth::admin()->get()->id; editor_name, editor_time, editor_ip
		CommonLog::insertLogEdit('AdminNew', $id, $history_id, EDIT);
    	//update seo
		CommonSeo::updateSeo('AdminNew', $id, FOLDER_SEO_NEWS, $input['image_url']);
		if ($input['image_urls'][0]) {
			return Redirect::action('AdminNewSlideController@edit', $id);
		}
		return Redirect::action('AdminNewSlideController@index') ;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		AdminNew::find($id)->delete();
		return Redirect::action('AdminNewSlideController@index') ;
	}

	public function deleteImageSlide($newId, $id)
	{
		NewSlide::find($id)->delete();
		return Redirect::action('AdminNewSlideController@edit', $newId);
	}

	public function highLight($id)
	{
		$new = AdminNew::find($id);
		$typeId = $new->type_new_id;
		AdminNew::where('type_new_id', $typeId)->update(['highlight' => INACTIVE]);
		$new->update(['highlight' => ACTIVE]);
		return Redirect::action('AdminNewSlideController@index');
	}

}
