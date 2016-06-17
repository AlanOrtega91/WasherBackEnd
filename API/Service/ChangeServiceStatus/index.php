<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_GET['serviceId']) || !isset($_GET['statusId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$serviceId = SafeString::safe($_GET['serviceId']);
$statusId = SafeString::safe($_GET['statusId']);
try{
  $service  = new Service();
  $service->changeServiceStatus($serviceId, $statusId);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>