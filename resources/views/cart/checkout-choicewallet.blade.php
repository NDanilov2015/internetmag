@extends('layouts.front')

@section('title', 'Checkout!')

@section('content')
<div class="col-md-12">
	<h2>Checkout process: Step 1. Please, choice your Payment System</h2>
	<br/>
	<br/>                        
	
	<form class="bg bg-light" id="checkout-step1-form" action="{!! action('CheckoutController@paymentProceed') !!}" method="POST">
		{{ csrf_field() }}
		<fieldset>
			<div class="form-group">

			<div class="row">
				<div class="form-check">
					<input class="control-input" type="radio" name="walletRadioButton" id="paypalRadioButton" value="PayPal" checked>
					<label class="control-label" for="paypalRadioButton">
						PayPal
					</label>
					<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="Credit Card Badges">
				</div>
			</div>
			<div class="row mt-4 mb-4 d-flex justify-content-center">
				<div class="badge badge-pill badge-warning" id="paypal-information">If you don't have an account in PayPal you can pay by credit card. <br/> Soon, our operator will contact you for details of the order. <br/> Please prepare the information that you could not specify when placing an order.</div>
			</div>
			<div class="row">
				<div class="form-check">
					<input class="control-input" type="radio" name="walletRadioButton" id="skrillRadioButton" value="Skrill">
					<label class="control-label" for="skrillRadioButton">
						Skrill&nbsp;&nbsp;
					</label>
					<img src="{{ asset('img/logos/skrill.png') }}" alt="MoneyBookers Badge">
				</div>
			</div><!-- end 3th row -->
			</div><!-- end form-group 1 -->

			<div class="form-group">
				<div class="form-check">
					&nbsp;
					<input class="form-check-input" type="checkbox" id="agreeTerms" required/>
					<label class="form-check-label" for="agreeTerms">
						I agree with the terms
					</label>
				</div>
			</div>
			
			<a href="/cart" class="btn btn-success">Return to Cart</a>
			<button id="submitPaypal" type="submit" class="btn btn-danger">Proceed with PayPal</button>
			<button id="submitSkrill" type="submit" class="btn btn-danger">Proceed with Skrill</button>
		</fieldset>
	</form>
</div>

<script>
	$(document).ready(function(){

		$("#submitSkrill").hide(); //Initial mode - PayPal, not Skrill

		$('input[type=radio][name=walletRadioButton]').change(function(){
			if (this.value == 'Skrill') {
				$("#submitPaypal").hide();
				$("#paypal-information").hide();
				$("#submitSkrill").show();
			} else {
				$("#submitPaypal").show();
				$("#paypal-information").show();
				$("#submitSkrill").hide();
			}
		});
	});	
</script>
@endsection