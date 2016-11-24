<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseUser.php";
require_once dirname ( __FILE__ ) . "/User.php";
require_once dirname ( __FILE__ ) . "/Service.php";
require_once dirname ( __FILE__ ) . "/../vendor/conekta/conekta-php/lib/Conekta.php";

class Payment {
	public static function createUser($firstName, $lastName, $email, $phone) {
		Conekta::setApiKey("key_B3vB4bJxGfXS6NfKS9zhUg");
		$customer = Conekta_Customer::create(
		  array(
		    'name'  => $firstName." ".$lastName,
		    'email' => $email,
		    'phone' => $phone,
		  )
		);

		if (!$customer) {
			throw new errorCreatingUserPaymentException ();
		}
		return $customer->id;
	}

	public static function readClient($id) {
		try {
		Conekta::setApiKey("key_B3vB4bJxGfXS6NfKS9zhUg");
		$customer = Conekta_Customer::find($id);
		$Credit_Cards = $card = $customer->cards;
		
		if (count ( $Credit_Cards ) <= 0)
			return;
		$Credit_Card = $Credit_Cards [0];
		return array (
				"cardName" => $Credit_Card->name,
				"cardExpirationMonth" => $Credit_Card->exp_month,
				"cardExpirationYear" => $Credit_Card->exp_year,
				"cardNumber" => $Credit_Card->last4 
		);
		} catch (Exception $e) {
			throw new errorReadingUserPayment ();
		}
	}
	public static function makeTransaction($price, $customerId, $name, $phone, $email) {
		Conekta::setApiKey("key_B3vB4bJxGfXS6NfKS9zhUg");
		$customer = Conekta_Customer::find($customerId);
		$Credit_Cards = $card = $customer->cards;
		if (count ( $Credit_Cards ) <= 0) {
			throw new errorMakingPaymentException();
		}
		$price =  $price*100 + 200;
		$creditCard = $Credit_Cards[0]; 
		$charge = Conekta_Charge::create(array(
  "description"=> "Stogies",
  "amount"=> $price,
  "currency"=> "MXN",
  "reference_id"=> "9839-wolf_pack",
  "card"=> $creditCard->id,
  "details"=> array(
    "name"=> "Arnulfo Quimare",
    "phone"=> "403-342-0642",
    "email"=> "logan@x-men.org",
    "customer"=> array(
      "logged_in"=>true,
      "successful_purchases"=> 14,
      "created_at"=> 1379784950,
      "updated_at"=> 1379784950,
      "offline_payments"=> 4,
      "score"=> 9
    ),
    "line_items"=> array(array(
      "name"=> "Box of Cohiba S1s",
      "description"=> "Imported From Mex.",
      "unit_price"=> $price,
      "quantity"=> 1,
      "sku"=> "cohb_s1",
      "category"=> "food"
    )),
    "billing_address"=> array(
      "street1"=>"77 Mystery Lane",
      "street2"=> "Suite 124",
      "city"=> "Darlington",
      "state"=>"NJ",
      "zip"=> "10192",
      "country"=> "Mexico",
      "tax_id"=> "xmn671212drx",
      "company_name"=>"X-Men Inc.",
      "phone"=> "77-777-7777",
      "email"=> "purshasing@x-men.org"
    )
  )
));
		if (! $charge)
			throw new errorMakingPaymentException ();
		return $charge->id;
	}
	public static function updatePaymentMethodForUser($id, $cardToken) {
		try {
		Conekta::setApiKey("key_B3vB4bJxGfXS6NfKS9zhUg");
		$customer = Conekta_Customer::find($id);
		if (count($customer->cards) <= 0) {
			$card = $customer->createCard(array('token'=>$cardToken));
		} else {
			$card = $customer->cards[0]->update(array('token' => $cardToken, 'active' => true));
		}
		if (! $card)
			throw new errorUpdatingPaymentException ();
		} catch (Conekta_Error $e) {
			throw new errorUpdatingPaymentException ();
		}
	}
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