<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
	$token = SafeString::safe($_POST['token']);
	$user  = new User();
	$info = $user->userHasToken($token);
  $service  = new Service();
  $servicesAndTypes = $service->getServices();
  echo json_encode(array("Status"=>"OK","Services"=>$servicesAndTypes['services'],"ServicesTypes"=>$servicesAndTypes['types']));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
  	echo json_encode(array("Status"=>"SESSION ERROR"));
  }
?>