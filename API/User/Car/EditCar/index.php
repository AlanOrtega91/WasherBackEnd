<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['vehiculoId']) || !isset($_POST['vehiculoFavoritoId']) || !isset($_POST['color']) ||
    !isset($_POST['placas'])  || !isset($_POST['marca'])  || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $vehiculoId = SafeString::safe($_POST['vehiculoId']);
  $vehiculoFavoritoId = SafeString::safe($_POST['vehiculoFavoritoId']);
  $color = SafeString::safe($_POST['color']);
  $placas = SafeString::safe($_POST['placas']);
  $marca = SafeString::safe($_POST['marca']);
  $token = SafeString::safe($_POST['token']);
  $user = new User();
  $infoUser = $user->userHasToken($token);
  $car  = new Car();
  $car->editCar($vehiculoId,$vehiculoFavoritoId,$color,$placas,$marca);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>