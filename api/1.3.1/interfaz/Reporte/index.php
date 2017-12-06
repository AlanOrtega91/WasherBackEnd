<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Reporte.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['descripcion']))
  die(json_encode(array("Status"=>"ERROR missing values")));


try{
	$descripcion = SafeString::safe($_POST['descripcion']);
	$latitud = null;
	if (isset($_POST['latitud'])) {
		$latitud = SafeString::safe($_POST['latitud']);
	}
	$longitud = null;
	if (isset($_POST['$longitud'])) {
		$longitud= SafeString::safe($_POST['$longitud']);
	}
	$reporte= new Reporte();
  $info = $reporte->guardarReporte($descripcion, $latitud, $longitud);
} catch(Exception $e) {
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"desconocido",
        "explicacion"=>$e->getMessage()
    ));
}
?>