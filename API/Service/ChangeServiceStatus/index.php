<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['serviceId']) || !isset($_POST['statusId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$serviceId = SafeString::safe($_POST['serviceId']);
$statusId = SafeString::safe($_POST['statusId']);
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