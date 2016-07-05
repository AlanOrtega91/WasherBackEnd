<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['idCoche']) || !isset($_POST['idServicio']) || !isset($_POST['idTipoServicio']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$carId = SafeString::safe($_POST['idCoche']);
$serviceId = SafeString::safe($_POST['idServicio']);
$typeOfServiceId = SafeString::safe($_POST['idTipoServicio']);
try{
  $service  = new Service();
  $price = $service->getPriceForCar($carId,$serviceId,$typeOfServiceId);
  echo json_encode(array("Status"=>"OK","price"=>$price));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>