<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\ClientOrderComment as Comment;

use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
	public function show($order_id)
	{
		$order = ClientOrder::find($order_id); //type Model, not Collection
		$items = json_decode($order->items); //We encoded <items package> to JSON in form hidden input tag
				
		$comment = $order->comments()->first();
		
		if (!empty($comment)) {
			$comment_text = $comment->comment_text;
		} else {
			$comment_text = '';
		}
		
		return view('orders.orderview-full', ['order' => $order, 'items' => $items, 'comment' => $comment_text]);
	}
	
	public function addComment(Request $request)
	{
		$comment = new Comment(array('comment_text' => $request->get('order_comment_text')));
		
		$order_id = $request->get('order_id');
		$order = ClientOrder::find($order_id);
		
		$order->save();
		$order->comments()->save($comment);
		
		return redirect(action('ClientOrderController@show', ['order_id' => $order_id])); //Return to this page
	}
}
