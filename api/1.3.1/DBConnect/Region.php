<?php
require_once dirname(__FILE__)."/DataBaseClasses/RegionDB.php";
class Region
{
	private $dataBase;

	public function __construct()
	{
	    $this->dataBase = new RegionDB();
	}

	function agregarRegion($nombre, $descripcion, $prioridad, $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal)
	{
		$this->dataBase->agregarRegion($nombre, $descripcion, $prioridad, $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal);
	}
}
?>