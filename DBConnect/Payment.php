<?php
require_once dirname ( __FILE__ ) . "/../DataBaseClasses/DataBaseUser.php";
require_once dirname ( __FILE__ ) . "/User.php";
class Payment {
	
	public function __construct() {
		Braintree_Configuration::environment ( 'sandbox' );
		Braintree_Configuration::merchantId ( 'ncjjy77xdztwsny3' );
		Braintree_Configuration::publicKey ( 'dhhbskndbg9nmwk2' );
		Braintree_Configuration::privateKey ( 'ab312b96bf5d161816b0f248779d04e3' );
	}
	
	function createUserInBrainTree() {
		if (! isset ( $_POST ['token'] ))
			die ( json_encode ( array (
					"Satus" => "ERROR missing values for create" 
			) ) );
		
		$token = SafeString::safe ( $_POST ['token'] );
		$user = new User ();
		$infoUser = $user->userHasToken ( $token );
		$id = $infoUser ['idCliente'];
		$firstName = $infoUser ['Nombre'];
		$lastName = $infoUser ['Apellido'];
		$email = $infoUser ['Email'];
		$phone = $infoUser ['Telefono'];
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
	
	function generateClientToken() {
		if (! isset ( $_POST ['token'] ))
			die ( json_encode ( array (
					"Satus" => "ERROR missing values" 
			) ) );
		
		$token = SafeString::safe ( $_POST ['token'] );
		$user = new User ();
		$infoUser = $user->userHasToken ( $token );
		$id = $infoUser ['idCliente'];
		return Braintree_ClientToken::generate ( array (
				"customerId" => $id 
		) );
	}
	
	function readClient() {
		if (! isset ( $_POST ['token'] ))
			die ( json_encode ( array (
					"Satus" => "ERROR missing values" 
			) ) );
		
		$token = SafeString::safe ( $_POST ['token'] );
		$user = new User ();
		$infoUser = $user->userHasToken ( $token );
		$id = $infoUser ['idCliente'];
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
	
	function makeTransaction() {
		if (! isset ( $_POST ['serviceId'] ))
			die ( json_encode ( array (
					"Satus" => "ERROR missing values" 
			) ) );
		
		$serviceId = $_POST ['serviceId'];
		$service = new Service ();
		$info = $service->getInfo ( $serviceId );
		$id = $info ['idCliente'];
		$price = $info ['precio'];
		$Braintree_Transaction = Braintree_Transaction::sale ( array (
				'customerId' => $id,
				'amount' => $price 
		) );
		$transactionId = $Braintree_Transaction->transaction->id;
		$service->saveTransaction ( $serviceId, $transactionId );
	}
	
	function updatePaymentMethodForUser() {
		if (! isset ( $_POST ['id'] ) || ! isset ( $_POST ['nonceFromClient'] ))
			die ( json_encode ( array (
					"Satus" => "ERROR missing values in payment" 
			) ) );
		
		$id = $_POST ['id'];
		$nonceFromClient = $_POST ['nonceFromClient'];
		$user = Braintree_Customer::find ( $id );
		
		if (count ( $user->creditCards ) > 0) {
			$creditCardToken = $user->creditCards [0]->token;
			$result = BrainTree_Customer::update ( $id, array (
					'creditCard' => array (
							'paymentMethodNonce' => $nonceFromClient,
							'options' => array (
									'updateExistingToken' => $creditCardToken,
									'verifyCard' => true 
							) 
					) 
			) );
		} else {
			$result = BrainTree_Customer::update ( $id, array (
					'creditCard' => array (
							'paymentMethodNonce' => $nonceFromClient,
							'options' => array (
									'verifyCard' => true 
							) 
					) 
			) );
		}
		if (! $result->success)
			throw new errorCreatingUserPaymentException ();
	}
}
class errorCreatingUserPaymentException extends Exception {
}
?>