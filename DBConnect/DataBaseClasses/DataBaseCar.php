<?php
include dirname(__FILE__)."/DataBase.php";
class DataBaseCar {
  
  const QUERY_INSERT_CAR = "INSERT INTO Coche (Placas, Color, idTamanioCoche, idTipoCoche)
		VALUES ('%s', '%s', '%s', '%s')
		;";
		const QUERY_INSERT_FAVORITE_CAR = "INSERT INTO Vehiculos_Favoritos (idVehiculo, idCliente, Color, Placas)
		VALUES ('%s', '%s', '%s', '%s')
		;";
		const QUERY_READ_FAVORITE_CAR_LIST = "SELECT * FROM
		Vehiculos_Favoritos LEFT JOIN Vehiculo ON
		Vehiculos_Favoritos.idVehiculo = Vehiculo.idVehiculo
		WHERE idCliente = '%s'
		;";
    
    var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
  }
  
  public function insertCar($placas,$color, $tamanioId, $tipoId)
	{
		$query = sprintf(DataBaseCar::QUERY_INSERT_CAR,$placas,$color, $tamanioId, $tipoId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed'.$this->mysqli->error);
		return $this->mysqli->insert_id;
	}
	
	public function insertFavoriteCar($carId,$clientId)
	{
		$query = sprintf(DataBaseCar::QUERY_INSERT_FAVORITE_CAR,$carId, $clientId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	
	public function readFavoriteCars($clientId)
	{
		$query = sprintf(DataBaseCar::QUERY_READ_FAVORITE_CAR_LIST,$clientId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		
		if($result->num_rows === 0)
			throw new carsNotFoundException("No favorite cars found");
		
    return $result;
	}
}
class carsNotFoundException extends Exception{
	}
class errorWithDatabaseException extends Exception{
	}