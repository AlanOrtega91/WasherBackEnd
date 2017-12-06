<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['latitud']) || !isset($_POST['longitud']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
} 


try{
  $token = SafeString::safe($_POST['token']);
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->userHasToken($token);
  $cleanerInfo = $cleaner->updateLocation($cleanerInfo['idLavador'], $latitud, $longitud);
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
catch (noSessionFoundException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"sesion",
			"explicacion"=>$e->getMessage()
	));
}
?>