<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TypeNew extends Eloquent
{
	use SoftDeletingTrait;
    protected $table = 'type_news';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];

    public function news()
    {
        return $this->hasMany('AdminNew', 'type_new_id', 'id');
    }
}