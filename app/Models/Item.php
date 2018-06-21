<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
  * ������ ���������� ������, ������������� ������ ������� 
  *
  */
class Item extends Model
{
	protected $table = 'items'; //��������� � ������� �������
	
	public function categories()
	{
		//������������ 1 ����� ����� ���������� � ���������� ����������
		return $this->belongsToMany('App\Models\Category');
	}
	
	public function viewPrice()
	{
		//return "$" . number_format($this->price/100, 2);
		return money_format('%i', $this->price / 100);
	}
	
	public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }
	
	public function scopeFindByCategorySlug($query, $categorySlug)
	{
		return $query->whereHas('items', function ($query) use ($categorySlug) {
			$query->where('slug', $categorySlug);
		})->get();
	}
}