<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseCar extends DataBase{
  
  const QUERY_INSERT_CAR = "INSERT INTO Vehiculos_Favoritos (idVehiculo, idCliente, Favorito, Color, Placas, Marca)
		VALUES ('%s', '%s', 1, '%s', '%s', '%s' )
		;";
		const QUERY_DELETE_CAR = "DELETE FROM Vehiculos_Favoritos WHERE idVehiculoFavorito = '%s';";
		const QUERY_UPDATE_CAR = "UPDATE Vehiculos_Favoritos SET
		idVehiculo = '%s',
		Color = '%s',
		Placas = '%s',
		Marca = '%s'
		WHERE idVehiculoFavorito = '%s'
		;";
		const QUERY_READ_FAVORITE_CAR_LIST = "SELECT * FROM
		Vehiculos_Favoritos LEFT JOIN Vehiculo ON
		Vehiculos_Favoritos.idVehiculo = Vehiculo.idVehiculo
		WHERE idCliente = '%s'
		;";
		
		const QUERY_UNSET_FAV_CAR = "UPDATE Vehiculos_Favoritos SET Favorito = 0 WHERE idCliente = '%s'";
		const QUERY_SET_FAV_CAR = "UPDATE Vehiculos_Favoritos SET Favorito = 1 WHERE idVehiculoFavorito = '%s'";
    
  
  public function insertCar($vehiculoId,$clientId,$color,$placas,$marca)
	{
		$query = sprintf(DataBaseCar::QUERY_INSERT_CAR,$vehiculoId,$clientId,$color,$placas,$marca);
		$this->ejecutarQuery($query);
		return $this->mysqli->insert_id;
	}
	
	public function deleteCar($favoriteCarId)
	{
		$query = sprintf(DataBaseCar::QUERY_DELETE_CAR,$favoriteCarId);
		$this->ejecutarQuery($query);
	}
	
	public function updateCar($vehiculoId,$vehiculoFavoritoId,$color,$placas,$marca){
		$query = sprintf(DataBaseCar::QUERY_UPDATE_CAR,$vehiculoId,$color,$placas,$marca,$vehiculoFavoritoId);
		$this->ejecutarQuery($query);
	}
	
	public function updateFavoriteCar($vehiculoFavoritoId,$clienteId){
		$query = sprintf(DataBaseCar::QUERY_UNSET_FAV_CAR,$clienteId);
		$this->ejecutarQuery($query);

		$query = sprintf(DataBaseCar::QUERY_SET_FAV_CAR,$vehiculoFavoritoId);
		$this->ejecutarQuery($query);
	}
	
	public function readFavoriteCars($clientId)
	{
		$query = sprintf(DataBaseCar::QUERY_READ_FAVORITE_CAR_LIST,$clientId);
		return $result = $this->ejecutarQuery($query);
	}
}