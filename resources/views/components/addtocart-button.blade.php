{{-- 
	*Если товар не в корзине, тогда показываем кнопку "Добавить"
	*Если товар уже в корзине, отображаем факт этого дела
	*
	*Эта схема выручает от исчезновения измененности кнопки при перезагрузке страницы
--}}

@if(!LaraCart::find(['id' => $item->id]))
<a href="#" class="btn btn-danger btn-addtocart" item-id="{{ $item->id }}">
	<span class="fa"></span>
	Add to cart
</a>
@else
<a href="/cart" class="btn btn-warning btn-gotocart fa fa-check" item-id="{{ $item->id }}">
	<span class="fa"></span>
	You take it
</a>
@endif