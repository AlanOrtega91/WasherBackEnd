<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseUser.php";
require_once dirname ( __FILE__ ) . "/User.php";
require_once dirname ( __FILE__ ) . "/Service.php";
require_once dirname ( __FILE__ ) . "/../recursos/conekta-php/lib/Conekta.php";

class Payment {
	
    //TODO: Cambiar a produccion
	const KEY = "key_pp8MSFH1FdMaF9o6p6fREg";
	public static function createUser($firstName, $lastName, $email, $phone) {
		\Conekta\Conekta::setApiKey(self::KEY);
		\Conekta\Conekta::setApiVersion("2.0.0");
		$customer = \Conekta\Customer::create(
				array(
						'name'  => $firstName." ".$lastName,
						'email' => $email,
						'phone' => $phone,
				)//customer
				);
		if (!$customer) {
			throw new errorCreatingUserPaymentException ();
		}
		return $customer->id;
	}

	public static function readClient($id) {
		try {
			\Conekta\Conekta::setApiKey(self::KEY);
			\Conekta\Conekta::setApiVersion("2.0.0");
			$customer = \Conekta\Customer::find($id);
			if (!$customer)
			{
				throw new usuarioNoEncontrado();
			}
			
			$payment_sources = $customer->payment_sources;
			
		
		if (count ( $payment_sources) <= 0)
		{
			return;
		}
		$payment = $payment_sources[0];
		return array (
				"cardName" => $payment->name,
				"cardExpirationMonth" => $payment->exp_month,
				"cardExpirationYear" => $payment->exp_year,
				"cardNumber" => $payment->last4 
		);
		} catch (Exception $e) {
		    throw new errorReadingUserPayment ($e->getMessage().$id);
		}
	}
	public static function makeTransaction($price, $customerId) {
		\Conekta\Conekta::setApiKey(self::KEY);
		\Conekta\Conekta::setApiVersion("2.0.0");
		$precioConekta =  $price*100;
		$charge = \Conekta\Order::create(array(
				'line_items' => array(
						array(
								'name' => 'Servicio de Washer',
								'description' => 'Lavado de autos',
								'unit_price' => $precioConekta,
								'quantity' => 1
						)
				),//line_items
				'charges' => array(
						array(
								'payment_method' => array(
										'type' => 'default'
								)
						)
				),//charges
				'currency' => 'mxn',
				'customer_info' => array(
						'customer_id' => $customerId
				)//customer_info
		));
		if (! $charge) {
			throw new errorMakingPaymentException ();
		}
		return $charge->id;
	}
	public static function updatePaymentMethodForUser($id, $cardToken) {
		\Conekta\Conekta::setApiKey(self::KEY);
		\Conekta\Conekta::setApiVersion("2.0.0");
		
		$customer = \Conekta\Customer::find($id);
		if (!$customer)
		{
			throw new usuarioNoEncontrado();
		}
		
		
		if (count($customer->payment_sources) <= 0) {
			$card = $customer->createPaymentSource(array(
					'token_id'=>$cardToken,
					'type' => 'card'
			));
		} else {
		    $customer->payment_sources[0]->delete();
		    $card = $customer->createPaymentSource(array(
		        'token_id'=>$cardToken,
		        'type' => 'card'
		    ));
		}
		if (! $card){
			throw new errorUpdatingPaymentException ();
		}
	}
}
class usuarioNoEncontrado extends Exception {
}
class errorCreatingUserPaymentException extends Exception {
}
class errorUpdatingPaymentException extends Exception {
}
class errorMakingPaymentException extends Exception {
}
class errorReadingUserPayment extends Exception {
}
?>