<div class="row mt-4 bg-success">
    <div class="col-md-12">
	  <ul class="nav justify-content-center">
		  <li class="nav-item">
			<a class="nav-link text-white" href="#">About US</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link text-white" href="#">Terms & conditions</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link text-white" href="#">User Agreement</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link text-white" href="#">FAQ</a>
		  </li>
	  </ul>
    </div>
  </div>
</div>

<!-- Обработка клика по кнопкам "В корзину" -->
<script type="text/javascript">
$(document).ready(function() {
	
	//Определена в layout.front
	refreshCartButton(); //При перезагрузке страницы отображение данных в кнопке корзины в хедере... и при клике
		
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
				  let jsonData = JSON.parse(data);
				  //Очищаем кнопку у товара/итема карточки товара добавить в корзину
				  //Здесь дублирование кода конечно, но спасает от много чего. Ладно.
				  thiskey.empty();
				  thiskey.removeClass("btn-danger");
				  thiskey.addClass("btn-warning fa fa-check");
				  //thiskey.attr('href', '/cart');
				  thiskey.text('Buy again (' + jsonData.qty + ')'); //11 символов чтобы длина кнопки та же
				  
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