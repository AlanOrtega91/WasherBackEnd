<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['cleanerId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$cleanerId = SafeString::safe($_POST['cleanerId']);
try{
  $service  = new Service();
  $review = $service->readReviewForCleaner($cleanerId);
  echo json_encode(array("Status"=>"OK","Calificacion"=>$review));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>