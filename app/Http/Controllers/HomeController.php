<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	protected $pagination = 6;
	
    /**
     * Вывод всех элементов без категорий
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
	 * Листинг конкретного сегмента каталога
	 * $cat_slug unique()
	 */
	public function catIndex($cat_slug)
	{
		$category = Category::whereSlug($cat_slug)->firstOrFail();
		$items = $category->items()->paginate($this->pagination); //Уже не модели, а сразу данные
		
		//Возвращаем id по slug и товар добываем?
		return view('index')->with([
            'items' => $items,
            'category' => $category,
        ]);
	}
	
	/**
     * Отображает конкретный продукт
	 * Это режим Detail View
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($cat_slug, $item_slug)
    {
        //$category = Category::whereSlug($cat_slug)->firstOrFail();
		
		$item = Item::where('slug', $item_slug)->firstOrFail();
        
		//Поиск подобных товаров вот здесь:
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
		
        //Это на случай главной страницы)
		$objCat = new \stdClass();
		$objCat->slug = '';
		
		//Массив в котором есть и id, и slug
		$id = $request->id;
		$categoryRequest = $request->category ? json_decode($request->category) : $objCat;
		$cat_slug = $categoryRequest->slug;
		
		/* Как быть если у нас нет категории, а? А ссылки всё равно генерить истинные надо! */
		
		$needBtnMore = false;
		
		if ($cat_slug !== '') {
			//Для страницы категорий то есть
			//Получаем объект-коллекцию с данными вида {id: 1, name: "Laptops", slug: "laptops", created_at: "2018-06-01 14:16:10", updated_at: "2018-06-01 14:16:10"}... и кучей методов
			$category = Category::whereSlug($cat_slug)->firstOrFail();
			//Получаем все Collection $items из данной категории с id больше текущего
			$items = $category->items()->get()->where('id', '>', $id);
		} else {
			//Для главной страницы
			$items = Item::all()->where('id', '>', $id);
		}
		
		if ($items->count() > $this->pagination) {
			$items = $items->take($this->pagination);
			$needBtnMore = true; //Если порция элементов больше чем лимит паджинации, то снова кнопку Button More
		}			
		//А на главной странице что будет?
		
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
				/* PHP 7 тернарка + isset = null coalescence operator
				 * То есть если $category не null, тогда её. Иначе ''
				 */
				$outputCardMode .= view('partials.front.showmore-button', ['viewmode' => 'cardview', 'item' => $item, 'category' => $category ?? ''])->render();
				$outputListMode .= view('partials.front.showmore-button', ['viewmode' => 'listview', 'item' => $item, 'category' => $category ?? ''])->render();
			}
				
			return ['cardView' => $outputCardMode, 'listView' => $outputListMode];
        }
    } //end function loadDataAjax()
	
	/**
	 * Быстрый просмотр товара
	 *
	 */
	public function quickViewAjax(Request $request)
	{
		$item_id = $request->item_id;		
		$item = Item::where('id', $item_id)->firstOrFail();
        
		//Поиск подобных товаров вот здесь:
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
