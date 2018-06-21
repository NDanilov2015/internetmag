<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use App\Models\Item;
use LukePOLO\LaraCart\Facades\LaraCart as LaraCart;
use LukePOLO\LaraCart\Facades\Cart as Cart;
use LukePOLO\LaraCart\Coupons\Percentage as CouponePercentage;

use Debugbar;

class CartController extends Controller
{
	public $coupones;
	
	public function __construct()
	{
		//Пока возможные купоны жёстко вшиваем в конструкторе, затем через интерфейс будем вводимть
		//Формат: [code] [discount_percent] [description]
		
		$this->coupones = collect([
			new CouponePercentage('CP10PD', '.10', ['description' => '10 % Off Any Purchase!']),
			new CouponePercentage('CP15PD', '.15', ['description' => '15 % Off Any Purchase!']),
			new CouponePercentage('CP20PD', '.20', ['description' => '20 % Off Any Purchase!']),
			new CouponePercentage('CP30PD', '.30', ['description' => '30 % Off Any Purchase!'])
			]);
	}
	
	public function index()
	{
		$items = LaraCart::getItems();
		
		$tax = config('laracart.tax');
		//echo priceStdFormat(LaraCart::total($formatted = false, $withDiscount = true));
		
		//Пока в % только
		$discountsArray = LaraCart::getCoupons();
				
		if (!empty($discountsArray)) {
			$discount = (float) collect($discountsArray)->first()->value; //Величина скидки в процентах
			$code = collect($discountsArray)->first()->code; //Код купона
		} else 
		{
			$discount = 0;
			$code = '';
		}
		
		//subTotal() без скидок. Без формата = чтобы float получить. Хотя sT не даёт скидку вообще
		$subTotal = (float)LaraCart::subTotal($formatted = false, $withDiscount = false);
		
		//А вот со скидками, но без налогов ещё)
		$afterDiscount = (float)LaraCart::total($formatted = false, $withDiscount = true, 
			$withTax = false, $withFees = false);
		
		//Вычисляем какая сумма на типовой налог уйдет
		$taxValue = $afterDiscount * $tax; //Налоги прилагаются уже после скидок в нашем маге)
		
		//Полная сумма со скидкой и налогом (пока вот таким сложным способом)
		$total = LaraCart::total($formatted = false, $withDiscount = true, $withTax = false) - $taxValue;
		
		//$newTotal = $newSubtotal * (1 + $tax);
		
		return view('cart.index')->with([
			'items' => $items,
			'subTotal' => $subTotal, //Полная сумма без скидок и налогов
			'discount' => $discount, //Скидка, полученная по купону
			'afterDiscount' => $afterDiscount, //Подитог без учета скидки. А налоги туда входят (tax)?
			'taxValue' => $taxValue, //Сколько от подитога возьмётся в НДС?
			'total' => $total, //Пока считаем без таксы
		]);
		
	}
	
	/**
	 * Обновление числа элементов 
	 *
	 */
	public function update()
	{
		
	}
	
	/**
	 * Удаляет элемент из корзины
	 *
	*/
	public function delete($item_hash)
	{	
		LaraCart::removeItem($item_hash);

		return redirect(action('CartController@index'));
	}
	
	
	public function destroy(Request $request)
	{
		LaraCart::emptyCart(); //А может лучше вызывать destroyCart() ?
		
		return redirect(action('CartController@index'));
	}
	
	//Каким образом выводить в заголовке окна обновление? Хотя это в JS который за добавление пусть!
	public function addItemByAJAX(Request $request)
	{
		$item = Item::where('id', $request->item_id)->firstOrFail();
		
		LaraCart::add($item);
		/*
		LaraCart::add(
			$item->id,
			$item->name,
			1, //Количество
			$item->price,
			[], //Options,
			false, //$taxable
			false //$lineitem
		);
		*/
		
		$newQuantity = LaraCart::find(["id" => $item->id])->qty;
		
		return ['qty' => $newQuantity];
		//Назад отправлять пакет данных: количество разновидностей товаров в корзине, total
	}
	
	//Хотя прошлый метод лучше в update переименовать наверное?
	
	/**
	 * Изменяет количество товаров данного типа
	 * $request (item_id, item_hash, new_qty)
	 *
	 */
	public function changeQty(Request $request)
	{
		$itemHash = $request->item_hash;
		$new_qty = $request->new_qty;
		
		//Обновляем в статическом экземпляре LC
		$item = LaraCart::getItem($itemHash);
        $item->qty = $new_qty;
		LaraCart::update();
		
		return ["Quantities are updated!", "LaraCart is true"];
	}
	
	public function applyCoupone(Request $request)
	{
		$coupone_code = strtoupper($request->coupone_code);		
		//Ищем совпадения введённого купона с коллекцией купонов
		$coupone_to_add = $this->coupones->where('code', $coupone_code)->first();
		
		//У нас элемент внутри массива объектов вообще-то)
		//Надо получить объект с данным кодом и добавить его к корзине
		//Если уже применены некие купоны - то не надо прилагать
		if (($coupone_to_add) && (empty(LaraCart::getCoupons()))) {
			LaraCart::addCoupon($coupone_to_add);
			//Нет ли опасности повторного добавления купонов?
		} else {
			Debugbar::info('Coupone not found');
		}
		
		//Как ошибки выводить если купон не тот?
		return redirect(action('CartController@index'));
	}
	
	public function removeCoupone(Request $request)
	{
		LaraCart::removeCoupons(); //На этом этапе 1 купон = все купоны
		//Как ошибки выводить если купон не тот?
		return redirect(action('CartController@index'));
	}
	
	/**
	 * Генерация данных для отображения в кнопке "Cart" в заголовке
	 * Функция предназначена для вызова AJAX-запросом
	 */
	public function refreshCartButton(Request $request)
	{
		//Установка $formatted в true вызывает money_format error у нас)
		
		$count = LaraCart::count();
		$total = priceStdFormat(LaraCart::total($formatted = false, $withDiscount = true));
		
		return ['total' => $total, 'count' => $count];
	}
	
}
