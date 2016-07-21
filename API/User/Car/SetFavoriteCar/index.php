<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_POST['vehiculoFavoritoId']) || !isset($_POST['clienteId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $vehiculoFavoritoId = SafeString::safe($_POST['vehiculoFavoritoId']);
  $clienteId = SafeString::safe($_POST['clienteId']);
  $car  = new Car();
  $car->setFavCar($vehiculoFavoritoId,$clienteId);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>