<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_POST['vehiculoId']) || !isset($_POST['vehiculoFavoritoId']) || !isset($_POST['color']) ||
    !isset($_POST['placas'])  || !isset($_POST['modelo']) || !isset($_POST['marca']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $vehiculoId = SafeString::safe($_POST['vehiculoId']);
  $vehiculoFavoritoId = SafeString::safe($_POST['vehiculoFavoritoId']);
  $color = SafeString::safe($_POST['color']);
  $placas = SafeString::safe($_POST['placas']);
  $modelo = SafeString::safe($_POST['modelo']);
  $marca = SafeString::safe($_POST['marca']);
  $car  = new Car();
  $car->editCar($vehiculoId,$vehiculoFavoritoId,$color,$placas,$modelo,$marca);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>