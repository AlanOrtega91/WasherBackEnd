<?php
include dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_GET['clientId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$clientId = SafeString::safe($_GET['clientId']);
try{
  $car  = new Car();
  $carsList = $car->getCarsList($clientId);
  echo json_encode(array("Status"=>"OK","carsList"=>$carsList));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
catch(carsNotFoundException $e)
{
  echo json_encode(array("Status"=>"OK","carsList"=>null));
}
?>