@extends('layouts.front')

@section('title', 'Checkout - Step 3!')

@section('content')
<div class="col-md-12">
	<h2>Checkout process: Step 3. Please, check status of your payment and remember your order Id</h2>
	
	@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <span onclick="this.parentElement.style.display='none'"
                class="w3-button w3-green w3-large w3-display-topright">&times;</span>
        <p>{!! $message !!}</p>
    </div>
	
	@php
		Session::forget('success');
	
		$order_id = session()->get('order_id'); 
		Session::forget('order_id');
	@endphp
	
    @endif
	
	@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <span onclick="this.parentElement.style.display='none'"
                class="w3-button w3-red w3-large w3-display-topright">&times;</span>
        <p>{!! $message !!}</p>
    </div>
    <?php Session::forget('error'); ?>
    @endif

    @include('orders.orderview-module', ['order_id' => $order_id])     
    </p>
	
</div>
@endsection