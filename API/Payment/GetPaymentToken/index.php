<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
	$token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $paymentToken = Payment::generateClientToken($info['idCliente']);
  echo json_encode(array("Status"=>"OK","paymentToken" => $paymentToken));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(noSessionFoundException $e)
{
  echo json_encode(array("Status"=>"SESSION ERROR"));
} catch (Braintree_Exception_NotFound $e){
	$token = SafeString::safe($_POST['token']);
	$user  = new User();
	$infoUser = $user->userHasToken($token);
	Payment::createUserInBrainTree($userInfo['idCliente'], $infoUser['Nombre'], $infoUser['Apellido'], 
			$infoUser['Email'], $infoUser['Telefono']);
	$paymentToken = Payment::generateClientToken($userInfo['idCliente']);
	echo json_encode(array("Status"=>"OK","paymentToken" => $paymentToken));
}
?>