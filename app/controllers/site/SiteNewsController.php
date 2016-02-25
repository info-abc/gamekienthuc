<?php

class SiteNewsController extends SiteController {

	public function getNumberGamesDevice()
	{
		if(getDevice() == MOBILE) {
			$limit = LIMIT_HIGHTLIGHT_MOBILE;
		} else {
			$limit = LIMIT_HIGHTLIGHT_PC;
		}
		return $limit;
	}

	public function index()
	{
		$limit = $this->getNumberGamesDevice();
		$list = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url')
			->where('news.start_date', '<=', Carbon\Carbon::now())
			->where('type_news.status', ENABLED)
			->orderBy('news.start_date', 'desc')
			->orderBy('news.weight_number', 'asc')
			->limit($limit)
			->lists('id');
		$inputListNews = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url')
			->where('news.start_date', '<=', Carbon\Carbon::now())
			->where('type_news.status', ENABLED)
			->whereNotIn('news.id', $list)
			->orderBy('news.start_date', 'desc')
			->orderBy('news.weight_number', 'asc')
			// ->offset(4)
			// ->get();
			->paginate(FRONENDPAGINATE);
		return View::make('site.News.listNews')->with(compact('inputListNews'));
	}

	public function listNews($slug)
	{

		$newType = TypeNew::findBySlug($slug);
		$list = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url')
			->where('news.start_date', '<=', Carbon\Carbon::now())
			->where('type_news.status', ENABLED)
			->where('type_new_id', $newType->id)
			->orderBy('news.start_date', 'desc')
			->orderBy('news.weight_number', 'asc')
			->limit($limit)
			->lists('id');
		$news = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
					->select('news.*')
					->where('type_new_id', $newType->id)
					->where('start_date', '<=', Carbon\Carbon::now())
					->where('type_news.status', ENABLED)
					->whereNotIn('news.id', $list)
					->orderBy('news.start_date', 'desc')
					->orderBy('news.weight_number', 'asc')
					->orderBy('id', 'desc')
					->paginate(FRONENDPAGINATE);
		return View::make('site.News.showType')->with(compact('newType', 'news'));
	}

	public function showDetail($slugType, $slugNew)
	{
		$now = date('Y-m-d');
		$newType = TypeNew::findBySlug($slugType);
		$inputNew = AdminNew::findBySlug($slugNew);
		$input['count_view'] = getZero($inputNew->count_view) + 1;
		CommonNormal::update($inputNew->id, $input, 'AdminNew');
		$inputRelated = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
							->select('news.*')
							->where('type_news.status', ENABLED)
							->where('type_new_id', $inputNew->type_new_id)
							->where('start_date', '<=', $now)
							->orderBy(DB::raw('RAND()'))
							->limit(PAGINATE_RELATED)
							->get();
		$inputHot = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
						->select('news.*')
						->where('type_news.status', ENABLED)
						->where('type_new_id', $inputNew->type_new_id)
						->where('start_date', '<=', $now)
						->orderBy('count_view', 'desc')
						->limit(PAGINATE_RELATED)
						->get();
		return View::make('site.News.showNews')->with(compact('newType', 'inputNew', 'inputRelated', 'inputHot'));
	}
}
