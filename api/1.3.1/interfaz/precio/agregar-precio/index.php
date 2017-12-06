<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Precio.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['idServicio']) || !isset($_POST['idVehiculo']) || !isset($_POST['idRegion']) || !isset($_POST['precio']))
{
  die(json_encode(array("Status"=>"ERROR missing values")));
}


try{
	$idServicio = SafeString::safe($_POST['idServicio']);
	$idVehiculo = SafeString::safe($_POST['idVehiculo']);
	$idRegion = SafeString::safe($_POST['idRegion']);
	$precio = SafeString::safe($_POST['precio']);

	$precioC = new Precio();
	$precioC->agregarPrecio($idServicio, $idVehiculo, $idRegion, $precio);
	echo json_encode(array(
      "estado"=>"ok",
  )); 
} catch(Exception $e) {
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"desconocido",
        "explicacion"=>$e->getMessage()
    ));
}
?>