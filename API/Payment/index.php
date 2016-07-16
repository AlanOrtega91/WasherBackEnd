<?php

require_once dirname(__FILE__).'/../../braintree/lib/Braintree.php';
require_once dirname(__FILE__).'/../../DBConnect/Service.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('ncjjy77xdztwsny3');
Braintree_Configuration::publicKey('dhhbskndbg9nmwk2');
Braintree_Configuration::privateKey('ab312b96bf5d161816b0f248779d04e3');
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
    case 'transaction':
      makeTransaction();
      $result = json_encode(array("Status" => "OK"));
      break;
  }
} catch (Exception $e) {
  echo $e->getMessage();
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
  $result = Braintree_Customer::create(array(
    'id' => $id,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $mail,
    'phone' => $phone
  ));
  if(!$result->success)
    throw new errorCreatingUserException();
}

function generateClientToken(){
  if (!isset($_POST['id']))
    die(json_encode(array("Satus"=>"ERROR missing values")));
    
  $id = $_POST['id'];
  return Braintree_ClientToken::generate(array(
                                          "customerId" => $id
                                        ));
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
                                         array(
                                          'creditCard' => array(
                                                           'paymentMethodNonce' => $nonceFromClient,
                                                           'options' => array(
                                                                         'updateExistingToken' => $creditCardToken,
                                                                         'verifyCard' => true
                                                                         )
                                                           )
                                          )
                                         );
  }
  else {
    $result = BrainTree_Customer::update(
                                         $id,
                                         array(
                                          'creditCard' => array(
                                                           'paymentMethodNonce' => $nonceFromClient,
                                                           'options' => array(
                                                                         'verifyCard' => true
                                                                         )
                                                           )
                                          )
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
    return;
  $Credit_Card = $Credit_Cards[0];
  return array(
               "cardName" => $Credit_Card->cardholderName,
               "cardExpiration" => $Credit_Card->expirationDate,
               "cardNumber" => $Credit_Card->maskedNumber
               );
}
function makeTransaction(){
  if (!isset($_POST['serviceId']))
    die(json_encode(array("Satus"=>"ERROR missing values")));
    
  $serviceId = $_POST['serviceId'];
  $service  = new Service();
  $info = $service->getInfo($serviceId);
  $id = $info['idCliente'];
  $price = $info['precio'];
  $Braintree_Transaction = Braintree_Transaction::sale(array(
                                         'customerId' => $id,
                                         'amount' => $price
                                         ));
  $transactionId = $Braintree_Transaction->transaction->id;
  $service->saveTransaction($serviceId,$transactionId);
}
class errorCreatingUserException extends Exception{
	}
?>