<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseUser.php";
require_once dirname ( __FILE__ ) . "/User.php";
require_once dirname ( __FILE__ ) . "/Service.php";
class Payment {
	public static function createUserInBrainTree($id, $firstName, $lastName, $email, $phone) {
		Braintree_Configuration::environment ( 'sandbox' );
		Braintree_Configuration::merchantId ( 'ncjjy77xdztwsny3' );
		Braintree_Configuration::publicKey ( 'dhhbskndbg9nmwk2' );
		Braintree_Configuration::privateKey ( 'ab312b96bf5d161816b0f248779d04e3' );

		$result = Braintree_Customer::create ( array (
				'id' => $id,
				'firstName' => $firstName,
				'lastName' => $lastName,
				'email' => $email,
				'phone' => $phone 
		) );
		if (! $result->success)
			throw new errorCreatingUserPaymentException ();
	}
	public static function generateClientToken($id) {
		Braintree_Configuration::environment ( 'sandbox' );
		Braintree_Configuration::merchantId ( 'ncjjy77xdztwsny3' );
		Braintree_Configuration::publicKey ( 'dhhbskndbg9nmwk2' );
		Braintree_Configuration::privateKey ( 'ab312b96bf5d161816b0f248779d04e3' );
		return Braintree_ClientToken::generate ( array (
				"customerId" => $id 
		) );
	}
	public static function readClient($id) {
		Braintree_Configuration::environment ( 'sandbox' );
		Braintree_Configuration::merchantId ( 'ncjjy77xdztwsny3' );
		Braintree_Configuration::publicKey ( 'dhhbskndbg9nmwk2' );
		Braintree_Configuration::privateKey ( 'ab312b96bf5d161816b0f248779d04e3' );
		
		$BrainTree_Customer = Braintree_Customer::find ( $id );
		$Credit_Cards = $BrainTree_Customer->creditCards;
		if (count ( $Credit_Cards ) <= 0)
			return;
		$Credit_Card = $Credit_Cards [0];
		return array (
				"cardName" => $Credit_Card->cardholderName,
				"cardExpiration" => $Credit_Card->expirationDate,
				"cardNumber" => $Credit_Card->maskedNumber 
		);
	}
	public static function makeTransaction($price, $customerId) {
		Braintree_Configuration::environment ( 'sandbox' );
		Braintree_Configuration::merchantId ( 'ncjjy77xdztwsny3' );
		Braintree_Configuration::publicKey ( 'dhhbskndbg9nmwk2' );
		Braintree_Configuration::privateKey ( 'ab312b96bf5d161816b0f248779d04e3' );
		$Braintree_Transaction = Braintree_Transaction::sale ( array (
				'customerId' => $customerId,
				'amount' => $price 
		) );
		if (! $Braintree_Transaction->success)
			throw new errorMakingPaymentException ();
		
		return $Braintree_Transaction->transaction->id;
	}
	public static function updatePaymentMethodForUser($id, $nonceFromClient) {
		Braintree_Configuration::environment ( 'sandbox' );
		Braintree_Configuration::merchantId ( 'ncjjy77xdztwsny3' );
		Braintree_Configuration::publicKey ( 'dhhbskndbg9nmwk2' );
		Braintree_Configuration::privateKey ( 'ab312b96bf5d161816b0f248779d04e3' );
		
		$user = Braintree_Customer::find ( $id );
		
		if (count ( $user->creditCards ) > 0) {
			$creditCardToken = $user->creditCards [0]->token;
			$result = BrainTree_Customer::update ( $id, array (
					'creditCard' => array (
							'paymentMethodNonce' => $nonceFromClient,
							'options' => array (
									'updateExistingToken' => $creditCardToken,
									'verifyCard' => true 
							) ) ) );
		} else {
			$result = BrainTree_Customer::update ( $id, array (
					'creditCard' => array (
							'paymentMethodNonce' => $nonceFromClient,
							'options' => array (
									'verifyCard' => true 
							) ) ) );
		}
		if (! $result->success)
			throw new errorUpdatingPaymentException ();
	}
}
class errorCreatingUserPaymentException extends Exception {
}
class errorUpdatingPaymentException extends Exception {
}
class errorMakingPaymentException extends Exception {
}
?>