<html lang="en">

@include('partials.front.head')

<body style="width: 97%;">
  <!-- BEGIN CONTAINER -->
  <div class="container">

   <div class="row">

    <div class="col-md-12">
      <nav class="navbar navbar-expand-md bg-success justify-content-between">
        <a href="/" class="navbar-brand text-white">InetMAG</a>
        <form class="form-inline">
          <span class="fa fa-search bg-white"></span>
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <a href="/cart" class="btn btn-danger" id="cart-button"><span class="fa fa-shopping-basket"></span>
			&nbsp;
			<span id="cart-button-data-area">
				<!-- Здесь AJAX вставляет количество товаров, полную сумму -->
				<!-- Если же корзина пуста, то Empty Cart выводит -->
			</span>
		</a>
		
		<a href="/auth/login" class="btn btn-info text-white">Login to admin dash</a>
      </nav>
    </div>
	
	<script type="text/javascript">
	/* Отображает сверхкраткую статистику корзины
	 * в заголовке окна - кнопка Cart.
	 * см. шаблон layouts.front
	 */
	function refreshCartButton() {
		
		var cartButtonArea = $('#cart-button > #cart-button-data-area');
		
		$.ajax({
           url : '{{ url("refreshCartButton") }}',
           method : "POST",
           data : { _token: "{{ csrf_token() }}" }, //Токен чтобы враги не послали POST-запрос
           dataType : "text",
           success : function(data)
           {
			  //Очищаем кнопку добавить в корзину
			  cartButtonArea.empty();
			  
			  console.log(data);
			  
			  //JSON.parse требовал UTF-8, на Windows это проблемное дело
			  objData = JSON.parse(data);
              //Тип данных = string, который на деле object
              if(objData.count > 0)
              {
                //console.log(data);
				cartButtonArea.html(
					'<span class="bg-warning text-dark">&nbsp;'+objData.total+'&nbsp;</span>'
					+'<span>&nbsp;&nbsp;</span>'+
					'<span class="bg-warning text-dark">&nbsp;'+objData.count+'&nbsp;</span>'
					);
              } else {
				cartButtonArea.text("Empty Cart");
			  }
           },
           error: function(errmsg) {
              console.log(errmsg.responseText)
           }
       });
	}
	</script>
  
    <div class="row mt-4 ml-0 mr-0">
      
      <div class="col-md-2">
        @include('leftmenu')
      </div>

      <div class="col-md-10">

        <!-- —делай эту строку на всю ширину -->
        <div class="row mb-5">
          <div class="col-md-12">
            @include('slideractions')
          </div>
        </div>

        <!-- Page main content area -->
        <div class="row mt-5">
          @yield('content')
        </div>

      </div><!-- end col-md-10 -->

    </div>
  </div>
</div>
<!-- END CONTAINER -->
  @include('partials.front.footer')
</body>
</html>