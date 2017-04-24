<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['serviceId']) || !isset($_POST['rating']) || !isset($_POST['token']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
try{
  $serviceId = SafeString::safe($_POST['serviceId']);
  $rating = SafeString::safe($_POST['rating']);
  $token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  	
  $service  = new Service();
  $service->sendReview($serviceId,$rating);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
  	echo json_encode(array("Status"=>"SESSION ERROR"));
  }
?>