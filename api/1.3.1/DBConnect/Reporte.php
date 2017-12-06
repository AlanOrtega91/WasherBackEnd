<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/ReporteDB.php";

class Reporte {
	
	
	private $dataBase;
	
	public function __construct() {
		$this->dataBase = new ReporteDB ();
	}
	
	function guardarReporte($descripcion, $latitud = null, $longitud = null){
		$this->dataBase->guardarReporte($descripcion, $latitud, $longitud);
	}
}
?>