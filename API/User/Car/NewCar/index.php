<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";

if (!isset($_POST['vehiculoId']) || !isset($_POST['color']) || !isset($_POST['placas'])
		|| !isset($_POST['modelo']) || !isset($_POST['marca'])  || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $vehiculoId = SafeString::safe($_POST['vehiculoId']);
  $token = SafeString::safe($_POST['token']);
  $color = SafeString::safe($_POST['color']);
  $placas = SafeString::safe($_POST['placas']);
  $modelo = SafeString::safe($_POST['modelo']);
  $marca = SafeString::safe($_POST['marca']);
  $user = new User();
  $infoUser = $user->userHasToken($token);
  $car  = new Car();
  $carId = $car->addCar($vehiculoId,$infoUser['idCliente'],$color,$placas,$modelo,$marca);
  echo json_encode(array("Status"=>"OK","carId" => $carId));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>