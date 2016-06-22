<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_GET['serviceId']) || !isset($_GET['rating']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$serviceId = SafeString::safe($_GET['serviceId']);
$rating = SafeString::safe($_GET['rating']);
try{
  $service  = new Service();
  $service->sendReview($serviceId,$rating);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>