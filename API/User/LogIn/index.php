<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__).'/../../../braintree/lib/Braintree.php';

if (!isset($_POST['mail']) || !isset($_POST['password']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
  $mail = SafeString::safe($_POST['mail']);
  $password = SafeString::safe($_POST['password']);
  $user  = new User();
  $car  = new Car();
  $service  = new Service();
  $userInfo = $user->sendLogIn($mail, $password);
  $clientId = $userInfo['idCliente'];
  $carsList = $car->getCarsList($clientId);
  $servicesHistory = $service->getHistory($clientId,1);
  echo json_encode(array("Status"=>"OK","User Info"=>$userInfo,
                         "carsList"=>$carsList,"History"=>$servicesHistory,"cards" => readClient($clientId)));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(carsNotFoundException $e)
{
  echo json_encode(array("Status"=>"OK","carsList"=>null));
}

function readClient($id){
  Braintree_Configuration::environment('sandbox');
  Braintree_Configuration::merchantId('ncjjy77xdztwsny3');
  Braintree_Configuration::publicKey('dhhbskndbg9nmwk2');
  Braintree_Configuration::privateKey('ab312b96bf5d161816b0f248779d04e3');
  $BrainTree_Customer = Braintree_Customer::find($id);
  $Credit_Cards = $BrainTree_Customer->creditCards;
  if(count($Credit_Cards) <= 0)
    return;
  $Credit_Card = $Credit_Cards[0];
  return array(
               "cardName" => $Credit_Card->cardholderName,
               "cardExpiration" => $Credit_Card->expirationDate,
               "cardNumber" => $Credit_Card->maskedNumber,
               "expired" => $Credit_Card->expired,
               "token" => $Credit_Card->token
               );
}
?>