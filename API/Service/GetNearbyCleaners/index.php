<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['latitud']) || !isset($_POST['longitud']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$distance = 5;
$latitud = SafeString::safe($_POST['latitud']);
$longitud = SafeString::safe($_POST['longitud']);
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