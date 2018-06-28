<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
	public function show($id)
	{
		return view('orders.orderview-full', ['order_id' => $id]);
	}
}
