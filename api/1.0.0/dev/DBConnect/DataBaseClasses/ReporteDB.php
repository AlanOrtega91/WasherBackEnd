<?php
require_once dirname ( __FILE__ ) . "/DataBase.php";
class ReporteDB {
	
	const GUARDAR_REPORTE = "INSERT INTO Reporte (latitud, longitud, Descripcion) VALUES('%s', '%s', '%s');";
	
	var $mysqli;
	public function __construct() {
		$this->mysqli = new mysqli ( DataBase::DB_LINK, DataBase::DB_LOGIN, DataBase::DB_PASSWORD, DataBase::DB_NAME );
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException ( "Error connecting with database" );
			$this->mysqli->set_charset("utf8");
	}
	
	public function guardarReporte($descripcion, $latitud, $longitud){
		$query = sprintf ( self::GUARDAR_REPORTE, $latitud, $longitud, $descripcion );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( "Could not create new user " . $this->mysqli->error );
	}
}

?>