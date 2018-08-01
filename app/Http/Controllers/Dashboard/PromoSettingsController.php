<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\ItemAction;

class PromoSettingsController extends Controller
{
    /**
     * Display a listing of action items
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$promoitems = Item::where('featured', "1")->where('item_status', "published")->get();
		
		$checkmaps = ItemAction::all(); //Intersect need with Items set, I believe
		
		//dd($checkmaps->where('item_id', "61")->first()->thirdweek); //dump and exit
		
		return view('dashboard.promosettings.index', ['promoitems' => $promoitems, 'checkmaps' => $checkmaps]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $update_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		//May be action type use? (manual update, load default, reset all action)
		$firstweek = $request->input('firstweek');
		$secondweek = $request->input('secondweek');
		$thirdweek = $request->input('thirdweek');
		$fourthweek = $request->input('fourthweek');
		
		ItemAction::truncate(); //Reset all table rows
		
		if (!empty($firstweek)) {
			foreach ($firstweek as $item_id) {
				$item_promo = new ItemAction(); //New row of table
				$item_promo->item_id = $item_id;
				$item_promo->firstweek = true;
				$item_promo->save();
			}
		}
		
		if (!empty($secondweek)) {
			foreach ($secondweek as $item_id) {
				$item_promo = new ItemAction(); //New row of table
				$item_promo->item_id = $item_id;
				$item_promo->secondweek = true;
				$item_promo->save();
			}
		}
		
		if (!empty($thirdweek)) {
			foreach ($thirdweek as $item_id) {
				$item_promo = new ItemAction(); //New row of table
				$item_promo->item_id = $item_id;
				$item_promo->thirdweek = true;
				$item_promo->save();
			}
		}
		
		if (!empty($fourthweek)) {
			foreach ($fourthweek as $item_id) {
				$item_promo = new ItemAction(); //New row of table
				$item_promo->item_id = $item_id;
				$item_promo->fourthweek = true;
				$item_promo->save();
			}
		}
		
		return redirect()->back();
    }

    /**
     * Cancel all promo actions
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
