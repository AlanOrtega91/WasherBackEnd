<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['token'])  || !isset($_POST['userType']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $token = SafeString::safe($_POST['token']);
  $userType = SafeString::safe($_POST['userType']);
  $service  = new Service();
  $info = $service->getActiveService($token,$userType);
  echo json_encode(array("Status"=>"OK","info"=>$info));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(serviceNotFoundException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR service"));
}
?>