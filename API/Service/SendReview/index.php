<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['serviceId']) || !isset($_POST['rating']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $serviceId = SafeString::safe($_POST['serviceId']);
  $rating = SafeString::safe($_POST['rating']);
  $service  = new Service();
  $service->sendReview($serviceId,$rating);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>