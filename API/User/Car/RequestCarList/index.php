<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $token = SafeString::safe($_POST['token']);
  $user = new User();
  $infoUser = $user->userHasToken($token);
  $car  = new Car();
  $carsList = $car->getCarsList($infoUser['idCliente']);
  echo json_encode(array("Status"=>"OK","carsList"=>$carsList));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
catch(carsNotFoundException $e)
{
  echo json_encode(array("Status"=>"OK","carsList"=>null));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>