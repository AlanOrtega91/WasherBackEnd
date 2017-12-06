<?php
require_once dirname(__FILE__)."/DataBaseClasses/PrecioDB.php";
class Precio
{
	private $dataBase;

	public function __construct()
	{
	    $this->dataBase = new PrecioDB();
	}

	function agregarPrecio($idServicio, $idVehiculo, $idRegion, $precio)
	{
		$this->dataBase->agregarPrecio($idServicio, $idVehiculo, $idRegion, $precio);
	}
}
?>