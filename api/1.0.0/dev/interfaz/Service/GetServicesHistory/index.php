<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['idCliente']) || !isset($_POST['clientType']) || !isset($_POST['token']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
try{
  $clientId = SafeString::safe($_POST['idCliente']);
  $clientType = SafeString::safe($_POST['clientType']);
  $token = SafeString::safe($_POST['token']);
  if ($clientType == 1){
  	$user  = new User();
  	$info = $user->userHasToken($token);
  	$id = $info['idCliente'];
  } elseif ($clientType == 2){
  	$cleaner  = new Cleaner();
  	$info = $cleaner->userHasToken($token);
  	$id = $info['idLavador'];
  }
  $service  = new Service();
  $servicesHistory = $service->getHistory($id,$clientType);
  echo json_encode(array("Status"=>"OK","History"=>$servicesHistory));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>