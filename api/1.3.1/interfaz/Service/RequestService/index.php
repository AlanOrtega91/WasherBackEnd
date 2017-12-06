<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['direccion']) || !isset($_POST['latitud']) || !isset($_POST['longitud']) ||
    !isset($_POST['idServicio']) || !isset($_POST['token']) || !isset($_POST['idCoche']) || 
		!isset($_POST['idCocheFavorito']) || !isset($_POST['metodoDePago']) || !isset($_POST['idRegion']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
} 

try
{
  $direccion = SafeString::safe($_POST['direccion']);
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  $idServicio = SafeString::safe($_POST['idServicio']);
  $idCoche = SafeString::safe($_POST['idCoche']);
  $idCocheFavorito = SafeString::safe($_POST['idCocheFavorito']);
  $token = SafeString::safe($_POST['token']);
  $metodoDePago = SafeString::safe($_POST['metodoDePago']);
  $idRegion = SafeString::safe($_POST['idRegion']);
  $user  = new User();
  $clientInfo = $user->userHasToken($token);
  
  $service  = new Service();
  $idService = $service->requestService($direccion, $latitud,$longitud,$idServicio,$clientInfo['idCliente'],$idCoche, $idCocheFavorito, $metodoDePago, $idRegion);
  $info = $service->getInfo($idService);
  echo json_encode(array(
  		"estado"=>"ok",
  		"servicio"=>$info,
  ));
} 
catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} 
catch (noSessionFoundException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"sesion",
			"explicacion"=>$e->getMessage()
	));
} 
catch (usuarioBloqueado $e) 
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"bloqueo",
			"explicacion"=>$e->getMessage()
	));
}
?>