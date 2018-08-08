<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	protected $pagination = 6;
	
    /**
     * ����� ���� ��������� ��� ���������
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$items = Item::paginate($this->pagination);
		$categoryPath = ''; //All items
		
        return view('index')->with([
            'items' => $items,
            'category' => $categoryPath,
        ]);
    }

	
	/**
	 * ������� ����������� �������� ��������
	 * $cat_slug unique()
	 */
	public function catIndex($cat_slug)
	{
		$category = Category::whereSlug($cat_slug)->firstOrFail();
		$items = $category->items()->paginate($this->pagination); //��� �� ������, � ����� ������
		
		//���������� id �� slug � ����� ��������?
		return view('index')->with([
            'items' => $items,
            'category' => $category,
        ]);
	}
	
	/**
     * ���������� ���������� �������
	 * ��� ����� Detail View
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($cat_slug, $item_slug)
    {
        //$category = Category::whereSlug($cat_slug)->firstOrFail();
		
		$item = Item::where('slug', $item_slug)->firstOrFail();
        
		//����� �������� ������� ��� �����:
		$mightAlsoLike = Item::where('slug', '!=', $item_slug)->mightAlsoLike()->get();

        return view('item')->with([
            'item' => $item,
            'mightAlsoLike' => $mightAlsoLike,
        ]);
    }
	
	/**
	 * Show more... button AJAX realization
	 *
	 */
	public function loadDataAjax(Request $request)
    {
        $outputCardMode = '';
		$outputListMode = '';
		
        //��� �� ������ ������� ��������)
		$objCat = new \stdClass();
		$objCat->slug = '';
		
		//������ � ������� ���� � id, � slug
		$id = $request->id;
		$categoryRequest = $request->category ? json_decode($request->category) : $objCat;
		$cat_slug = $categoryRequest->slug;
		
		/* ��� ���� ���� � ��� ��� ���������, �? � ������ �� ����� �������� �������� ����! */
		
		$needBtnMore = false;
		
		if ($cat_slug !== '') {
			//��� �������� ��������� �� ����
			//�������� ������-��������� � ������� ���� {id: 1, name: "Laptops", slug: "laptops", created_at: "2018-06-01 14:16:10", updated_at: "2018-06-01 14:16:10"}... � ����� �������
			$category = Category::whereSlug($cat_slug)->firstOrFail();
			//�������� ��� Collection $items �� ������ ��������� � id ������ ��������
			$items = $category->items()->get()->where('id', '>', $id);
		} else {
			//��� ������� ��������
			$items = Item::all()->where('id', '>', $id);
		}
		
		if ($items->count() > $this->pagination) {
			$items = $items->take($this->pagination);
			$needBtnMore = true; //���� ������ ��������� ������ ��� ����� ����������, �� ����� ������ Button More
		}			
		//� �� ������� �������� ��� �����?
		
		if(!$items->isEmpty())
        {
            foreach($items as $item)
            {
                //$url = url('catalog/' . $item->slug);
                $body = substr(strip_tags($item->description), 0, 500);
                $body .= strlen(strip_tags($item->description)) > 500 ? "..." : "";
                
				$outputCardMode .= view('item-brief-card', ['item' => $item])->render();
				$outputListMode .= view('item-brief-listitem', ['item' => $item])->render();
			}
            
			if ($needBtnMore) {
				/* PHP 7 �������� + isset = null coalescence operator
				 * �� ���� ���� $category �� null, ����� �. ����� ''
				 */
				$outputCardMode .= view('partials.front.showmore-button', ['viewmode' => 'cardview', 'item' => $item, 'category' => $category ?? ''])->render();
				$outputListMode .= view('partials.front.showmore-button', ['viewmode' => 'listview', 'item' => $item, 'category' => $category ?? ''])->render();
			}
				
			return ['cardView' => $outputCardMode, 'listView' => $outputListMode];
        }
    } //end function loadDataAjax()
	
	/**
	 * ������� �������� ������
	 *
	 */
	public function quickViewAjax(Request $request)
	{
		$item_id = $request->item_id;		
		$item = Item::where('id', $item_id)->firstOrFail();
        
		//����� �������� ������� ��� �����:
		$mightAlsoLike = Item::where('id', '!=', $item_id)->mightAlsoLike()->get();

        return view('item-showcontent')->with([
            'item' => $item,
            'mightAlsoLike' => $mightAlsoLike,
			'needSimilarItemsShow' => false,
        ]);
	}
	
	/*
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3',
        ]);

        $query = $request->input('query');

        // $products = Product::where('name', 'like', "%$query%")
        //                    ->orWhere('details', 'like', "%$query%")
        //                    ->orWhere('description', 'like', "%$query%")
        //                    ->paginate(10);

        $products = Product::search($query)->paginate(10);

        return view('search-results')->with('products', $products);
    }

    public function searchAlgolia(Request $request)
    {
        return view('search-results-algolia');
    }
	*/
}
