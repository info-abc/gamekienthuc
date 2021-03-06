<?php
class CommonGame
{
	public static function uploadAction($fileUpload, $isFile = NULL, $issetFile = NULL, $isUnique = NULL)
	{
		if(Input::hasFile($fileUpload)){
			$file = Input::file($fileUpload);
			$filename = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			if(isset($isUnique)) {
				$filename = changeFileNameImage($filename);
			}
			if($isFile != '') {
				$pathUpload = self::getPathFile($extension);
			} else {
				$pathUpload = public_path().UPLOAD_GAME_AVATAR;
			}
			$uploadSuccess = $file->move($pathUpload, $filename);
		}
		if(isset($uploadSuccess)) {
			if(isset($isFile) && $extension == 'zip') {
				$pathUnzip = public_path().UPLOAD_GAME;
				Zipper::make($pathUpload.'/'.$filename)->extractTo($pathUnzip);
			}
			return $filename;
		}
		if($issetFile) {
			return $issetFile;
		}
	}

	public static function getPathFile($extension = null)
	{
		if($extension) {
			if($extension == 'zip') {
				return public_path().UPLOAD_GAMEZIP;
			}
			if($extension == 'swf') {
				return public_path().UPLOAD_FLASH;
			}
			if($extension == 'apk') {
				return public_path().UPLOAD_GAMEOFFLINE;
			}
		}
		return null;
	}

	public static function inputActionGame($id = NULL)
	{
		if($id) {
			$issetFile = self::getIssetFile($id, TRUE);
			$issetAvatar = self::getIssetFile($id);
		} else {
			$issetFile = '';
			$issetAvatar = '';
		}
		$inputGame = array();
		$inputGame['image_url'] = self::uploadAction('image_url', '', $issetAvatar,  IS_UPLOAD_UNIQUE);
		$inputGame['link_upload_game'] = self::uploadAction('link_upload_game', IS_UPLOAD_FILE, $issetFile);
		$inputGame['name'] = Input::get('name');
		$inputGame['description'] = Input::get('description');
		$inputGame['link_download'] = Input::get('link_download');
		//check link upload game , link_url
		if(Input::get('link_url')) {
			$inputGame['link_url'] = Input::get('link_url');
		} elseif(!Input::get('link_url') && $inputGame['link_upload_game']) {
			$inputGame['link_url'] = getFilename($inputGame['link_upload_game']);
		}
		$inputGame['parent_id'] = Input::get('parent_id');
		$inputGame['weight_number'] = Input::get('weight_number');
		$inputGame['start_date'] = Input::get('start_date');
		if($inputGame['start_date'] == '') {
			$inputGame['start_date'] = Carbon\Carbon::now();
		}
		$inputGame['status'] = Input::get('status');
		$inputGame['score_status'] = Input::get('score_status');
		$inputGame['gname'] = Input::get('gname');
		$inputGame['slide_id'] = Input::get('slide_id');
		$inputGame['type_main'] = Input::get('type_main');
		$inputGame['width'] = Input::get('width');
		$inputGame['height'] = Input::get('height');
		$inputGame['screen'] = Input::get('screen');
		$inputGame['sapo'] = Input::get('sapo');
		return $inputGame;
	}

	public static function getIssetFile($id, $isFile = NULL)
	{
		if($isFile){
			$result = Game::find($id)->link_upload_game;
		} else {
			$result = Game::find($id)->image_url;
		}
		if ($result) {
			return $result;
		}
		return NULL;
	}

	public static function searchAdminGame($input)
	{
		$orderBy = self::searchAdminGameSortBy($input);
		$data = Game::where(function ($query) use ($input) {

			if(isset($input['isGameBox'])) {
				$query = $query->where('index', ACTIVE);
			}

			if ($input['keyword'] != '') {
				$inputSlug = convert_string_vi_to_en($input['keyword']);
				$inputSlug = strtolower( preg_replace('/[^a-zA-Z0-9]+/i', '-', $inputSlug) );
				$query = $query->where('slug', 'like', '%'.$inputSlug.'%');
							// ->orWhere('name', 'like', '%'.$input['keyword'].'%');
			}
			if($input['parent_id'] != '') {
				$query = $query->where('parent_id', $input['parent_id']);
			}
			if($input['parent_id'] == '') {
				$query = $query->whereNotNUll('parent_id');
			}
			// if($input['category_parent_id'] != '') {
			// 	$list = CategoryParent::find($input['category_parent_id'])->categoryparentrelations->lists('game_id');
			// 	$query = $query->whereIn('id', $list);
			// }
			if($input['type_id'] != '') {
				$listType = Type::find($input['type_id'])->gametypes->lists('game_id');
				$query = $query->whereIn('id', $listType);
			}
			if($input['status'] != '') {
				$query = $query->where('status', $input['status']);
			}
			if($input['start_date'] != ''){
				$query = $query->where('start_date', '>=', $input['start_date']);
			}
			if($input['end_date'] != ''){
				$query = $query->where('start_date', '<=', $input['end_date'] . ' 23:59:59');
			}
			if(isset($input['status_seo']) && $input['status_seo'] != '')
			{
				$listSeo = AdminSeo::where('model_name', 'Game')->where('status_seo', $input['status_seo'])->lists('model_id');
				$query = $query->whereIn('id', $listSeo);
			}
			
		})
		// ->get();
		// dd($data);
		// ->lists('id');
		// return $data;
		// dd($data);
		->orderBy($orderBy[0], $orderBy[1])
		->paginate(PAGINATE);
		//dd(DB::getQueryLog());
		return $data;
	}

	public static function searchAdminGameSortBy($input)
	{
		$sortBy = 'start_date';
		$sort = 'desc';
		if(isset($input['sortByCountView']) && $input['sortByCountView'] != '') {
			if($input['sortByCountView'] == 'count_view_asc') {
				$sortBy = 'count_view';
				$sort = 'asc';
			}
			if($input['sortByCountView'] == 'count_view_desc') {
				$sortBy = 'count_view';
				$sort = 'desc';
			}
		}
		if($input['sortByCountPlay'] != '') {
			if($input['sortByCountPlay'] == 'count_play_asc') {
				$sortBy = 'count_play';
				$sort = 'asc';
			}
			if($input['sortByCountPlay'] == 'count_play_desc') {
				$sortBy = 'count_play';
				$sort = 'desc';
			}
		}
		if(isset($input['sortByCountVote']) && $input['sortByCountVote'] != '') {
			if($input['sortByCountVote'] == 'count_vote_asc') {
				$sortBy = 'count_vote';
				$sort = 'asc';
			}
			if($input['sortByCountVote'] == 'count_vote_desc') {
				$sortBy = 'count_vote';
				$sort = 'desc';
			}
		}
		if(isset($input['sortByCountDownload']) && $input['sortByCountDownload'] != '') {
			if($input['sortByCountDownload'] == 'count_download_asc') {
				$sortBy = 'count_download';
				$sort = 'asc';
			}
			if($input['sortByCountDownload'] == 'count_download_desc') {
				$sortBy = 'count_download';
				$sort = 'desc';
			}
		}
		// weight_number
		if(isset($input['sortByweightNumber']) && $input['sortByweightNumber'] != '') {
			if($input['sortByweightNumber'] == 'weight_number_asc') {
				$sortBy = 'weight_number';
				$sort = 'asc';
			}
			if($input['sortByweightNumber'] == 'weight_number_desc') {
				$sortBy = 'weight_number';
				$sort = 'desc';
			}
		}
		return [$sortBy, $sort];
	}


	// get games, orderBy arrange category parent, paging
	public static function boxGameByCategoryParent($data, $paginate = null)
	{
		$now = Carbon\Carbon::now();
		$arrange = getArrange($data->arrange);
		$game = $data->games->first();
		if($game) {
			if($paginate) {
				if(getDevice() == MOBILE) {
					$listGame = Game::where('parent_id', '!=', GAMEFLASH)
						->where('parent_id', $game->id)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->orderBy($arrange, 'desc')
						->paginate(PAGINATE_LISTGAME);
				} else {
					$listGame = Game::where('parent_id', $game->id)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->orderBy($arrange, 'desc')
						->paginate(PAGINATE_LISTGAME);
				}
			} else {
				if(getDevice() == MOBILE) {
					$listGame = Game::where('parent_id', '!=', GAMEFLASH)
						->where('status', ENABLED)
						->where('parent_id', $game->id)
						->where('start_date', '<=', $now)
						->orderBy($arrange, 'desc');
				} else {
					$listGame = Game::where('parent_id', $game->id)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->orderBy($arrange, 'desc');
				}
			}
			return $listGame;
		}
		return null;
	}

	public static function boxGameByType($data, $paginate = null)
	{
		$now = Carbon\Carbon::now();
		$games = Type::find($data->id)->gametypes->lists('game_id');
		if($games) {
			if($paginate) {
				if(getDevice() == MOBILE) {
					$listGame = Game::whereIn('id', $games)
						->where('status', ENABLED)
						->where('parent_id', '=', GAMEHTML5)
						->where('start_date', '<=', $now)
						->orderBy('id', 'desc')
						->paginate(PAGINATE_LISTGAME);
				} else {

					$listGame = Game::whereIn('id', $games)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->whereIn('parent_id', [GAMEHTML5, GAMEFLASH])
						->orderBy('id', 'desc')
						->paginate(PAGINATE_LISTGAME);
				}
			} else {
				if(getDevice() == MOBILE) {
					$listGame = Game::whereIn('id', $games)
						->where('status', ENABLED)
						->where('parent_id', '=', GAMEHTML5)
						->where('start_date', '<=', $now);
				} else {
					$listGame = Game::whereIn('id', $games)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->whereIn('parent_id', [GAMEHTML5, GAMEFLASH]);
				}
			}
			return $listGame;
		}
		return null;
	}

	public static function boxGameByCategoryParentIndex($data)
	{
		$now = Carbon\Carbon::now();
		$arrange = getArrange($data->arrange);
		$game = $data->games->first();
		if($game) {
			if(getDevice() == MOBILE) {
				if (Cache::has('listGameMobile'.$game->id.$arrange))
				{
					$listGame = Cache::get('listGameMobile'.$game->id.$arrange);
				} else {
					$listGame = DB::table('games')
						->join('types', 'types.id', '=', 'games.type_main')
						->join('games as category', 'category.id', '=', 'games.parent_id')
						->select('games.id', 'games.name', 'games.slug'
								, 'games.parent_id', 'games.type_main', 'games.image_url', 'games.sapo'
								, 'types.name as type_name', 'types.slug as type_slug'
								, 'category.slug as category_slug')
						->distinct()
						->where('games.parent_id', '!=', GAMEFLASH)
						->where('games.parent_id', $game->id)
						->whereNull('games.deleted_at')
						->where('games.status', ENABLED)
						->where('games.start_date', '<=', $now);
					if($data->arrange == GAME_NEWEST){
						$listGame = $listGame->orderBy('games.'.$arrange, 'desc');
					} elseif($data->arrange == GAME_PLAY) {
						$listGame = $listGame->orderByRaw(DB::raw("games.weight_number = '0', games.weight_number"))
								->orderBy('games.'.$arrange, 'desc');
					} else{
						$listGame = $listGame->orderBy('games.'.$arrange, 'desc')
								->orderByRaw(DB::raw("games.weight_number = '0', games.weight_number"));
					}
					$listGame = $listGame->get();
					Cache::put('listGameMobile'.$game->id.$arrange, $listGame, CACHETIME);
				}
			} else {
				if (Cache::has('listGamePC'.$game->id.$arrange))
				{
					$listGame = Cache::get('listGamePC'.$game->id.$arrange);
				} else {
					if (in_array($game->id, [GAMEFLASH, GAMEHTML5])) {
						$listGame = DB::table('games')
						->join('types', 'types.id', '=', 'games.type_main')
						->join('games as category', 'category.id', '=', 'games.parent_id')
						->select('games.id', 'games.name', 'games.slug'
								, 'games.parent_id', 'games.type_main', 'games.image_url', 'games.sapo'
								, 'types.name as type_name', 'types.slug as type_slug', 'games.count_play', 'category.slug as category_slug')
						->distinct()
						// ->where('games.parent_id', $game->id)
						->whereNull('games.deleted_at')
						->where('games.status', ENABLED)
						->where('games.start_date', '<=', $now);
					}
					else{
						$listGame = DB::table('games')
						->join('games as category', 'category.id', '=', 'games.parent_id')
						->select('games.id', 'games.name', 'games.slug'
								, 'games.parent_id', 'games.image_url', 'games.sapo'
								, 'games.count_play', 'category.slug as category_slug')
						->distinct()
						->where('games.parent_id', $game->id)
						->whereNull('games.deleted_at')
						->where('games.status', ENABLED)
						->where('games.start_date', '<=', $now);
					}
					if($data->arrange == GAME_NEWEST){
						$listGame = $listGame->orderBy('games.'.$arrange, 'desc');
					} elseif($data->arrange == GAME_PLAY) {
						$listGame = $listGame->orderByRaw(DB::raw("games.weight_number = '0', games.weight_number"))
								->orderBy('games.'.$arrange, 'desc');
					} else{
						$listGame = $listGame->orderBy('games.'.$arrange, 'desc')
								->orderByRaw(DB::raw("games.weight_number = '0', games.weight_number"));
					}
					$listGame = $listGame->get();
					Cache::put('listGamePC'.$game->id.$arrange, $listGame, CACHETIME);
				}
			}
			return $listGame;
		}
		return null;
	}

	public static function getUrlGameIndex($game = null)
	{
		if($game) {
			if (!(in_array($game->parent_id, [GAMEFLASH, GAMEHTML5]))) {
				return $url = url('/' . $game->category_slug . '/' . $game->slug);
			}
			if($game->type_name && $game->type_slug) {
				$url = url('/' . $game->type_slug . '/' . $game->slug);
				return $url;
			} else {
				dd('Đường dẫn sai');
			}
		} else {
			return url('/');
		}
	}

	// url game
	public static function getUrlGame($game = null)
	{
		if($game) {
			if (!(in_array($game->parent_id, [GAMEFLASH, GAMEHTML5]))) {
				if (Cache::has('category'.$game->parent_id))
				{
					$category = Cache::get('category'.$game->parent_id);
				} else {
					$category = Game::find($game->parent_id);
					Cache::put('category'.$game->parent_id, $category, CACHETIME);
				}
				return $url = url('/' . $category->slug . '/' . $game->slug);
			}
			if (Cache::has('type'.$game->type_main))
			{
				$type = Cache::get('type'.$game->type_main);
			} else {
				$type = Type::find($game->type_main);
				Cache::put('type'.$game->type_main, $type, CACHETIME);
			}
			if($type) {
				$url = url('/' . $type->slug . '/' . $game->slug);
				return $url;
			} else {
				dd('Đường dẫn sai');
			}
		} else {
			return url('/');
		}
	}

	// url download game
	public static function getUrlDownload($game = null)
	{
		if($game) {
			if($game->link_download != '') {
				// return url(UPLOAD_GAMEOFFLINE . '/' . $game->link_upload_game);
				return url($game->link_download);
			}
			if($game->link_url != '') {
				return url(UPLOAD_GAMEOFFLINE . '/' . $game->link_url);
			}
		}
		return '#';
	}

	// Other games by parent_id with limit
	public static function getRelateGame($parentId, $limit, $typeId)
	{
		$now = Carbon\Carbon::now();
		if($parentId && $limit && $typeId) {
			if(getDevice() == MOBILE) {
				if (Cache::has('listGameRelateMobile'.$parentId))
				{
					$listGame = Cache::get('listGameRelateMobile'.$parentId);
				} else {
					$listGame = Game::where('parent_id', $parentId)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->where('parent_id', '!=', GAMEFLASH)
						->where('type_main', $typeId)
						->orderBy(DB::raw('RAND()'))
						->limit($limit)->get();
					Cache::put('listGameRelateMobile'.$parentId, $listGame, CACHETIME);
				}
			} else {
				if (Cache::has('listGameRelate'.$parentId))
				{
					$listGame = Cache::get('listGameRelate'.$parentId);
				} else {
					$listGame = Game::where('parent_id', $parentId)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->where('type_main', $typeId)
						->orderBy(DB::raw('RAND()'))
						->limit($limit)->get();
					Cache::put('listGameRelate'.$parentId, $listGame, CACHETIME);
				}
			}
			return $listGame;
		}
		return null;
	}

	// link to file folder play game online
	public static function getLinkGame($game = null)
	{
		if($game) {
			// $ext = getExtension($game->link_upload_game);
			$filename = getFilename($game->link_upload_game);
			if($game->parent_id == GAMEFLASH) {
				if($game->link_url != '') {
					$link = url(UPLOAD_FLASH . '/' . $game->link_url); // . '.swf'
				} else {
					$link = url(UPLOAD_FLASH . '/' . $game->link_upload_game);
				}
				$box = self::getBoxGame($link, $game);
				return $box;
			}
			if($game->parent_id == GAMEHTML5) {
				if($game->link_url != '') {
					$link = url(UPLOAD_GAME . '/' . $game->link_url);
				} else {
					$link = url(UPLOAD_GAME . '/' . $filename);
				}
				$box = self::getBoxGame($link, $game);
				return $box;
			}
		}
		return null;
	}

	public static function getBoxGame($link, $game)
	{
		$width = (isset($game->width) && $game->width != '')?($game->width):'640';
		$height = (isset($game->height) && $game->height != '')?($game->height):'480';

		if($game->parent_id == GAMEFLASH) {
			$box = '<div id="game-ad">
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
					codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" 
					width="'.$width.'" height="'.$height.'" 
					id="preloader" 
					align="middle">
					<param name="allowScriptAccess" value="always" />
					<param name="allowFullScreen" value="false" />
				    <param name="movie" value="'. url('games-flash/ima3_preloader_1.5.swf') .'" type="application/x-shockwave-flash"></param>
				    <param name="quality" value="high" />
				    <param name="bgcolor" value="#ffffff" />
				    <param name="wmode" value="transparent"></param>
				    <param name="flashvars" value="adTagUrl=http%3A%2F%2Fgoogleads.g.doubleclick.net%2Fpagead%2Fads%3Fad_type%3Dvideo_text_image_flash%26client%3Dca-games-pub-1198251289541286%26description_url%3Dhttp%253A%252F%252Fgame.kienthuc.net.vn%26videoad_start_delay%3D0%26hl%3Den" />
				    <embed src="'. url('games-flash/ima3_preloader_1.5.swf') .'" 
			          	type="application/x-shockwave-flash" 
						quality="high" bgcolor="#000000" 
						width="'.$width.'" height="'.$height.'" 
						name="preloader" 
			          	align="middle" allowScriptAccess="always" 
			          	allowFullScreen="false" 
			          	flashVars="adTagUrl=http%3A%2F%2Fgoogleads.g.doubleclick.net%2Fpagead%2Fads%3Fad_type%3Dvideo_text_image_flash%26client%3Dca-games-pub-1198251289541286%26description_url%3Dhttp%253A%252F%252Fgame.kienthuc.net.vn%26videoad_start_delay%3D0%26hl%3Den" 
			          	pluginspage="http://www.adobe.com/go/getflashplayer" 
			          	wmode="direct">
				    </embed>
				</object>
			</div>
			<div id="game-container" style="display:none;">
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
					codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" 
					width="'.$width.'" height="'.$height.'" 
					id="preloader" 
					align="middle">
					<param name="allowScriptAccess" value="always" />
					<param name="allowFullScreen" value="false" />
				    <param name="movie" value="'.$link.'" type="application/x-shockwave-flash"></param>
				    <param name="quality" value="high" />
				    <param name="bgcolor" value="#ffffff" />
				    <param name="wmode" value="transparent"></param>
				    <param name="flashvars" value="adTagUrl=http%3A%2F%2Fgoogleads.g.doubleclick.net%2Fpagead%2Fads%3Fad_type%3Dvideo_text_image_flash%26client%3Dca-games-pub-1198251289541286%26description_url%3Dhttp%253A%252F%252Fgame.kienthuc.net.vn%26videoad_start_delay%3D0%26hl%3Den" />
				    <embed src="'.$link.'" 
			          	type="application/x-shockwave-flash" 
						quality="high" bgcolor="#000000" 
						width="'.$width.'" height="'.$height.'" 
						name="preloader" 
			          	align="middle" allowScriptAccess="always" 
			          	allowFullScreen="false" 
			          	flashVars="adTagUrl=http%3A%2F%2Fgoogleads.g.doubleclick.net%2Fpagead%2Fads%3Fad_type%3Dvideo_text_image_flash%26client%3Dca-games-pub-1198251289541286%26description_url%3Dhttp%253A%252F%252Fgame.kienthuc.net.vn%26videoad_start_delay%3D0%26hl%3Den" 
			          	pluginspage="http://www.adobe.com/go/getflashplayer" 
			          	wmode="direct">
				    </embed>
				</object>
			</div>
			<script type="text/javascript">
				function removeAdSwf() {
					console.log(1);
					document.getElementById("game-ad").innerHTML = "";
					// document.getElementById("preloader").style.visibility = "hidden";
					document.getElementById("game-container").style.display="block";
				}
				function noAdsReturned() {
					console.log(2);
					document.getElementById("game-ad").innerHTML = "";
					// document.getElementById("preloader").style.visibility = "hidden";
					document.getElementById("game-container").style.display="block";
				}
			</script>';
			// $box = '<embed type="application/x-shockwave-flash" src="' . $link .'" width="'.$width.'" height="'.$height.'" style="undefined" id="game" name="game" quality="high" wmode="direct">';
			return $box;
		}

		if($game->parent_id == GAMEHTML5) {
			//game html5 chạy file game.html trong iframe (bỏ menu)
			$link = $link . '/game.html';
			$box = '<div id="game-ad">
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
					codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" 
					width="'.$width.'" height="'.$height.'" 
					id="preloader" 
					align="middle">
					<param name="allowScriptAccess" value="always" />
					<param name="allowFullScreen" value="false" />
				    <param name="movie" value="'. url('games-flash/ima3_preloader_1.5.swf') .'" type="application/x-shockwave-flash"></param>
				    <param name="quality" value="high" />
				    <param name="bgcolor" value="#ffffff" />
				    <param name="wmode" value="transparent"></param>
				    <param name="flashvars" value="adTagUrl=http%3A%2F%2Fgoogleads.g.doubleclick.net%2Fpagead%2Fads%3Fad_type%3Dvideo_text_image_flash%26client%3Dca-games-pub-1198251289541286%26description_url%3Dhttp%253A%252F%252Fgame.kienthuc.net.vn%26videoad_start_delay%3D0%26hl%3Den" />
				    <embed src="'. url('games-flash/ima3_preloader_1.5.swf') .'" 
			          	type="application/x-shockwave-flash" 
						quality="high" bgcolor="#000000" 
						width="'.$width.'" height="'.$height.'" 
						name="preloader" 
			          	align="middle" allowScriptAccess="always" 
			          	allowFullScreen="false" 
			          	flashVars="adTagUrl=http%3A%2F%2Fgoogleads.g.doubleclick.net%2Fpagead%2Fads%3Fad_type%3Dvideo_text_image_flash%26client%3Dca-games-pub-1198251289541286%26description_url%3Dhttp%253A%252F%252Fgame.kienthuc.net.vn%26videoad_start_delay%3D0%26hl%3Den" 
			          	pluginspage="http://www.adobe.com/go/getflashplayer" 
			          	wmode="direct">
				    </embed>
				</object>
			</div>
			<div id="game-container" style="display:none; margin: 10px auto; width: '.$width.'px; height: '.$height.'px;">
				<iframe name="my-iframe" id="my-iframe" width="100%" src="'.$link.'" height="100%" scrolling="no" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" webkit-playsinline="true" seamless="seamless" style="-webkit-transform: scale(1, 1);
				-o-transform: scale(1, 1);
				-ms-transform: scale(1, 1);
				transform: scale(1, 1);
				-moz-transform-origin: top left;
				-webkit-transform-origin: top left;
				-o-transform-origin: top left;
				-ms-transform-origin: top left;
				transform-origin: top left;
				frameborder: 0px;">
				</iframe>
			</div>
			<script type="text/javascript">
				function removeAdSwf() {
					console.log(1);
					document.getElementById("game-ad").innerHTML = "";
					// document.getElementById("preloader").style.visibility = "hidden";
					document.getElementById("game-container").style.display="block";
					document.getElementById("my-iframe").contentWindow.location.reload();
				}
				function noAdsReturned() {
					console.log(2);
					document.getElementById("game-ad").innerHTML = "";
					// document.getElementById("preloader").style.visibility = "hidden";
					document.getElementById("game-container").style.display="block";
					document.getElementById("my-iframe").contentWindow.location.reload();
				}
			</script>';
			// $box = '<div style="margin: 10px auto; width: '.$width.'px; height: '.$height.'px;">
			// 		<iframe name="my-iframe" id="my-iframe" width="100%" src="'.$link.'" height="100%" scrolling="no" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" webkit-playsinline="true" seamless="seamless" style="-webkit-transform: scale(1, 1);
			// 		-o-transform: scale(1, 1);
			// 		-ms-transform: scale(1, 1);
			// 		transform: scale(1, 1);
			// 		-moz-transform-origin: top left;
			// 		-webkit-transform-origin: top left;
			// 		-o-transform-origin: top left;
			// 		-ms-transform-origin: top left;
			// 		transform-origin: top left;
			// 		frameborder: 0px;">
			// 		</iframe>
			// 	</div>';
			return $box;
		}
	}

	//get link play game for games HTML5
	public static function getLinkPlayGameHtml5($game = null)
	{
		if($game) {
			$filename = getFilename($game->link_upload_game);
			if($game->parent_id == GAMEHTML5) {
				if($game->link_url != '') {
					$link = url(UPLOAD_GAME . '/' . $game->link_url);
				} else {
					$link = url(UPLOAD_GAME . '/' . $filename);
				}
				return $link;
			}
		}
		return View::make('404');
	}

	public static function getStyle()
	{
		if(getDevice() == MOBILE) {
			$style = 'width: 100%; height: 100%;';
		} else {
			$style = 'width: 100%; height: 450px;';
		}
		return $style;
	}

	public static function getSlide()
	{
		return AdminSlide::lists('name', 'id');
	}

	public static function getGameScore($gameId)
	{
		$score = Score::where('game_id', $gameId)
					->orderBy('score', 'desc')
					->groupBy('user_id')
					->limit(GAMESCORE_LIMITED)
					->get();
		if($score) {
			return $score;
		} else {
			return null;
		}
	}

	public static function getGameMost()
	{
		$now = Carbon\Carbon::now();
		$games = Game::whereNotNull('parent_id')
				->where('status', ENABLED)
				->where('parent_id', GAMEHTML5)
				->where('start_date', '<=', $now)
				->orderBy('count_play', 'desc')
				->orderBy('start_date', 'desc')
				->limit(PAGINATE_BOXGAME)
				->get();
		return $games;
	}

	/**
	* Get list game for binh chon nhieu + hay nhat + download
	* @return list game
	*
	*/
	public static function getListGame($view = null)
	{
		$now = Carbon\Carbon::now();
		if(getDevice() == MOBILE) {
			$games = Game::whereNotNull('parent_id')
				->where('status', ENABLED)
				->where('parent_id', '!=', GAMEFLASH)
				->where('start_date', '<=', $now);
		} else {
			$games = Game::whereNotNull('parent_id')
				->where('status', ENABLED)
				->where('start_date', '<=', $now);
		}
		//check game category
		if($view == 'android') {
			$games = $games->where('parent_id', GAMEOFFLINE);
		}
		//to do: vote, play for gamehtml5 only
		if($view == 'vote' || $view == 'play') {
			$games = $games->whereIn('parent_id', [GAMEHTML5, GAMEFLASH]);
		}
		return $games;
	}

	public static function getGameByType($typeId)
	{
		$now = Carbon\Carbon::now();
		$gameIds = Type::find($typeId)->gametypes->lists('game_id');
		if($gameIds) {
			if(getDevice() == MOBILE) {
				$listGame = Game::whereIn('id', $gameIds)
					->where('status', ENABLED)
					->where('parent_id', '=', GAMEHTML5)
					->where('start_date', '<=', $now);
			} else {
				$listGame = Game::whereIn('id', $gameIds)
					->where('status', ENABLED)
					->where('start_date', '<=', $now)
					->whereIn('parent_id', [GAMEHTML5, GAMEFLASH]);
			}
			$listGame = $listGame->orderBy('start_date', 'desc')
							->limit(6)
							->get();
			return $listGame;
		}
		return null;
	}

	public static function getBoxMiniGame()
	{
		$cacheName = 'getBoxMiniGame';
		if (Cache::has($cacheName)) {
        	$result = Cache::get($cacheName);
        	return $result;
        }

		$result = array();
		$types = Type::all();
		if($types) {
			foreach($types as $key => $value) {
				$games = self::getGameByType($value->id);
				$result[$key] = array(
					'type_id' => $value->id,
					'type_name' => $value->name,
					'type_slug' => $value->slug,
					'games' => $games
				);
			}
		}
		Cache::put($cacheName, $result, CACHETIME);
		return $result;
	}

	public static function boxGameByTag($data, $paginate = null)
	{
		$now = Carbon\Carbon::now();
		$gameIds = AdminTag::find($data->id)->gameTags->lists('game_id');
		if($gameIds) {
			if($paginate) {
				if(getDevice() == MOBILE) {
					$listGame = Game::whereIn('id', $gameIds)
						->where('status', ENABLED)
						->where('parent_id', '=', GAMEHTML5)
						->where('start_date', '<=', $now)
						->orderBy('id', 'desc')
						->paginate(PAGINATE_LISTGAME);
				} else {

					$listGame = Game::whereIn('id', $gameIds)
						->where('status', ENABLED)
						->where('start_date', '<=', $now)
						->whereIn('parent_id', [GAMEHTML5, GAMEFLASH])
						->orderBy('id', 'desc')
						->paginate(PAGINATE_LISTGAME);
				}
			} else {
				if(getDevice() == MOBILE) {
					$listGame = Game::whereIn('id', $gameIds)
						->where('status', ENABLED)
						->where('parent_id', '=', GAMEHTML5)
						->where('start_date', '<=', $now);
				} else {
					$listGame = Game::whereIn('id', $gameIds)
						->where('status', ENABLED)
						->where('start_date', '<=', $now);
						// ->whereIn('parent_id', [GAMEHTML5, GAMEFLASH]);
				}
			}
			return $listGame;
		}
		return null;
	}

	public static function gameCode1($pc = 0, $uploadIm = null)
	{
		if($pc == 1) {
    		$limitGame = 4;
    	} else {
    		$limitGame = 2;
    	}
    	$now = Carbon\Carbon::now();
    	$dataFirst = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.image_link as image_link', 'news.sapo as sapo', 'news.sapo_text as sapo_text', 'news.author as author')
			->where('news.start_date', '<=', $now)
			->where('type_news.status', ENABLED)
			->where('news.status', APPROVE)
			->where('news.index', '!=', INACTIVE)
			->orderByRaw("news.weight_number = '0', news.weight_number")
			->orderBy('news.start_date', 'desc')
			->orderBy('news.id', 'desc')
			->first();
		$dataSecond = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
			->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.sapo as sapo', 'news.sapo_text as sapo_text', 'news.author as author')
			->where('news.start_date', '<=', $now)
			->where('type_news.status', ENABLED)
			->where('news.status', APPROVE)
			->where('news.index', '!=', INACTIVE)
			->orderByRaw("news.weight_number = '0', news.weight_number")
			->orderBy('news.start_date', 'desc')
			->orderBy('news.id', 'desc')
			->skip(1)
			->take(2)
			->get();
		if($pc == 1) {
			$dataList = Game::where('status', ENABLED)
				->where('start_date', '<=', $now)
				->where('index', ACTIVE)
				->orderByRaw("games.index = '0', games.index")
	    		->orderBy('start_date', 'desc')
	    		->take($limitGame)
	    		->get();
		} else {
			$dataList = Game::where('status', ENABLED)
						->where('parent_id', '!=', GAMEFLASH)
						->where('start_date', '<=', $now)
						->where('index', ACTIVE)
						->orderByRaw("games.index = '0', games.index")
			    		->orderBy('start_date', 'desc')
			    		->take($limitGame)
			    		->get();
		}
		$dataListCount = count($dataList);
		if($dataListCount < $limitGame) {
			$dataListLimit = $limitGame - $dataListCount;
			if($pc == 1) {
				$dataListGame = Game::where('status', ENABLED)
					// ->where('parent_id', '=', GAMEHTML5)
					->where('start_date', '<=', $now)
					->where('index', INACTIVE)
					->orderByRaw("games.index = '0', games.index")
		    		->orderBy('start_date', 'desc')
		    		->take($dataListLimit)
		    		->get();
			} else {
				$dataListGame = Game::where('status', ENABLED)
					->where('parent_id', '!=', GAMEFLASH)
					->where('start_date', '<=', $now)
					->where('index', INACTIVE)
					->orderByRaw("games.index = '0', games.index")
		    		->orderBy('start_date', 'desc')
		    		->take($dataListLimit)
		    		->get();
			}
		}
		if($uploadIm != null) {
			self::uploadToServe($dataFirst, 1);
			self::uploadToServe($dataList);
			if(isset($dataListGame)) {
				self::uploadToServe($dataListGame);
			}
			if($pc == 1) {
				return View::make('site.common.iframe')->with(compact('dataFirst', 'dataList', 'dataListGame', 'dataSecond'));
			} else {
				return View::make('site.common.iframe_mobile')->with(compact('dataFirst', 'dataList', 'dataListGame', 'dataSecond'));
			}
		} else {
			if($pc == 1) {
				return View::make('site.common.iframe_1')->with(compact('dataFirst', 'dataList', 'dataListGame', 'dataSecond'));
			} else {
				return View::make('site.common.iframe_mobile_1')->with(compact('dataFirst', 'dataList', 'dataListGame', 'dataSecond'));
			}
		}
		
	}

	public static function gameCode2($pc = 0, $uploadIm = null)
	{
		if($pc == 1) {
    		$limitGame = 5;
    	} else {
    		$limitGame = 4;
    	}
    	$now = Carbon\Carbon::now();
    	if($pc == 1) {
    		$dataList = Game::where('status', ENABLED)
				->where('start_date', '<=', $now)
				->where('index', ACTIVE)
				->orderByRaw("games.index = '0', games.index")
	    		->orderBy('start_date', 'desc')
	    		->take($limitGame)
	    		->get();
		} else {
			$dataList = Game::where('status', ENABLED)
						->where('parent_id', '!=', GAMEFLASH)
						->where('start_date', '<=', $now)
						->where('index', ACTIVE)
						->orderByRaw("games.index = '0', games.index")
			    		->orderBy('start_date', 'desc')
			    		->take($limitGame)
			    		->get();
		}
		$dataListCount = count($dataList);
		if($dataListCount < $limitGame) {
			$dataListLimit = $limitGame - $dataListCount;
			if($pc == 1) {
				$dataListGame = Game::where('status', ENABLED)
					// ->where('parent_id', '=', GAMEHTML5)
					->where('start_date', '<=', $now)
					->where('index', INACTIVE)
					->orderByRaw("games.index = '0', games.index")
		    		->orderBy('start_date', 'desc')
		    		->take($dataListLimit)
		    		->get();
			} else {
				$dataListGame = Game::where('status', ENABLED)
					->where('parent_id', '!=', GAMEFLASH)
					->where('start_date', '<=', $now)
					->where('index', INACTIVE)
					->orderByRaw("games.index = '0', games.index")
		    		->orderBy('start_date', 'desc')
		    		->take($dataListLimit)
		    		->get();
			}
		}
		if($uploadIm != null) {
			self::uploadToServe($dataList);
			if(isset($dataListGame)) {
				self::uploadToServe($dataListGame);
			}
			if($pc == 1) {
				return View::make('site.common.iframe2')->with(compact('dataList', 'dataListGame'));
			} else {
				return View::make('site.common.iframe2_mobile')->with(compact('dataList', 'dataListGame'));
			}
		} else {
			if($pc == 1) {
				return View::make('site.common.iframe2_1')->with(compact('dataList', 'dataListGame'));
			} else {
				return View::make('site.common.iframe2_mobile_1')->with(compact('dataList', 'dataListGame'));
			}
		}
	}
	public static function uploadToServe($input, $dataFirst=null)
	{
		//tin tuc
		if(isset($dataFirst)) {
			$link = LINK_SERVE_UPLOAD . '='. url(UPLOADIMG . '/news'.'/'. $input->id . '/' . $input->image_url);
			$json = @file_get_contents($link);
			if($json) {
				$data = json_decode($json, TRUE);
				$input->image_link = $data['data']['img_url'];
				$input->update(['image_link' => $data['data']['img_url']]);
			}
			return $input;
		}
		//game
		foreach ($input as $key => $value) {
			$link = LINK_SERVE_UPLOAD . '='. url(UPLOAD_GAME_AVATAR . '/' .  $value->image_url);
			$json = @file_get_contents($link);
			if($json) {
				$data = json_decode($json, TRUE);
				$value->image_link = $data['data']['img_url'];
				$value->update(['image_link' => $data['data']['img_url']]);
			}
		}
		return $input;

	}

}
