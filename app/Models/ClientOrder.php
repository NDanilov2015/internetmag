<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientOrder extends Model
{
    protected $table = 'orders';
	
	public function comments()
	{
		return $this->hasMany('App\Models\ClientOrderComment');
	}
}
