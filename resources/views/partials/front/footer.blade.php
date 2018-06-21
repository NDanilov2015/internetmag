<div class="row mt-4 bg-success">
    <div class="col-md-12">
      @include('partials.front.footermenu')
    </div>
  </div>
</div>

<!-- Обработка клика по кнопкам "В корзину" -->
<script type="text/javascript">
$(document).ready(function() {
	
	//Определена в layout.front
	refreshCartButton(); //При перезагрузке страницы отображение данных в кнопке корзины... и при клике
		
	$(document).on('click','.btn-addtocart', function(event) {
		event.preventDefault(); //Чтобы окно не скакало от кликов по ссылке...
		
		var thiskey = $(this);
		item_id = thiskey.attr('item-id');
		
		$.ajax({
           url : '{{ url("addItemByAJAX") }}',
           method : "POST",
           data : { item_id, _token:"{{ csrf_token() }}" },
           dataType : "text",
           success : function(data)
           {
              //Тип данных = string, который на деле object
              if(data != '')
              {
				  //Очищаем кнопку у товара/итема карточки товара добавить в корзину
				  //Здесь дублирование кода конечно, но спасает от много чего. Ладно.
				  thiskey.empty();
				  thiskey.removeClass("btn-danger btn-addtocart");
				  thiskey.addClass("btn-warning btn-gotocart fa fa-check");
				  thiskey.attr('href', '/cart');
				  thiskey.text("You take it"); //Галочку бы еще добавить) 11 символов чтобы длина кнопки та же
				  
				  //Обновляем Главную Кнопку корзины в верхней панели
				  refreshCartButton();
              }
           },
           error: function(errmsg) {
              console.log(errmsg.responseText)
           }
       });
		
		
	});
});
</script>
		
<!-- Optional JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
