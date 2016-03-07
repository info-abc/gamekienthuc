<?php
/**
* 
*/
class AdCommon 
{
	public static function getNameClassAd()
	{
		$array = [];
		$advertise = Advertise::where('model_name', 'TypeNew')
			->whereNull('relate_id')->lists('model_id');
		foreach ($advertise as $value) {
			$array[$value] = TypeNew::find($value)->name;

		}
		return $array;
	}
	public static function getPositionClassAd()
	{
		return [
			POSITION_HEADER => 'Header' , 
			POSITION_RIGHT => 'Cột phải', 
			POSITION_NEWS_GAMES => 'Footer tin trên game', 
			POSITION_GAMES_GAMES => 'Giữa game', 
			POSITION_STICKY_LEFT => 'Banner trôi trái', 
			POSITION_STICKY_RIGHT => 'Banner trôi phải',
		];
	}
	public static function getNamePositionClassAd($value)
	{
		$array = self::getPositionClassAd();
		return $array[$value];
	}
}