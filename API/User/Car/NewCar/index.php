<?php
include dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../../DBConnect/Car.php";

if (!isset($_POST['placas']) || !isset($_POST['color']) || !isset($_POST['tamanioId']) || !isset($_POST['tipoId']) || !isset($_POST['clienteId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$placas = SafeString::safe($_POST['placas']);
$color = SafeString::safe($_POST['color']);
$tamanioId = SafeString::safe($_POST['tamanioId']);
$tipoId = SafeString::safe($_POST['tipoId']);
$clientId = SafeString::safe($_POST['clienteId']);
try{
  $car  = new Car();
  $idCoche = $car->addCar($placas,$color, $tamanioId, $tipoId, $clientId);
  echo json_encode(array("Status"=>"OK","idCoche"=>$idCoche));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>