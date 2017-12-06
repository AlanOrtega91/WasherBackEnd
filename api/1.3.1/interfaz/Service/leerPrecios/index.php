<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['latitud']) || !isset($_POST['longitud']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}
  
try
{
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  
  $service  = new Service();
  $precios = $service->leerPrecios($latitud, $longitud);
  echo json_encode(array(
  		"estado"=>"ok",
  		"precios"=>$precios,
  ));
  
} catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
}
?>