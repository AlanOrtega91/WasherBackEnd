<?php
require_once dirname(__FILE__)."/DataBase.php";

class RegionDB extends DataBase
{
	const AGREGAR_REGION = "INSERT INTO Region (nombre, descripcion, prioridad, 
		latitudInicio, longitudInicio, latitudFinal, longitudFinal) 
		VALUES ('%s', '%s', %s, %s, %s, %s, %s)";


	function agregarRegion($nombre, $descripcion, $prioridad, $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal)
	{
		$query = sprintf ( self::AGREGAR_REGION, $nombre, $descripcion, $prioridad, 
			$latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal);
	    $this->ejecutarQuery($query);
	}
}
?>