<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";

if (!isset($_POST['direccion']) || !isset($_POST['latitud']) || !isset($_POST['longitud']) ||
    !isset($_POST['idServicio']) || !isset($_POST['idTipoServicio']) ||
    !isset($_POST['token']) || !isset($_POST['idCoche']) || !isset($_POST['idCocheFavorito']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  

try{
  $direccion = SafeString::safe($_POST['direccion']);
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  $idServicio = SafeString::safe($_POST['idServicio']);
  $idTipoServicio = SafeString::safe($_POST['idTipoServicio']);
  $idCoche = SafeString::safe($_POST['idCoche']);
  $idCocheFavorito = SafeString::safe($_POST['idCocheFavorito']);
  $token = SafeString::safe($_POST['token']);

  $user  = new User();
  $clientInfo = $user->userHasToken($token);
  
  $service  = new Service();
  $idService = $service->requestService($direccion, $latitud,$longitud,$idServicio,$clientInfo['idCliente'],$idTipoServicio,$idCoche, $idCocheFavorito);
  $info = $service->getInfo($idService);
  echo json_encode(array("Status"=>"OK","info"=>$info));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
  	echo json_encode(array("Status"=>"SESSION ERROR"));
  }
?>