<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";

if (!isset($_POST['token']) || !isset ( $_POST ['nonceFromClient'] ))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
	$nonceFromClient = $_POST ['nonceFromClient'];
	$token = SafeString::safe($_POST['token']);
	$user  = new User();
	$info = $user->userHasToken($token);
	$paymentToken = Payment::updatePaymentMethodForUser($info['idCliente'],$nonceFromClient);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(noSessionFoundException $e)
{
  echo json_encode(array("Status"=>"SESSION ERROR"));
} catch(errorUpdatingPaymentException $e)
{
  echo json_encode(array("Status"=>"PAYMENT ERROR"));
}
?>