<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ClientOrder;
use App\Models\ClientOrderComment;

class OrdersController extends Controller
{
	
	public $order_status_labels = [
			'paid' => 'info',
			'done' => 'success',
			'cancelled' => 'danger',
			'proceeding' => 'warning',
	];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$order = ClientOrder::find($id);
		$order_comment = $order->comments()->first();
		
		if (!empty($order_comment)) {
			$comment_text = $order_comment->comment_text;
		} else {
			$comment_text = '';
		}
		
		$order_status_label = $this->order_status_labels[$order->order_status];
		
		$items = json_decode($order->items);
		
		$pageurl = "";
		
        return view('dashboard.orders.show', ['pageurl' => $pageurl, 'order' => $order, 'order_status_label' => $order_status_label, 'comment_text' => $comment_text, 'items' => $items]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Edit order status? By AJAX or non-AJAX?
		//Edit in the same view
    }

    /**
     * Update "status" parameter in storage of the specified order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $newstatus)
    {
        $order = ClientOrder::find($id);
		$order->order_status = $newstatus;
		$order->save();
		
		return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	/* Useful service functions */
	public function loadOrdersAJAX()
	{
		//How can I page it? How i paged base items?
		//No, paging in JS code. In this code all items get from DB
		$orders = ClientOrder::all();
		
		$iTotalRecords = $orders->count();
		$iDisplayLength = intval($_REQUEST['length']); //ћожно ли избавитьс€ от этого безобрази€?
		
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
		$iDisplayStart = intval($_REQUEST['start']);
		$sEcho = intval($_REQUEST['draw']);
		  
		$records = array();
		$records["data"] = array(); 

		$end = $iDisplayStart + $iDisplayLength;
		$end = $end > $iTotalRecords ? $iTotalRecords : $end;

	   
		for($i = $iDisplayStart; $i < $end; $i++) {
			
			$status_text = $orders[$i]->order_status;
			$status_class = $this->order_status_labels[$status_text];
			
			//$id = ($i + 1);
			$id = $orders[$i]->id;
			
			$records["data"][] = array(
			  '<input type="checkbox" name="id[]" value="'.$id.'">',
			  $id,
			  date('d M Y - H:i:s', $orders[$i]->updated_at->timestamp),
			  $orders[$i]->fullname,
			  $orders[$i]->country,
			  priceStdFormat($orders[$i]->subtotal),
			  $orders[$i]->total,
			  '<span class="label label-sm label-'.$status_class.'">'.ucfirst($status_text).'</span>',
			  '<a href="orders/' . $id . '" class="btn btn-xs default btn-editable"><i class="fa fa-search"></i> View</a>',
			);
		  }

		  if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
			$records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
			$records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
		  }

		  $records["draw"] = $sEcho;
		  $records["recordsTotal"] = $iTotalRecords;
		  $records["recordsFiltered"] = $iTotalRecords;
		  
		  echo json_encode($records);
	}
}
