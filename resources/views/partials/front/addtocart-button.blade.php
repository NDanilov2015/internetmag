{{-- 
	*Если товар не в корзине, тогда показываем кнопку "Добавить"
	*Если товар уже в корзине, отображаем факт этого дела
	*
	*Эта схема выручает от исчезновения измененности кнопки при перезагрузке страницы
--}}

@if(!LaraCart::find(['id' => $item->id]))
<a href="#" class="btn btn-danger btn-addtocart" item-id="{{ $item->id }}">
	Add to cart
</a>
@else
<a href="/cart" class="btn btn-warning btn-addtocart fa fa-check" item-id="{{ $item->id }}">
	Buy again ({{ LaraCart::find(['id' => $item->id])->qty }})
</a>
@endif