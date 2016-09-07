<?php

class SlugController extends SiteController {

	public function listData($slug)
	{
		$categoryParent = CategoryParent::findBySlug($slug);
		$type = Type::findBySlug($slug);
		$newType = TypeNew::findBySlug($slug);
		$tag = AdminTag::findBySlug($slug);
		if($categoryParent || $type) {
			return self::listGame($slug);
		}
		if($newType) {
			return self::listNews($slug);
		}
		if($tag) {
			return self::listTags($slug);
		}
		return Redirect::action('SiteController@returnPage404');
	}

	public function detailData($type, $slug)
	{
		$game = Game::findBySlug($slug);
		$inputNew = AdminNew::findBySlug($slug);
		//chua check type game
		if($game) {
			return self::detailGame($type, $slug);
		}
		//chua check type new
		if($inputNew) {
			return self::showDetail($type, $slug);
		}
		return Redirect::action('SiteController@returnPage404');
	}

	public function listTags($slug)
	{
		if (Cache::has('tag_'.$slug))
        {
            $tag = Cache::get('tag_'.$slug);
        } else {
            $tag = AdminTag::findBySlug($slug);
            Cache::put('tag_'.$slug, $tag, CACHETIME);
        }
        if($tag) {
			return View::make('site.tag.index')->with(compact('tag'));
		}
		return Redirect::action('SiteController@returnPage404');
	}

	public function listGame($slug)
	{
		if (Cache::has('categoryParent_'.$slug))
        {
            $categoryParent = Cache::get('categoryParent_'.$slug);
        } else {
            $categoryParent = CategoryParent::findBySlug($slug);
            Cache::put('categoryParent_'.$slug, $categoryParent, CACHETIME);
        }
		if (Cache::has('type_'.$slug))
        {
            $type = Cache::get('type_'.$slug);
        } else {
            $type = Type::findBySlug($slug);
            Cache::put('type_'.$slug, $type, CACHETIME);
        }
		if($categoryParent) {
			// $games = CommonGame::boxGameByCategoryParent($categoryParent);
			// $count = ceil(count($games->get())/PAGINATE_BOXGAME);
			// return View::make('site.game.category')->with(compact('games', 'categoryParent', 'count'));
			return View::make('site.game.category')->with(compact('categoryParent'));
		}
		if($type) {
			// $games = CommonGame::boxGameByType($type);
			// $count = ceil(count($games->get())/PAGINATE_BOXGAME);
			// return View::make('site.game.type')->with(compact('games', 'type', 'count'));
			return View::make('site.game.type')->with(compact('type'));
		}
		//TODO 404
		return CommonLog::logErrors(ERROR_TYPE_404);
	}

	public function detailGame($type, $slug)
	{
		// http://minigame.de/be-trai/game-ban-ga-hay-va-chan.html
		if (Cache::has('game_'.$slug))
        {
            $game = Cache::get('game_'.$slug);
        } else {
            $game = Game::findBySlug($slug);
            Cache::put('game_'.$slug, $game, CACHETIME);
        }
		$play = Input::get('play');
		if($game) {
			$count_view = $game->count_view+1;
			$game->update(array('count_view' => $count_view));
			if(getDevice() == COMPUTER) {
				$count_play = $game->count_play+1;
				$game->update(array('count_play' => $count_play));
				// cuongnt todo add count week and count month
				// week
				$date_week = $game->update_week ;
				$date_current = date('Y/m/d');
				$datecountweek = date('w');
				$month_week = date('m', strtotime($date_week));
				$month_current = date('m');
				// $end_date = strtotime("+7 day", $date_week);
				$arrayupdate = array();
				if(!$date_week)
				{
					$date_week_update = date('Y-m-d', strtotime($date_week. ' -'. $datecountweek + 1 .'days'));
					$game->update(array('update_week' => $date_week_update));
				}
				$date_week = date('Y/m/d', strtotime($date_week. ' + 7 days'));
				if($date_week >  $date_current)
				{
					$game->update(array('total_play_download_current_weekly' => $game->total_play_download_current_weekly + 1));
				}else
				{
					$game->update(array('update_week' => $date_current, 'total_play_download_before_weekly' => $game->total_play_download_current_weekly,
										'total_play_download_current_weekly' => '0'));
				}
				// month
				if( $month_current == $month_week)
				{
					$game->update(array('total_play_dowload_current_month' => $game->total_play_dowload_current_month + 1));
				}else
				{
					$game->update(array('total_play_dowload_before_month' => $game->total_play_dowload_current_month,
										'total_play_dowload_current_month' => '0'));
				}

			}
 			return $this->getViewGame($game->parent_id, $game, $play);
		}
		return CommonLog::logErrors(ERROR_TYPE_404);
	}

	public function getViewGame($parentId = null, $game = null, $play = null)
    {

    	if($parentId && $game) {
    		if(getDevice() == MOBILE) {
    			if(!(in_array($game->parent_id, [GAMEFLASH, GAMEHTML5]))) {
	    			return View::make('site.game.downloadmobile')->with(compact('game'));
	    		} else {
	    			if($play == 'true') {
	    				return View::make('site.game.onlinemobileplay')->with(compact('game'));
	    			}
	    			return View::make('site.game.onlinemobile')->with(compact('game'));
	    		}
    		} else {
    			if(!(in_array($game->parent_id, [GAMEFLASH, GAMEHTML5]))) {
	    			return View::make('site.game.downloadweb')->with(compact('game'));
	    		} else {
	    			$gametop = $this->listGameTop();
	    			if($play == 'true') {
	    				//return View::make('site.game.onlinemobileplay')->with(compact('game'));
	    				return View::make('site.game.onlinewebplay')->with(compact('game'));
	    			}
	    			return View::make('site.game.onlineweb')->with(compact('game', 'gametop'));
	    		}
    		}
    	}
    }

	public function getPage404($type, $slug, $word)
    {
		if ($word) {
			if(strpos(Request::url(), '/games/')) {
				return CommonLog::logErrors(ERROR_TYPE_MISSING);
			} else {
				return CommonLog::logErrors(ERROR_TYPE_404);
			}
		}
    }

    public function listGameTop()
    {
    	$now = Carbon\Carbon::now();
        $games = Game::whereNotNull('parent_id')
        		->where('start_date','<', $now)
                ->where('status', ENABLED)
                ->where('parent_id', GAMEHTML5)
                ->orWhere('parent_id', GAMEFLASH)
                ->limit(GAMETOP)
                ->orderBy(DB::raw('RAND()'))
                ->orderBy('count_play', 'desc')
                ->limit(GAMETOP_LIMITED)
                ->get();
        return View::make('site.game.topgame')->with(compact('games'));
    }

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
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.sapo as sapo', 'news.sapo_text as sapo_text', 'news.author as author')
			->where('news.start_date', '<=', Carbon\Carbon::now())
			->where('type_news.status', ENABLED)
			->where('news.status', APPROVE)
			// ->where('news.is_hot', ACTIVE)
			// ->orderByRaw("news.weight_number = '0', news.weight_number")
			// ->orderBy('news.highlight', 'desc')
			->orderBy('news.start_date', 'desc')
			->orderBy('news.id', 'desc')
			->limit($limit)
			->lists('id');
		$inputListNews = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.sapo as sapo', 'news.sapo_text as sapo_text', 'news.author as author')
			->where('news.start_date', '<=', Carbon\Carbon::now())
			->where('type_news.status', ENABLED)
			->where('news.status', APPROVE)
			->whereNotIn('news.id', $list)
			// ->orderByRaw("news.weight_number = '0', news.weight_number")
			// ->orderBy('news.highlight', 'desc')
			->orderBy('news.start_date', 'desc')
			->orderBy('news.id', 'desc')
			// ->offset(4)
			// ->get();
			->paginate(FRONENDPAGINATE);
		return View::make('site.News.listNews')->with(compact('inputListNews'));
	}

	public function listNews($slug)
	{
		$limit = $this->getNumberGamesDevice();
		$newType = TypeNew::findBySlug($slug);
		
		if(empty($newType)) {
			return Redirect::action('SiteController@returnPage404');
		}

		$list = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.sapo as sapo', 'news.sapo_text as sapo_text', 'news.author as author')
			->where('news.start_date', '<=', Carbon\Carbon::now())
			->where('type_news.status', ENABLED)
			->where('news.status', APPROVE)
			->where('type_new_id', $newType->id)
			// ->where('news.is_hot', ACTIVE)
			// ->orderByRaw("news.weight_number = '0', news.weight_number")
			// ->orderBy('news.highlight', 'desc')
			->orderBy('news.start_date', 'desc')
			->orderBy('news.id', 'desc')
			->limit($limit)
			->lists('id');
		$news = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
					->select('news.*')
					->where('type_new_id', $newType->id)
					->where('start_date', '<=', Carbon\Carbon::now())
					->where('type_news.status', ENABLED)
					->where('news.status', APPROVE)
					->whereNotIn('news.id', $list)
					// ->orderByRaw("news.weight_number = '0', news.weight_number")
					// ->orderBy('news.highlight', 'desc')
					->orderBy('news.start_date', 'desc')
					->orderBy('news.id', 'desc')
					->paginate(FRONENDPAGINATE);
		return View::make('site.News.showType')->with(compact('newType', 'news'));
	}

	public function showDetail($slugType, $slugNew)
	{
		$limitHot = AdminPagination::where('status', NEW_HOT)
			->first();
		$limitRelate = AdminPagination::where('status', NEW_RELATE)
			->first();
		$now = Carbon\Carbon::now();
		$newType = TypeNew::findBySlug($slugType);

		$inputNew = AdminNew::findBySlug($slugNew);
		if(empty($newType) || empty($inputNew)) {
			return Redirect::action('SiteController@returnPage404');
		}
		$input['count_view'] = getZero($inputNew->count_view) + 1;
		CommonNormal::update($inputNew->id, $input, 'AdminNew');
		$inputRelated = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
							->select('news.*')
							->where('news.start_date', '<=', $now)
							->where('type_news.status', ENABLED)
							->where('news.status', APPROVE)
							->where('news.type_new_id', $inputNew->type_new_id)
							->orderBy(DB::raw('RAND()'))
							->limit($limitRelate->paginate_number)
							->get();
		$inputHot = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
						->select('news.*')
						->where('news.start_date', '<=', $now)
						->where('type_news.status', ENABLED)
						->where('news.status', APPROVE)
						->where('news.type_new_id', $inputNew->type_new_id)
						->orderBy('news.count_view', 'desc')
						->limit($limitHot->paginate_number)
						->get();
		if($inputNew->type == ACTIVE) {
			$inputNewSlide = NewSlide::where('new_id', $inputNew->id)->get();
			return View::make('site.News.slideNews')->with(compact('newType', 'inputNew', 'inputRelated', 'inputHot', 'inputNewSlide'));
		}

		return View::make('site.News.showNews')->with(compact('newType', 'inputNew', 'inputRelated', 'inputHot'));
	}


}
