<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['latitud']) || !isset($_POST['longitud']) || !isset($_POST['token']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}
  
try
{
  $distance = 1.5;
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $info = $cleaner->userHasToken($token);
  
  $service  = new Service();
  $services = $service->getServicesNearby($latitud, $longitud,$distance);
  echo json_encode(array(
  		"estado"=>"ok",
  		"servicios"=>$services,
  ));
  
} catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} catch (noSessionFoundException $e){
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"sesion",
			"explicacion"=>$e->getMessage()
	));
}
?>