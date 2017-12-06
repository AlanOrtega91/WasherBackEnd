<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['serviceId']) || !isset($_POST['token']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}
try
{ 
  $serviceId = SafeString::safe($_POST['serviceId']);
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $info = $cleaner->userHasToken($token);
  
  $service  = new Service();
  $info = $service->acceptService($serviceId,$info['idLavador'],$token);
  echo json_encode(array(
  		"estado"=>"ok",
  		"servicio"=>$info,
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
catch(serviceTakenException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"servicio",
			"explicacion"=>$e->getMessage()
	));
} 
catch (insufficientProductException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"productos",
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