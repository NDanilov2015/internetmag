<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClientOrder;

use LukePOLO\LaraCart\Facades\LaraCart as LaraCart;

use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use URL;
use Debugbar;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class CheckoutController extends Controller
{
	public function __construct()
	{
		/** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
	}
	
	public function choiceWallet(Request $request)
	{
		return view('cart.checkout-choicewallet');
	}
	
	public function paymentProceed(Request $request)
	{
		
		$payment_method = $request->walletRadioButton;
		return view('cart.checkout-paymentproceed', ['payment_method' => $payment_method]);
	}
	
	/*
	 * Записывает данные заказа в таблицу orders
	 */
	protected function recordOrderData($data)
	{
		$anotherData = json_decode($data->anotherData);
		
		$order = new ClientOrder();
		//Client's personal data
		$order->fullname = $data->payerFullname;
		$order->email = $data->payerEmail;
		$order->address = $data->payerAddress;
		$order->city = $data->payerCity;
		$order->state = $data->payerState;
		$order->country = $data->payerCountry;
		$order->zip = $data->payerZip;
		$order->phone = $data->payerPhone;
		//Items data
		$order->items = $data->itemsData;
		$order->subtotal = $anotherData->subTotal;
		$order->discount = $anotherData->discount;
		$order->afterdiscount = $anotherData->afterDiscount;
		$order->taxvalue = $anotherData->taxValue;
		$order->total = $data->amount;
		$order->order_status = 'proceeding';
		
		$order->save();
		//Get instant id of created record
		session()->put('order_id', $order->id);
	}
	
	/*
	 * Методы, отвечающие за оплату
	 * 
	 */
	public function doPayPal(Request $request)
	{
		//Запись личных данных заказчика в таблицу заказа
		$this->recordOrderData($request);
		
		/** Begin payment transaction process **/
		$payer = new Payer();
        $payer->setPaymentMethod('paypal');
		
		$item_1 = new Item();
		$item_1->setName('Summary order') /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->amount);
			
		$item_list = new ItemList();
        $item_list->setItems(array($item_1));
		
		$amount = new Amount();
        $amount->setCurrency('USD')
			   ->setTotal($request->amount);
		
		$transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
			
		$redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.endstage')) /** Return URL after payment will be approved **/
					  ->setCancelUrl(URL::route('payment.endstage'));
					  
		$payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction)); 
		
		try {
			$payment->create($this->_api_context);
			
		} catch (\PayPal\Exception\PPConnectionException $ex) {
			
			if (\Config::get('app.debug')) {
				\Session::put('error', 'Connection timeout');
				return Redirect::route('paywithpaypal');
			} else {
				\Session::put('error', 'Some error occur, sorry for inconvenient');
				return Redirect::route('paywithpaypal');
			}
			
		}//end try ... catch
		
		foreach ($payment->getLinks() as $link) {
			if ($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
				break;
			}
		}
		
		/** add payment ID to session **/
		
		//Не этот ли будет высвечиваться далее на странице заказа, а?
		Session::put('paypal_payment_id', $payment->getId()); //Вот досюда мы нормально доходим!
		
		//Вот тут мы переходим на Paypal URL
		if (isset($redirect_url)) {
			return Redirect::away($redirect_url);
		}
		
		/* Если нет ошибок, сюда уже больше не возвращаемся! */
		
		Session::put('error', 'Unknown error occurred');
		return Redirect::route('paywithpaypal');
	} //end function doPayPal
	
	public function endPayPal(Request $request)
	{
		/** Get the payment ID (saved in last step) before session clear **/
        $payment_id = Session::get('paypal_payment_id');

		/** clear the session payment ID **/
        Session::forget('paypal_payment_id');
		
		//Вместо страшного по виду if (isset($_GET[‘success’]) && $_GET[‘success’] == ‘true’) ...
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
			Session::put('error', 'Payment failed');
            return Redirect::route('payment.status');
		}
		
		$payment = Payment::get($payment_id, $this->_api_context);
		
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
		
		/**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
		if ($result->getState() == 'approved') {
			Session::put('success', 'Payment success');
			
			/** Cleaning coupons and cart **/
			LaraCart::removeCoupons();
            LaraCart::emptyCart();
			
			return Redirect::route('payment.status');
		}
		
		Session::put('error', 'Payment failed');
		return Redirect::route('payment.status');
	}
	
	public function doSkrill(Request $request)
	{
		
	}
	
	public function viewOrderStatus(Request $request)
	{
		//И данные показывать надо!)
		return view('cart.payment-status');
	}
}