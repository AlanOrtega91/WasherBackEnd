<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['vehiculoFavoritoId']) || !isset($_POST['token']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
try{
  $vehiculoFavoritoId = SafeString::safe($_POST['vehiculoFavoritoId']);
  $token = SafeString::safe($_POST['token']);
  $user = new User();
  $infoUser = $user->userHasToken($token);
  $car  = new Car();
  $car->setFavCar($vehiculoFavoritoId,$infoUser['idCliente']);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>