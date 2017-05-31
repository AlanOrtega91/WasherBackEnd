<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['favoriteCarId']) || !isset($_POST['token']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
try{
  $favoriteCarId = SafeString::safe($_POST['favoriteCarId']);
  $token = SafeString::safe($_POST['token']);
  $user = new User();
  $infoUser = $user->userHasToken($token);
  $car  = new Car();
  $car->deleteCar($favoriteCarId);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB". $e->getMessage()));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>