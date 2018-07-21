<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientOrderComment extends Model
{
    protected $table = 'orders_comments'; //Comments table, non-pivot
	
	protected $fillable = ['comment_text']; //Can be mass-assignable
	
	public function order()
	{
		return $this->belongsTo('App\Models\ClientOrder');
	}
}
