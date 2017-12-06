<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Payment.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['cardToken']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}

try
{
	$token = SafeString::safe($_POST['token']);
	$cardToken = SafeString::safe($_POST['cardToken']);
  	$user  = new User();
  	$info = $user->userHasToken($token);
  	$paymentToken = Payment::updatePaymentMethodForUser($info["ConektaId"], $cardToken);
  	echo json_encode(array(
  			"estado"=>"ok"
  	));
} 
catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} 
catch(noSessionFoundException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"sesion",
			"explicacion"=>$e->getMessage()
	));
} 
catch (errorUpdatingPaymentException $e){
	try
	{
		$token = SafeString::safe($_POST['token']);
		$cardToken = SafeString::safe($_POST['cardToken']);
		$user  = new User();
		$infoUser = $user->userHasToken($token);
		$conektaId = Payment::createUser( $infoUser["Nombre"], $infoUser["PrimerApellido"], $infoUser["Email"], $infoUser["Telefono"]);
	  	$user->saveConektaId($infoUser["idCliente"], $conektaId);
	  	$paymentToken = Payment::updatePaymentMethodForUser($conektaId, $cardToken);
		echo json_encode(array("Status"=>"OK"));
	} catch (errorUpdatingPaymentException $e){
		echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
	} catch (Conekta_Error $e) {
		echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
	}
} 
catch (Conekta_Error $e) 
{
	echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
} 
catch (Exception $e) 
{
	echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
}
?>