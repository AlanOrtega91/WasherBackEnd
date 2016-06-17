<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_GET['direccion']) || !isset($_GET['latitud']) || !isset($_GET['longitud']) ||
    !isset($_GET['idServicio']) || !isset($_GET['idTipoServicio']) ||
    !isset($_GET['idCliente']) || !isset($_GET['idCoche']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$direccion = SafeString::safe($_GET['direccion']);
$latitud = SafeString::safe($_GET['latitud']);
$longitud = SafeString::safe($_GET['longitud']);
$idServicio = SafeString::safe($_GET['idServicio']);
$idTipoServicio = SafeString::safe($_GET['idTipoServicio']);
$idCliente = SafeString::safe($_GET['idCliente']);
$idCoche = SafeString::safe($_GET['idCoche']);
try{
  $service  = new Service();
  $idService = $service->requestService($direccion, $latitud,$longitud,$idServicio,$idCliente,$idTipoServicio,$idCoche);
  echo json_encode(array("Status"=>"OK","idService"=>$idService));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>