<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['cleanerId']) || !isset($_POST['token']))
  die(json_encode(array("Status"=>"ERROR missing values")));

try{  
  $cleanerId = SafeString::safe($_POST['cleanerId']);
  $token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $service  = new Service();
  $cleaner = $service->getCleanerLocation($cleanerId);
  echo json_encode(array("Status"=>"OK","cleaner" => $cleaner));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>