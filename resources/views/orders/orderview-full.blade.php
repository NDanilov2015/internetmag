@extends('layouts.front')

@section('title', 'Order View')

@section('content')
<div class="col-md-12">
	<h2>Order View</h2>
	<br/>
	@include('orders.orderview-module', ['order_id' => $order_id]);
</div>
@endsection