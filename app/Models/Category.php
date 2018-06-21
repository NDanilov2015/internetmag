<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
  * Модель категорий товара. Категория может содержать много товаров... 
  *
  */
class Category extends Model
{
	protected $table = "categories";
	
	public function items()
	{
		//Может заменить на hasMany() ?
		return $this->belongsToMany('App\Models\Item');
	}
}