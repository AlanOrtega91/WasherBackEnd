<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['token'])  || !isset($_POST['userType']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$token = SafeString::safe($_POST['token']);
$userType = SafeString::safe($_POST['userType']);
try{
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