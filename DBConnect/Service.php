<?php
include dirname(__FILE__)."/DataBaseClasses/DataBaseService.php";
class Service {
  
  private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseService();
	}
  
  public function getServices()
  {
    $services = $this->dataBase->readAllServices();
		$servicesList = array();
		while($service = $services->fetch_assoc())
			array_push($servicesList,$service);
		
		$serviceTypes = $this->dataBase->readAllServicesType();
		$serviceTypesList = array();
		while($type = $serviceTypes->fetch_assoc())
			array_push($serviceTypesList,$type);
			
		return array("services"=>$servicesList,"types"=>$serviceTypesList);
  }
  
  public function getInfo($serviceId)
  {
    $service = $this->dataBase->readService($serviceId);
		$info = $service->fetch_assoc();
		return $info;
  }
	
	public function getHistory($clientId)
  {
    $services = $this->dataBase->readServicesHistory($clientId);
		$servicesList = array();
		while($service = $services->fetch_assoc())
			array_push($servicesList,$service);
		
		return $servicesList;
  }
  
  public function getStatus($serviceId)
  {
    $service = $this->dataBase->readService($serviceId);
		$row = $service->fetch_assoc();
		$status = array("status"=>$row['status'],"finalTime"=>$row['horaFinalEstimada']);
		return $status;
  }
  
  public function changeServiceStatus($serviceId, $statusId)
  {
		if ($statusId == 4) {
			date_default_timezone_set('America/Mexico_City');
			$fecha = date("Y-m-d H:i:s");
			$this->dataBase->updateStartTimeService($serviceId, $fecha);
			$this->dataBase->removeCleanerProducts($serviceId);
		}
    $this->dataBase->updateService($serviceId, $statusId);
  }
  
  public function getCleaners($latitud, $longitud,$distance)
  {
    $cleaners = $this->dataBase->readCleanersLocation($latitud, $longitud, $distance);
		$cleanersList = array();
		while($cleaner = $cleaners->fetch_assoc())
			array_push($cleanersList,$cleaner);
			
		return $cleanersList;
  }
	
	public function getServicesNearby($latitud, $longitud,$distance)
	{
		$services = $this->dataBase->readServicesLocation($latitud, $longitud, $distance);
		$servicesList = array();
		while($service = $services->fetch_assoc())
			array_push($servicesList,$service);
			
		return $servicesList;
	}
  
  public function requestService($direccion, $latitud,$longitud,$idServicio,$idCliente,$idTipoServicio,$idCoche)
  {
		date_default_timezone_set('America/Mexico_City');
		$fecha = date("Y-m-d H:i:s");
    $idService = $this->dataBase->insertService($fecha,$direccion, $latitud,$longitud,$idServicio,$idCliente,$idTipoServicio,$idCoche);
		return $idService;
  }
	
	public function acceptService($serviceId,$cleanerId)
	{
		$this->dataBase->updateServiceAccepted($serviceId,$cleanerId);
	}
	
	public function getPriceForCar($carId,$serviceId,$typeOfServiceId)
	{
		return $this->dataBase->calculatePrice($carId, $typeOfServiceId, $serviceId);
	}
}
?>