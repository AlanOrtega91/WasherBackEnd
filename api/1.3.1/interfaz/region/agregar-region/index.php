<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Region.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['nombre']) || !isset($_POST['prioridad']) || !isset($_POST['latitudInicio']) 
	|| !isset($_POST['longitudInicio']) || !isset($_POST['latitudFinal']) || !isset($_POST['longitudFinal']))
{
  die(json_encode(array("Status"=>"ERROR missing values")));
}


try{
	$nombre = SafeString::safe($_POST['nombre']);
	$prioridad = SafeString::safe($_POST['prioridad']);
	$latitudInicio = SafeString::safe($_POST['latitudInicio']);
	$longitudInicio = SafeString::safe($_POST['longitudInicio']);
	$latitudFinal = SafeString::safe($_POST['latitudFinal']);
	$longitudFinal = SafeString::safe($_POST['longitudFinal']);

	$descripcion = null;
	if (isset($_POST['descripcion'])) {
		$descripcion = SafeString::safe($_POST['descripcion']);
	}

	$region = new Region();
	$region->agregarRegion($nombre, $descripcion, $prioridad, $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal);
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