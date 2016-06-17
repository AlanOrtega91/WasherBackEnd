<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_GET['latitud']) || !isset($_GET['longitud']) || !isset($_GET['distancia']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$distance = SafeString::safe($_GET['distancia']);
$latitud = SafeString::safe($_GET['latitud']);
$longitud = SafeString::safe($_GET['longitud']);
try{
  $service  = new Service();
  $cleaners = $service->getCleaners($latitud, $longitud,$distance);
  echo json_encode(array("Status"=>"OK","cleaners"=>$cleaners));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>