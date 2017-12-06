<?php
require_once dirname ( __FILE__ ) . "/../../../DBConnect/SafeString.php";
require_once dirname ( __FILE__ ) . "/../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['pushNotificationToken']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}

try 
{
	$token = SafeString::safe ( $_POST ['token'] );
	$pushNotificationToken = SafeString::safe ( $_POST ['pushNotificationToken'] );
	$user = new User ();
	$infoUser = $user->userHasToken ( $token );
	$user->savePushNotificationToken ( $infoUser ['idCliente'], $pushNotificationToken );
	echo json_encode(array(
			"estado" => "ok"
	));
} 
catch ( userNotFoundException $e ) 
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"usuario",
			"explicacion"=>$e->getMessage()
	));
} 
catch ( errorWithDatabaseException $e ) 
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} 
catch ( noSessionFoundException $e ) 
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"sesion",
			"explicacion"=>$e->getMessage()
	));
}
?>