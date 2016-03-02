<?php
class NewsManager
{
	public static function searchNews($input)
	{
		$orderBy = self::searchAdminGameSortBy($input);
		$data = AdminNew::where(function ($query) use ($input)
		{
			if ($input['type_new_id'] != 0) {
				$query = $query->where('type_new_id', $input['type_new_id']);
			}
			if ($input['title']) {
				$query = $query->where('title', 'like', '%'.$input['title'].'%');
			}
			if($input['start_date'] != ''){
				$query = $query->where('start_date', '>=', $input['start_date']);
			}
			if($input['end_date'] != ''){
				$query = $query->where('start_date', '<=', $input['end_date']);
			}
			// if($input['status_seo'] != '')
			// {
			// 	$listSeo = AdminSeo::where('model_name', 'AdminNew')->where('status_seo', $input['status_seo'])->lists('model_id');
   //              $query = $query->whereIn('id', $listSeo);
			// }
			if ($input['position'] != '') {
				$query = $query->where('position', $input['position']);
			}
			if ($input['user_id'] != '') {
				if ($input['user_id'] == 1) {
					// $userRole = Admin::find($userId)->role_id
					$query = $query->where('user', $input['position']);
				}
				if ($input['user_id'] == 2) {
					# code...
				}
			}
		});
		if (Admin::isAdmin() || Admin::isEditor()) {
			$data = $data->where('status', '!=', SCRATCH_PAPER)
				->orderBy($orderBy[0], $orderBy[1])->paginate(PAGINATE);
		}
		if (Admin::isReporter()) {
			$data = $data->whereIn('status', [SCRATCH_PAPER, BACK])
				->orderBy($orderBy[0], $orderBy[1])->paginate(PAGINATE);
		}
		return $data;
	}
	public static function searchAdminGameSortBy($input)
	{
		$sortBy = 'id';
		$sort = 'desc';
		if($input['sortByCountView'] != '') {
			if($input['sortByCountView'] == 'count_view_asc') {
				$sortBy = 'count_view';
				$sort = 'asc';
			}
			if($input['sortByCountView'] == 'count_view_desc') {
				$sortBy = 'count_view';
				$sort = 'desc';
			}
		}
		return [$sortBy, $sort];
	}

	public static function getHomeNews($typeId = null)
	{
		if(getDevice() == MOBILE) {
			$limit = HOME_MOBILE_PAGINATE;
			$offset = LIMIT_HIGHTLIGHT_MOBILE;
		} else {
			$limit = HOME_PAGINATE;
			$offset = LIMIT_HIGHTLIGHT_PC;
		}
		$data = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
				->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.sapo as sapo')
				->where('news.start_date', '<=', Carbon\Carbon::now())
				->where('type_news.status', ENABLED);
		if($typeId) {
			$data = $data->where('news.type_new_id', $typeId);
			$hot = self::getNewsHighlightIdArray($typeId);
			$data = $data->whereIn($hot);
		} else {
			$data = $data->where('news.index', '!=', INACTIVE);
		}
		$data = $data->orderBy('news.weight_number', 'asc')
				->orderBy('news.start_date', 'desc')
				->limit($limit)
				->offset($offset)
				->get();
		return $data;
	}

	public static function getNewsHighlight($typeId = null)
	{
		if(getDevice() == MOBILE) {
			$limit = LIMIT_HIGHTLIGHT_MOBILE;
		} else {
			$limit = LIMIT_HIGHTLIGHT_PC;
		}
		$data = AdminNew::join('type_news', 'news.type_new_id', '=', 'type_news.id')
				->select('news.id as id', 'news.slug as slug', 'type_news.slug as slugType', 'type_news.name as nameType', 'news.title as title', 'news.description as description', 'news.image_url as image_url', 'news.sapo as sapo')
				->where('news.start_date', '<=', Carbon\Carbon::now())
				->where('type_news.status', ENABLED);
		if($typeId) {
			$data = $data->where('news.type_new_id', $typeId);
			$data = $data->where('news.is_hot', ACTIVE);
		} else {
			$data = $data->where('news.index', '!=', INACTIVE);
		}
		$data = $data->orderBy('news.weight_number', 'asc')
				->orderBy('news.start_date', 'desc')
				->limit($limit)
				->get();
		return $data;
	}

	public static function getNewsHighlightIdArray($typeId = null)
	{
		$array = array();
		if(isset($typeId)) {
			$data = self::getNewsHighlight($typeId);
			if(count($data) > 0) {
				foreach($data as $key => $value) {
					$array[$key] = $value->id;
				}
			}
		}
		return $array;
	}

	public static function getNameStatusNewEdit($userId)
	{
		$userRole = Admin::find($userId)->role_id;
		if (in_array($userRole, [ADMIN, EDITOR])) {
			$array = array(
				APPROVE => 'Đăng bài',
				NO_APPROVE => 'Chưa đăng bài'
			);
			return $array;
		} else {
			$array = array(
				SEND => 'Chưa phê duyệt',
				APPROVE => 'Phê duyệt',
				REJECT => 'Hủy',
				BACK => 'Gửi lại PV',
			);
			return $array;
		}
	}
	public static function getNameStatusNewCreate()
	{
		if (Admin::isAdmin() || Admin::isEditor()) {
			$array = array(
				APPROVE => 'Đăng bài',
				NO_APPROVE => 'Chưa đăng bài'
			);
			return $array;
		} else {
			$array = array(
				SEND => 'Gửi đi',
				SCRATCH_PAPER => 'Lưu tin',
			);
			return $array;
		}
	}
	public static function getNameStatusIndex($status, $userId)
	{
		$userRole = Admin::find($userId)->role_id;
		if ($status == APPROVE) {
			if (in_array($userRole, [ADMIN, EDITOR])) {
				return 'Đăng bài';
			} else {
				return 'Đã phê duyệt';
			}
		}
		if ($status == NO_APPROVE) {
			return 'Chưa đăng bài';
		}
		if ($status == SEND) {
			return 'Chờ phê duyệt';
		}
		if ($status == REJECT) {
			return 'Huỷ';
		}
		if ($status == BACK) {
			return 'Trả lại bài viết';
		}
		if ($status == SCRATCH_PAPER) {
			return 'Nháp';
		}
	}

	public static function checkUserRole($userId)
	{
		$userRole = Admin::find($userId)->role_id;
		if ($userRole == REPORTER) {
			return false;
		}
		return true;
	}

	public static function getUserName($userId)
	{
		$userRole = Admin::find($userId)->role_id;
		if ($userRole == REPORTER) {
			return 'Phóng viên';
		}
		return 'Editor/Admin';
	}
}