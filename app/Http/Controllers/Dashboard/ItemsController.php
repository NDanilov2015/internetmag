<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\Category;
use Debugbar;

class ItemsController extends Controller
{
	
	public $item_status_labels = [
		'published' => 'success',
		'unpublished' => 'warning',
		'deleted' => 'danger',
	];
	
    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//AJAX loop - from View AJAX call another method of this controller
        return view('dashboard.products.index');
    }

    /**
     * Show the form for creating a new item
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$item = Item::find($id);
		$item_categories = $item->categories()->get(); //get() on QueryBuilder give Models array
		
        return view('dashboard.products.edit', ['item' => $item, 'item_categories' => $item_categories]);
    }

    /**
     * Update the invividual Item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		//dd($request);
		
		$item = Item::find($id);
		//Update data in model from out POST request
		$item->name = $request->product['name'];
		$item->description = $request->product['description'];
		$item->price = filter_var($request->product['price'], FILTER_SANITIZE_NUMBER_INT);
		$item->item_status = $request->product['status'];
		//SEO parameters
		$item->meta_title = $request->product['meta_title'];
		$item->meta_keywords = $request->product['meta_keywords'];
		$item->meta_description = $request->product['meta_description'];
		//Saving updating product to database
		$item->save();
		
		$item->categories()->sync($request->product['categories']);
		//Reset old category <-> item many to many relations
		
		//Saving updating category system to db through belongsToMany()
		/*
		foreach ($request->product['categories'] as $category_id) {
			$item->categories()->save(Category::find($category_id));
		}
		*/
		
		if ($request->operation_button == "save" ) {
			return redirect(action('Dashboard\ItemsController@index')); //Как мы раньше возвращали?
		}
		
		if ($request->operation_button == "save_and_continue") { return redirect()->back(); }
		
		return redirect(action('Dashboard\ItemsController@index'));
    }
	
	/**
	 * Bulk updating of "promo" attributes
	 *
	 */
	public function updatePromoAJAX(Request $request)
	{
		$items_ids = $request->items_ids; //array of items ids
		
		foreach ($items_ids as $id) {
			$item = Item::find($id);
			if ($request->action == "setpromoted") { $item->featured = true; }
			if ($request->action == "unpromoted") { $item->featured = false; }
			$item->save();
		} //end foreach
		
		echo "updatePromoAJAX method call()";
	}
	
	/**
	 * Bulk updating of "status" attributes
	 *
	 */
	public function updateStatusAJAX(Request $request)
	{
		$items_ids = $request->items_ids; //array of items ids
		
		foreach ($items_ids as $id) {
			$item = Item::find($id);
			if ($request->action == "setpublished") { $item->item_status = 'published'; }
			if ($request->action == "unpublished") { $item->item_status = 'unpublished'; }
			if ($request->action == "setdeleted") { $item->item_status = 'deleted'; }
			$item->save();
		} //end foreach
		
		echo "updateStatusAJAX method call()";
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
	public function loadItemsAJAX(Request $request)
	{		
		$items = Item::all(); //Return full "Collection" of items
				
		//action = filter || filter_cancel
		if ($request->action == "filter") {
			
			/* Category filter */
			if ($request->product_category != null) {
				$category = Category::find($request->product_category);
				$items = $category->items()->get();
			}
			
			/* Special promotion filter */
			if (($request->specialpromo_filter != null) && ($request->specialpromo_filter == 'promoted')) {
				$items = $items->where('featured', "1");
			}
			
			if (($request->specialpromo_filter != null) && ($request->specialpromo_filter == 'notpromoted')) {
				$items = $items->where('featured', "0");
			}
			
			/* Status published/unpublished filter */
			if (($request->product_status_filter != null) && ($request->product_status_filter == 'published')) {
				$items = $items->where('item_status', "published");
			}
			
			if (($request->product_status_filter != null) && ($request->product_status_filter == 'notpromoted')) {
				$items = $items->where('item_status', "unpublished");
			}
			
			if (($request->product_status_filter != null) && ($request->product_status_filter == 'deleted')) {
				$items = $items->where('item_status', "deleted");
			}	
		}//end filter system				 
		
		$items = array_values($items->toArray()); //Continuous numbered array... of arrays, not Model "Item" objects!
		$iTotalRecords = count($items);
			
		$iDisplayLength = intval($_REQUEST['length']);
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
		$iDisplayStart = intval($_REQUEST['start']);
		$sEcho = intval($_REQUEST['draw']);
	  
		$records = array();
		$records["data"] = array(); 

		$end = $iDisplayStart + $iDisplayLength;
		$end = $end > $iTotalRecords ? $iTotalRecords : $end;
		
		for($i = $iDisplayStart; $i < $end; $i++) {

			$status_text = $items[$i]['item_status'];
			$status_class = $this->item_status_labels[$status_text];
			
			$spec_promo_text = $items[$i]['featured'] ? "in promo" : "not in promo";
			$spec_promo_class = $items[$i]['featured'] ? "success" : "info";
			
			$category = Item::find($items[$i]['id'])->categories()->first()->name;
			
			//$id = ($i + 1);
			$id = $items[$i]['id'];

			$records["data"][] = array(
			  '<input type="checkbox" name="id[]" value="'.$id.'">',
			  $i, //Item number in frontend list, not product ID!
			  $id,
			  $items[$i]['name'],
			  $category,
			  priceStdFormat($items[$i]['price']),
			  //date('d M Y', $items[$i]->updated_at->timestamp),
			  $items[$i]['updated_at'],
			  '<span class="label label-sm label-'.$spec_promo_class.'">'.ucfirst($spec_promo_text).'</span>',
			  '<span class="label label-sm label-'.$status_class.'">'.ucfirst($status_text).'</span>',
			  '<a href="products/'.$id.'/edit" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Edit</a>',
			);
		}//end for

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
