<?php

require_once dirname(__FILE__).'/../braintree/lib/Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('j7y47rpby5mns8vg');
Braintree_Configuration::publicKey('s5h9wj75c25s8y8r');
Braintree_Configuration::privateKey('bbed5c25e540a8896b1c1cab8ed5f1ae');
if (!isset($_POST['operation']))
  die(json_encode(array("Satus"=>"ERROR missing values for operation")));
  
$operation = $_POST['operation'];
try{
  switch($operation){
    case 'create':
      createUserInBrainTree();
      $result = json_encode(array("Status" => "OK"));
      break;
    case 'token':
      $result = json_encode(array("Status" => "OK","paymentToken" => generateClientToken()));
      break;
    case 'update':
      updatePaymentMethodForUser();
      $result = json_encode(array("Status" => "OK"));
      break;
    case 'read':
      $result =  json_encode(array("Status" => "OK","cards" => readClient()));
      break;
  }
} catch (Exception $e) {
  $result = json_encode(array("Status" => "Error"));
}

echo $result;


function createUserInBrainTree(){
  if (!isset($_POST['id']) || !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['email']) || !isset($_POST['phone']))
    die(json_encode(array("Satus"=>"ERROR missing values for create")));
    
  $id = $_POST['id'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $mail = $_POST['email'];
  $phone = $_POST['phone'];
  $result = Braintree_Customer::create([
    'id' => $id,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $mail,
    'phone' => $phone
  ]);
  if(!$result->success)
    throw new errorCreatingUserException();
}

function generateClientToken(){
  if (!isset($_POST['id']))
    die(json_encode(array("Satus"=>"ERROR missing values")));
    
  $id = $_POST['id'];
  return Braintree_ClientToken::generate([
                                          "customerId" => $id
                                          ]);
}

function updatePaymentMethodForUser(){
  if (!isset($_POST['id']) || !isset($_POST['nonceFromClient']))
    die(json_encode(array("Satus"=>"ERROR missing values in payment")));
    
  $id = $_POST['id'];
  $nonceFromClient = $_POST['nonceFromClient'];
  $user = Braintree_Customer::find($id);

  if(count($user->creditCards) > 0){
    $creditCardToken = $user->creditCards[0]->token;
    $result = BrainTree_Customer::update(
                                         $id,
                                         [
                                          'creditCard' => [
                                                           'paymentMethodNonce' => $nonceFromClient,
                                                           'options' => [
                                                                         'updateExistingToken' => $creditCardToken,
                                                                         'verifyCard' => true
                                                                         ]
                                                           ]
                                          ]
                                         );
  }
  else {
    $result = BrainTree_Customer::update(
                                         $id,
                                         [
                                          'creditCard' => [
                                                           'paymentMethodNonce' => $nonceFromClient,
                                                           'options' => [
                                                                         'verifyCard' => true
                                                                         ]
                                                           ]
                                          ]
                                         );
  }
  if(!$result->success)
    throw new errorCreatingUserException();
}

function readClient(){
  if (!isset($_POST['id']))
    die(json_encode(array("Satus"=>"ERROR missing values")));
    
  $id = $_POST['id'];
  $BrainTree_Customer = Braintree_Customer::find($id);
  $Credit_Cards = $BrainTree_Customer->creditCards;
  if(count($Credit_Cards) <= 0)
    return array("id" => $BrainTree_Customer->id);
  $Credit_Card = $Credit_Cards[0];
  return array(
               "id" => $BrainTree_Customer->id,
               "cardName" => $Credit_Card->cardholderName,
               "cardExpiration" => $Credit_Card->expirationDate,
               "cardNumber" => $Credit_Card->maskedNumber
               );
}
class errorCreatingUserException extends Exception{
	}
?>