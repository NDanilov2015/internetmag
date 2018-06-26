<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
	
	public function choiceWallet(Request $request)
	{
		return view('cart.checkout-choicewallet');
	}
	
	public function payPalProceed(Request $request)
	{
		
	}
	
	public function skrillProceed(Request $request)
	{
		
	}
}