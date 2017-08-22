<?php
require_once dirname ( __FILE__ ) . "/DataBase.php";
class ReporteDB extends DataBase{
	
	const GUARDAR_REPORTE = "INSERT INTO Reporte (latitud, longitud, Descripcion) VALUES('%s', '%s', '%s');";

	
	public function guardarReporte($descripcion, $latitud, $longitud){
		$query = sprintf ( self::GUARDAR_REPORTE, $latitud, $longitud, $descripcion );
		$this->ejecutarQuery($query);
	}
}

?>