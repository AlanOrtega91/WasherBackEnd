<?php
require_once dirname(__FILE__)."/DataBase.php";

class PrecioDB extends DataBase
{
	const AGREGAR_PRECIO = "INSERT INTO Precio_Servicio (idServicio, idVehiculo, idRegion, Precio) 
		VALUES (%s, %s, %s, %s)";


	function agregarPrecio($idServicio, $idVehiculo, $idRegion, $precio)
	{
		$query = sprintf ( self::AGREGAR_PRECIO, $idServicio, $idVehiculo, $idRegion, $precio);
	    $this->ejecutarQuery($query);
	}
}
?>