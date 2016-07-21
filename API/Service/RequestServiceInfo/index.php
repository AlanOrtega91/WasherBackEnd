<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['serviceId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $serviceId = SafeString::safe($_POST['serviceId']);
  $service  = new Service();
  $info = $service->getInfo($serviceId);
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