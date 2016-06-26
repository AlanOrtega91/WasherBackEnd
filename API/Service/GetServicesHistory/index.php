<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['idCliente']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$clientId = SafeString::safe($_POST['idCliente']);
try{
  $service  = new Service();
  $servicesHistory = $service->getHistory($clientId);
  echo json_encode(array("Status"=>"OK","History"=>$servicesHistory));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>