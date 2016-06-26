<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $service  = new Service();
  $servicesAndTypes = $service->getServices();
  echo json_encode(array("Status"=>"OK","Services"=>$servicesAndTypes['services'],"ServicesTypes"=>$servicesAndTypes['types']));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>