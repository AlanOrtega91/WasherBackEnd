<?php
require_once dirname(__FILE__)."/DataBaseClasses/DataBaseCar.php";
class Car {
  
  private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseCar();
	}
  
  public function addCar($placas,$color, $tamanioId, $tipoId, $clientId)
	{
		$carId = $this->dataBase->insertCar($placas,$color, $tamanioId, $tipoId);
		$this->dataBase->insertFavoriteCar($carId,$clientId);
		return $carId;
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