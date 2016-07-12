<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_POST['favoriteCarId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$favoriteCarId = SafeString::safe($_POST['favoriteCarId']);
try{
  $car  = new Car();
  $car->deleteCar($favoriteCarId);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>