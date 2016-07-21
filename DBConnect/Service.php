<?php
require_once dirname(__FILE__)."/DataBaseClasses/DataBaseService.php";
require_once dirname(__FILE__)."/PushNotification.php";
require_once dirname(__FILE__).'/../braintree/lib/Braintree.php';

class Service {
  
  private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseService();
	}
  
	public function saveTransaction($serviceId,$transactionId){
		$service = $this->dataBase->updateTransactionId($serviceId,$transactionId);
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
	
	public function getActiveService($token,$userType)
  {
		if($userType == 1)
			$service = $this->dataBase->readActiveServiceForUser($token);
		else
			$service = $this->dataBase->readActiveServiceForCleaner($token);
		$info = $service->fetch_assoc();
		return $info;
  }
	
	public function getHistory($clientId,$clientType)
  {
		if($clientType == 1)
			$services = $this->dataBase->readServicesHistoryForUser($clientId);
		else
			$services = $this->dataBase->readServicesHistoryForCleaner($clientId);
		$servicesList = array();
		while($service = $services->fetch_assoc())
			array_push($servicesList,$service);
		
		return $servicesList;
  }
	
	public function sendReview($serviceId,$rating){
		$this->dataBase->updateReview($serviceId,$rating);
	}
  
  public function getStatus($serviceId)
  {
    $service = $this->dataBase->readService($serviceId);
		$row = $service->fetch_assoc();
		$status = array("status"=>$row['status'],"finalTime"=>$row['horaFinalEstimada']);
		return $status;
  }
	
	public function readReviewForCleaner($cleanerId){
		$reviewRow = $this->dataBase->readReviewForCleaner($cleanerId);
		$review = $reviewRow->fetch_assoc();
		return $review['Calificacion'];
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
				$service = $this->dataBase->readService($serviceId);
				$line = $service->fetch_assoc();
				$message = $this->selectMessage($statusId, $line);
				$row = $this->dataBase->readPushNotificationToken($serviceId);
				$token = $row->fetch_assoc();
				PushNotification::sendNotification(array($token['pushNotificationTokenCliente'],$token['pushNotificationTokenLavador']),$message);
				PushNotification::sendMessage(array($token['pushNotificationTokenCliente'],$token['pushNotificationTokenLavador']),$message);
  }
		
		public function selectMessage($statusId,$line) {
				switch($statusId){
						case 2:
								return array("state" => "2","message" => "Tu servicio ya fue aceptado","serviceInfo" => $line);
						case 3:
								return array("state" => "3","message" => "Tu servicio va de camino","serviceInfo" => $line);
						case 4:
								return array("state" => "4","message" => "Tu servicio ha comenzado","serviceInfo" => $line);
						case 5:
								return array("state" => "5","message" => "Tu servicio se ha completado","serviceInfo" => $line);
						case 6:
								return array("state" => "6","message" => "Tu servicio fue cancelado","serviceInfo" => $line);
				}
		}
		
		function makePayment($serviceId){
				$service = $this->dataBase->readService($serviceId);
				$line = $service->fetch_assoc();
				if($line['idTransaccion'] == null){
						$idClient = $this->dataBase->getUserId($serviceId);
						$this->makeTransaction($serviceId, $idClient);
				}
		}
		
		function makeTransaction($serviceId, $id)
		{
				Braintree_Configuration::environment('sandbox');
				Braintree_Configuration::merchantId('ncjjy77xdztwsny3');
				Braintree_Configuration::publicKey('dhhbskndbg9nmwk2');
				Braintree_Configuration::privateKey('ab312b96bf5d161816b0f248779d04e3');
				$info = $this->getInfo($serviceId);
				$price = $info['precio'];
				$Braintree_Transaction = Braintree_Transaction::sale(array(
                                         'customerId' => $id,
                                         'amount' => $price
                                         ));
				if(!$Braintree_Transaction->success)
						throw new errorMakingPaymentException();
				
				$transactionId = $Braintree_Transaction->transaction->id;
				$this->saveTransaction($serviceId,$transactionId);
}
  
  public function getCleaners($latitud, $longitud,$distance)
  {
    $cleaners = $this->dataBase->readCleanersLocation($latitud, $longitud, $distance);
		$cleanersList = array();
		while($cleaner = $cleaners->fetch_assoc())
			array_push($cleanersList,$cleaner);
			
		return $cleanersList;
  }
		
		public function getCleanerLocation($cleanerId){
				return $this->dataBase->readCleanerLocation($cleanerId);
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
				$this->makePayment($idService);
				return $idService;
  }
	
	public function acceptService($serviceId,$cleanerId)
	{
		$this->dataBase->updateServiceAccepted($serviceId,$cleanerId);
		$this->changeServiceStatus($serviceId, 2);
	}
	
	public function getPriceForCar($carId,$serviceId,$typeOfServiceId)
	{
		return $this->dataBase->calculatePrice($carId, $typeOfServiceId, $serviceId);
	}
}
?>