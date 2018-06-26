@extends('layouts.front')

@section('title', 'Customer cart!')

@section('content')
    <div class="cart-section container">
        <div>
            @if (count($items) > 0)

            <h2>{{ count($items) }} item(s) in Shopping Cart</h2>

            <div class="cart-table">
                @foreach ($items as $item)
                <div class="row border border-bottom-0">                    
                    <!-- Left column -->
                    <div class="col-md-6">
                        <a href="{!! action('HomeController@show', $item->slug) !!}"><img src="{{ productImage($item->model->image) }}" alt="item" class="cart-table-img" style="width: 50px;"></a>
                        <div class="cart-item-details">
                            <div class="cart-table-item"><a href="{{ action('HomeController@show', $item->slug) }}">{{ $item->name }}</a></div>
                            <div class="cart-table-description">{{ $item->model->description }}</div>
                        </div>
                    </div>
					
					<!-- Right column -->
                    <div class="col-md-6 d-flex align-items-center">
                    	<div class="row d-flex justify-content-around" style="width: 90%;">
                    		<div class="col-md-4">
                    			<div>
                    			<a href="{!! action('CartController@delete', $item->itemHash) !!}" class="btn btn-danger btn-removefromcart-bkp" item-id="{{ $item->id }}" item-hash="{{ $item->itemHash }}">Delete item</a>
                    			</div>
                    		</div>
							<div class="col-md-2">&nbsp;&nbsp;</div>
                    		<div class="col-md-3">
                    			<div>
                    			<select class="quantity" item-id="{{ $item->id }}" item-hash="{{ $item->itemHash }}">
                    				@for ($i = 1; $i < 5 + 1 ; $i++)
                    				<option {{ $item->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                    				@endfor
                    			</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div>{{ priceStdFormat($item->subTotal($format = false, $withDiscount = false, $taxedItemsOnly = false, $withTax = false)) }}
                    			</div>
                    		</div>
                    	</div>
                    </div>
                </div> <!-- end cart-table-row -->
                @endforeach

            </div> <!-- end cart-table -->

			<!-- Coupone block -->
			<div class="row border">
				<div class="col-md-6">
					<p><b>Do you have discount coupone?</b></p>
				</div>
				<div class="col-md-6 mt-1 mb-1">
                	<form action="{!! action('CartController@applyCoupone') !!}" method="POST">
                        {{ csrf_field() }}
                        <input type="text" name="coupone_code" id="coupone_code">
                        <button type="submit" class="btn btn-success">Apply</button>
                    </form>
                </div>
                <div class=""></div>
        	</div>

			<!-- Cart total block -->
            <div class="row alert alert-info">

                <div class="col-md-2">
                    Our shipping is free because it's virtual product :)
                </div>

                <div class="col-md-6">
                    <b>Subtotal (before coupons and taxes):</b>
                    <br>
                    @if (!empty(LaraCart::getCoupons()))
                    	@foreach (LaraCart::getCoupons() as $key => $value)
                        	Your apply discount code: {{ $value->code }}
	                        <form action="{!! action('CartController@removeCoupone') !!}" method="POST" style="display:inline;">
	                            {{ csrf_field() }}
	                            <button type="submit" class="btn btn-danger">Remove</button>
	                        </form>
                        	<br>
                        @endforeach
                        New Subtotal (after coupons) <br>
                    @endif
                    Tax ({{ (float)config('laracart.tax') }} %)<br>
                    <b>Total:</b>
                </div>
                    
                <div class="col-md-4">
                    {{ priceStdFormat($subTotal) }} <br>

                    @if (!empty(LaraCart::getCoupons()))
                        Discount: {{ $discount * 100 }} % <br>
                        <br>
                        {{ priceStdFormat($afterDiscount) }} <br>
                    @endif
                    
                    {{ priceStdFormat($taxValue) }} <br>
                    <b>{{ priceStdFormat($total) }}</b>
                </div>
            </div>
            <!-- end cart-totals -->

            <!-- 16.06.2018 - workFront at this point -->

            <div class="cart-buttons">
                <a href="{!! action('HomeController@index') !!}" class="btn btn-success">Continue Shopping</a>
                <a href="{!! action('CheckoutController@choiceWallet') !!}" class="btn btn-warning">Checkout Products</a>
                <a href="{!! action('CartController@destroy') !!}" class="btn btn-danger">Empty Cart</a>
            </div>

            @else

                <h3>No items in Cart!</h3>
                <div class="spacer"></div>
                <a href="{!! action('HomeController@index') !!}" class="btn btn-success">Continue Shopping</a>
                <div class="spacer"></div>

            @endif

        </div>

    </div> <!-- end cart-section -->

<script>
        $(document).on('change', '.quantity', function(event) {
        	let thiskey = $(this);
        	let new_qty = thiskey.find("option:selected").val();
			let item_id = thiskey.attr('item-id');
        	let item_hash = thiskey.attr('item-hash');

        	$.ajax({
	           url : '{{ url("cart/changeQty") }}',
	           method : "POST",
	           data : { item_id, item_hash, new_qty, _token: "{{ csrf_token() }}" },
	           dataType : "text",
	           success : function(data)
	           {
	              //refreshCartButton(); //При перезагрузке страницы итак вызов будет
				  window.location.href = '{!! action('CartController@index') !!}';
	           },
	           error: function(errmsg) {
	              console.log(errmsg.responseText)
	           }
       		});
        });
  </script>

@endsection

{{-- @section('extra-js') --}}
    
{{-- @endsection --}}