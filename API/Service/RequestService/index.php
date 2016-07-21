<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['direccion']) || !isset($_POST['latitud']) || !isset($_POST['longitud']) ||
    !isset($_POST['idServicio']) || !isset($_POST['idTipoServicio']) ||
    !isset($_POST['idCliente']) || !isset($_POST['idCoche']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  

try{
  $direccion = SafeString::safe($_POST['direccion']);
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  $idServicio = SafeString::safe($_POST['idServicio']);
  $idTipoServicio = SafeString::safe($_POST['idTipoServicio']);
  $idCliente = SafeString::safe($_POST['idCliente']);
  $idCoche = SafeString::safe($_POST['idCoche']);

  $service  = new Service();
  $idService = $service->requestService($direccion, $latitud,$longitud,$idServicio,$idCliente,$idTipoServicio,$idCoche);
  $info = $service->getInfo($idService);
  echo json_encode(array("Status"=>"OK","info"=>$info));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (errorMakingPaymentException $e) {
  echo json_encode(array("Status"=>"ERROR Payment"));
}
?>