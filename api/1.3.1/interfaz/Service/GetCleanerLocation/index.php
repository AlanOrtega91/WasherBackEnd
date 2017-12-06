<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['cleanerId']) || !isset($_POST['token']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}

try{  
  $cleanerId = SafeString::safe($_POST['cleanerId']);
  $token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $service  = new Service();
  $cleaner = $service->getCleanerLocation($cleanerId);
  echo json_encode(array(
  		"estado"=>"ok",
  		"lavador"=>$cleaner,
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