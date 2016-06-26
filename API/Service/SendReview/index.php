<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['serviceId']) || !isset($_POST['rating']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$serviceId = SafeString::safe($_POST['serviceId']);
$rating = SafeString::safe($_POST['rating']);
try{
  $service  = new Service();
  $service->sendReview($serviceId,$rating);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>