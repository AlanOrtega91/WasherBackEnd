<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Payment.php";

if (!isset($_POST['token']) || !isset($_POST['cardToken']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
	$token = SafeString::safe($_POST['token']);
	$cardToken = SafeString::safe($_POST['cardToken']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $paymentToken = Payment::updatePaymentMethodForUser($info["ConektaId"], $cardToken);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(noSessionFoundException $e)
{
  echo json_encode(array("Status"=>"SESSION ERROR"));
} catch (Conekta_ResourceNotFoundError $e){
	try{
	$token = SafeString::safe($_POST['token']);
	$cardToken = SafeString::safe($_POST['cardToken']);
	$user  = new User();
	$infoUser = $user->userHasToken($token);
	$conektaId = Payment::createUser( $infoUser["Nombre"], $infoUser["PrimerApellido"], $infoUser["Email"], $infoUser["Telefono"]);
  	$user->saveConektaId($infoUser["idCliente"], $conektaId);
  	$paymentToken = Payment::updatePaymentMethodForUser($conektaId, $cardToken);
	echo json_encode(array("Status"=>"OK".$conektaId));
	} catch (Conekta_ResourceNotFoundError $e){
		echo json_encode(array("Status"=>"UPDATE PAYMENT ACCOUNT ERROR"));
	}
}
?>