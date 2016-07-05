<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_POST['clientId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$clientId = SafeString::safe($_POST['clientId']);
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