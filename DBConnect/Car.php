<?php
require_once dirname(__FILE__)."/DataBaseClasses/DataBaseCar.php";
class Car {
  
  private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseCar();
	}
  
		public function addCar($vehiculoId,$clientId,$color,$placas,$modelo,$marca)
	{
		return $this->dataBase->insertCar($vehiculoId,$clientId,$color,$placas,$modelo,$marca);
	}
	
	public function editCar($vehiculoId,$vehiculoFavoritoId,$color,$placas,$modelo,$marca){
		$this->dataBase->updateCar($vehiculoId,$vehiculoFavoritoId,$color,$placas,$modelo,$marca);
	}
	
	public function deleteCar($favoriteCarId)
	{
		$this->dataBase->deleteCar($favoriteCarId);
	}
	
	public function setFavCar($vehiculoFavoritoId,$clienteId){
		$this->dataBase->updateFavoriteCar($vehiculoFavoritoId,$clienteId);
	}
	
	public function getCarsList($clientId)
	{
		$cars = $this->dataBase->readFavoriteCars($clientId);
		$carsList = array();
		while($car = $cars->fetch_assoc())
			array_push($carsList,$car);
			
		return $carsList;
	}
}