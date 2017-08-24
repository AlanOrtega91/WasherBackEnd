<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['idLavador']))
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
  $cleanerId =  SafeString::safe($_POST['idLavador']);
  $service = new Service();
  $user  = new User();
  $user->userHasToken($token);
  $review = $service->readReviewForCleaner($cleanerId);
  echo json_encode(array(
  		"estado"=>"ok",
  		"calificacion"=>$review,
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
catch (cleanerNotFoundException $e) 
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"lavador",
			"explicacion"=>$e->getMessage()
	));
}

?>