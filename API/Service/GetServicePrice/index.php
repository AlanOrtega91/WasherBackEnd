<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_GET['idCoche']) || !isset($_GET['idServicio']) || !isset($_GET['idTipoServicio']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$carId = SafeString::safe($_GET['idCoche']);
$serviceId = SafeString::safe($_GET['idServicio']);
$typeOfServiceId = SafeString::safe($_GET['idTipoServicio']);
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