<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";


if (!isset($_POST['serviceId']) || !isset($_POST['statusId']) || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
 
try{ 
  $serviceId = SafeString::safe($_POST['serviceId']);
  $statusId = SafeString::safe($_POST['statusId']);
  $token = SafeString::safe($_POST['token']);
  try{
  	$user  = new User();
  	$info = $user->userHasToken($token);
  } catch (noSessionFoundException $e){
  	$cleaner  = new Cleaner();
  	$info = $cleaner->readCleanerData($token);
  }
  $service  = new Service();
  $service->changeServiceStatus($serviceId, $statusId);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>