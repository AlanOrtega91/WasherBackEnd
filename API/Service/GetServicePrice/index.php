<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";


if (!isset($_POST['idCoche']) || !isset($_POST['idServicio']) || !isset($_POST['idTipoServicio']) || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  

try{
  $carId = SafeString::safe($_POST['idCoche']);
  $serviceId = SafeString::safe($_POST['idServicio']);
  $typeOfServiceId = SafeString::safe($_POST['idTipoServicio']);
  $token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $service  = new Service();
  $price = $service->getPriceForCar($carId,$serviceId,$typeOfServiceId);
  echo json_encode(array("Status"=>"OK","price"=>$price));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>