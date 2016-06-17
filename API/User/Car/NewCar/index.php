<?php
include dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_GET['placas']) || !isset($_GET['color']) || !isset($_GET['tamanioId']) || !isset($_GET['tipoId']) || !isset($_GET['clienteId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$placas = SafeString::safe($_GET['placas']);
$color = SafeString::safe($_GET['color']);
$tamanioId = SafeString::safe($_GET['tamanioId']);
$tipoId = SafeString::safe($_GET['tipoId']);
$clientId = SafeString::safe($_GET['clienteId']);
try{
  $car  = new Car();
  $idCoche = $car->addCar($placas,$color, $tamanioId, $tipoId, $clientId);
  echo json_encode(array("Status"=>"OK","idCoche"=>$idCoche));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>