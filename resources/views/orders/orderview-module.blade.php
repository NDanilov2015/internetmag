@php
	$order = App\Models\ClientOrder::find($order_id);
	$items = json_decode($order->items);
@endphp

<div class="col-md-12 border mt-0">
<h4>Client's information</h4>
<div class="row">
	<div class="col-md-12">
	<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Property</th>
      <th scope="col">Value</th>
    </tr>
  </thead>
  <tbody>
  	 <tr>
      <th scope="row">1</th>
      <td>Order id</td>
      <td>{{ $order->id }}</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Full Name</td>
      <td>{{ $order->fullname }}</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Email</td>
      <td>{{ $order->email }}</td>
    </tr>

	@if(!empty($order->address))
    <tr>
      <th scope="row">4</th>
      <td>Address</td>
      <td>{{ $order->address }}</td>
    </tr>
    @endif

	@if(!empty($order->city))
    <tr>
      <th scope="row">5</th>
      <td>City</td>
      <td>{{ $order->city }}</td>
    </tr>
    @endif

	@if(!empty($order->state))
    <tr>
      <th scope="row">6</th>
      <td>State</td>
      <td>{{ $order->state }}</td>
    </tr>
    @endif

	@if(!empty($order->country))
    <tr>
      <th scope="row">7</th>
      <td>Country</td>
      <td>{{ $order->country }}</td>
    </tr>
    @endif

	@if(!empty($order->zip))
    <tr>
      <th scope="row">8</th>
      <td>Zip</td>
      <td>{{ $order->zip }}</td>
    </tr>
    @endif

	@if(!empty($order->phone))
    <tr>
      <th scope="row">9</th>
      <td>Phone</td>
      <td>{{ $order->phone }}</td>
    </tr>
    @endif
  </tbody>
</table>
</div>
</div><!-- end row 1 -->
<br/>
<h4>Buyed products short information</h4>
<div class="row">
	<!-- LC -->
	<div class="col-md-6">
		@foreach ($items as $item)
		<div class="row border border-bottom-0 pt-2">
			<div class="col-md-6">
				<img src="{{ productImage($item->options->model->image) }}" alt="item" class="cart-table-img" style="width: 50px;">
				<div class="cart-item-details">
					<div class="cart-table-item">{{ $item->options->name }}</div>
				</div>
			</div>

			<div class="col-md-6 d-flex align-items-center">
				Quantity: {{ $item->options->qty }}
			</div>
		</div>
	@endforeach
	</div><!-- end class col-md-6 -->
	
	<!-- RC -->
	<div class="col-md-6">
		<table class="table">
			<tbody>
				<tr>
					<td><b>Subtotal (before coupons and taxes)</b></td>
					<td> {{ priceStdFormat($order->subtotal) }}</td>
				</tr>
				
	 			@if (!empty($order->discount))
	        	<tr>
	            	<td><b>Discount by coupone</b></td>
	            	<td>{{ (float)$order->discount * 100 }} %</td>
	            </tr>		
	            <tr>
	            	<td><b>New Subtotal (by coupone)</b></td>
	            	<td>{{ priceStdFormat($order->afterdiscount) }}</td>
	            </tr>
	        	@endif

				<tr>
					<td><b>Tax ({{ (float)config('laracart.tax') }} %)</b></td>
					<td>{{ priceStdFormat($order->taxvalue) }}</td>
				</tr>

				<tr>
					<td><b>Total:</b></td>
					<td>USD{{ $order->total }}</td>
				</tr>
			</tbody>
	    </table>
	</div><!-- end class col-md-6 -->
</div><!--end row -->
<br/>

<h4>Your order status</h4>
<div class="row">
	<div class="col-md-12 alert alert-warning">
		{{ ucfirst($order->order_status) }}
	</div>
</div><!-- end class row -->
<br/>

<h4>Your order link for remember and re-check it status</h4>
<div class="row">
	<div class="col-md-12 alert alert-warning">
		<b><a href="{{ url("/") }}/clientorders/{{ $order->id }}">{{ url("/") }}/clientorders/{{ $order->id }}</a></b>
	</div>
</div><!-- end class row -->
</div>