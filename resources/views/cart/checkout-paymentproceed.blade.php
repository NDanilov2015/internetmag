@extends('layouts.front')

@section('title', 'Checkout - Step 2!')

@section('content')
<!-- open tag <div row mt-5> -->
<h2>Checkout process: Step 2. Please, fill your data form</h2>

@php
	$items = session()->get('items');
	$subTotal = session()->get('subTotal');
	$discount = session()->get('discount');
	$afterDiscount = session()->get('afterDiscount');
	$taxValue = session()->get('taxValue');
	$total = session()->get('total');

	$true_total = round($total/100, 2); //For payment systems we need float, not string
@endphp

<div class="col-md-5">
	<form method="POST" id="payment-form"  action="{!! action('CheckoutController@doPayPal') !!}">
	  {{ csrf_field() }}

	  <h4>Please, fill your personal data:</h4>
	  
	  <div class="form-group">
	  		<label for="payer-fullname"><span style="color:red;">*</span>Name and Surname</label>
	  		<input type="text" class="form-control" id="payer-fullname" name="payerFullname" placeholder="Donald Tramp" required />
	  </div><!-- end payer-email -->

	  <div class="form-group">
	  		<label for="payer-email"><span style="color:red;">*</span>Email</label>
	  		<input type="email" class="form-control" id="payer-email" name="payerEmail" placeholder="Email" required/>
	  </div><!-- end payer-email -->

	  <div class="form-group">
	    <label for="payer-address">Address</label>
	    <input type="text" class="form-control" id="payer-address" name="payerAddress" placeholder="1234 White House" />
	  </div><!-- end payer-address -->

	  <div class="form-row">
	  	<div class="form-group col-md-6">
	  		<label for="payer-city">City</label>
	  		<input type="text" class="form-control" id="payer-city" name="payerCity" />
	  	</div>

	  	<div class="form-group col-md-6">
	  		<label for="payer-state">State</label>
	  		<input type="text" class="form-control" id="payer-state" name="payerState" placeholder="Alabama" />
	  	</div>

	  </div><!-- end form-row -->
	  
	  <div class="form-row">
	  	  <div class="form-group col-md-6">
	  		<label for="payer-country">Country</label>
	  		<input type="text" class="form-control" id="payer-country" name="payerCountry" />
	  	  </div>

		  <div class="form-group col-md-6">
	  		<label for="payer-zip">Zip</label>
	  		<input type="text" class="form-control" id="payer-zip" name="payerZip" />
	  	  </div>
  	  </div>

	  <div class="form-group">
	    <label for="payer-phone"><span style="color:red;">*</span>Your phone number</label>
	    <input type="phone" class="form-control" id="payer-phone" name="payerPhone" required/>
	  </div><!-- end payer-address -->

	  <input name="amount" type="hidden" value="{{ $true_total }}" />
	  <input name="itemsData" type="hidden" value="{{ json_encode($items) }}" />
	  <input name="anotherData" type="hidden" value="{{ json_encode(['subTotal' => $subTotal,
	  	'discount' => $discount, 'afterDiscount' => $afterDiscount, 'taxValue' => $taxValue]) }}">
	
	  <a class="btn btn-success" href="{!! action('CartController@index') !!}">Return to Cart</a>
	  <button class="btn btn-danger">Pay with {{ $payment_method }}</button></p>
	</form>

</div>

<div class="col-md-3"></div>

<!-- Global right column -->
<div class="col-md-4">
	<h4>Short review of your cart</h4>
	@foreach ($items as $item)
		<div class="row border border-bottom-0 pt-2">
			<div class="col-md-6">
				<img src="{{ productImage($item->model->image) }}" alt="item" class="cart-table-img" style="width: 50px;">
				<div class="cart-item-details">
					<div class="cart-table-item">{{ $item->name }}</div>
				</div>
			</div>

			<div class="col-md-6 d-flex align-items-center">
				Quantity: {{ $item->qty }}
			</div>
		</div>
	@endforeach
	<!-- Cart total block -->
    <div class="row alert alert-info">
    	<table class="table">
    		<tbody>
    			<tr>
    				<td><b>Subtotal (before coupons and taxes)</b></td>
    				<td> {{ priceStdFormat($subTotal) }}</td>
    			</tr>

     			@if (!empty(LaraCart::getCoupons()))
            	@foreach (LaraCart::getCoupons() as $key => $value)
            	<tr>
                	<td><b>Applied coupone</b></td>
                	<td>{{ $value->code }}</td>
                </tr>
                <tr>
                	<td><b>Discount</b></td>
                	<td>{{ $discount * 100 }} %</td>
                </tr>
                @endforeach
                <tr>
                	<td><b>New Subtotal (after coupons)</b></td>
                	<td>{{ priceStdFormat($afterDiscount) }}</td>
                </tr>
            	@endif

    			<tr>
    				<td><b>Tax ({{ (float)config('laracart.tax') }} %)</b></td>
    				<td>{{ priceStdFormat($taxValue) }}</td>
    			</tr>

    			<tr>
    				<td><b>Total:</b></td>
    				<td>{{ priceStdFormat($total) }}</td>
    			</tr>
    		</tbody>
    	</table>
    </div>
    <!-- end cart-totals -->
</div><!-- end global right column -->
@endsection