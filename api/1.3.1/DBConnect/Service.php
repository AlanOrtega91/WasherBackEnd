<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseService.php";
require_once dirname ( __FILE__ ) . "/DataBaseClasses/PromocionDB.php";
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseUser.php";
require_once dirname ( __FILE__ ) . "/PushNotification.php";
class Service {
	private $dataBase;
	const CANCEL_PRICE = 0.20; 
	public function __construct() {
		$this->dataBase = new DataBaseService ();
	}
	public function saveTransaction($serviceId, $transactionId) {
		$service = $this->dataBase->updateTransactionId ( $serviceId, $transactionId );
	}
	public function getServices() {
		$services = $this->dataBase->readAllServices ();
		$servicesList = array ();
		while ( $service = $services->fetch_assoc () )
			array_push ( $servicesList, $service );
		
		$serviceTypes = $this->dataBase->readAllServicesType ();
		$serviceTypesList = array ();
		while ( $type = $serviceTypes->fetch_assoc () )
			array_push ( $serviceTypesList, $type );
		
		return array (
				"services" => $servicesList,
				"types" => $serviceTypesList 
		);
	}
	public function getInfo($serviceId) {
		$service = $this->dataBase->readService ( $serviceId );
		$info = $service->fetch_assoc ();
		return $info;
	}
	public function getActiveService($token, $userType) {
		if ($userType == 1)
			$service = $this->dataBase->readActiveServiceForUser ( $token );
		else
			$service = $this->dataBase->readActiveServiceForCleaner ( $token );
		$info = $service->fetch_assoc ();
		return $info;
	}
	public function getHistory($clientId, $clientType) {
		if ($clientType == 1)
			$services = $this->dataBase->readServicesHistoryForUser ( $clientId );
		else
			$services = $this->dataBase->readServicesHistoryForCleaner ( $clientId );
		$servicesList = array ();
		while ( $service = $services->fetch_assoc () )
			array_push ( $servicesList, $service );
		
		return $servicesList;
	}
	public function sendReview($serviceId, $rating) {
		$this->dataBase->updateReview ( $serviceId, $rating );
		$line = $this->getInfo ( $serviceId );
		$reviewRow = $this->dataBase->readReviewForCleaner ( $line ['idLavador'] );
		$review = $reviewRow->fetch_assoc ();
		
		$message = array (
				"state" => "-1",
				"rating" => $review ['Calificacion'], 
				"message" => "Alguien te califico"
		);
		$row = $this->dataBase->readPushNotificationToken ( $serviceId );
		$token = $row->fetch_assoc ();
		PushNotification::sendNotification($token ['pushNotificationTokenLavador'], $message,$token ['cleanerDevice'],"cleaner");
	}
	public function getStatus($serviceId) {
		$service = $this->dataBase->readService ( $serviceId );
		$row = $service->fetch_assoc ();
		$status = array (
				"status" => $row ['status'],
				"finalTime" => $row ['horaFinalEstimada'] 
		);
		return $status;
	}
	public function readReviewForCleaner($cleanerId) {
		$reviewRow = $this->dataBase->readReviewForCleaner ( $cleanerId );
		$review = $reviewRow->fetch_assoc ();
		return $review ['Calificacion'];
	}
	public function changeServiceStatus($serviceId, $statusId, $cancelCode) {
	    date_default_timezone_set ( 'America/Mexico_City' );
	    $fecha = date ( "Y-m-d H:i:s" );
		if ($statusId == 2){
			$this->dataBase->updateAcceptTimeService ( $serviceId, $fecha );
		}
		if ($statusId == 4) {
			$this->dataBase->updateStartTimeService ( $serviceId, $fecha );
			$this->dataBase->removeCleanerProducts ( $serviceId );
		}
		$line = $this->getInfo ( $serviceId );
		$metodoDePago = $line['pago'];
		if ($statusId == 6) {
			if ($cancelCode == 1) {
				try {
					if($metodoDePago == "t") 
					{
						$this->makePayment ( $serviceId, $line, $line ['precio'] * Service::CANCEL_PRICE );
					}
				} catch ( Exception $e ) {
					$this->dataBase->blockUser ( $line ['idCliente'] );
				}
			} else if ($cancelCode == 0) {
				$this->checkForCancel ( $serviceId );
			} else {
				$this->checkForCancelTakingTooLong ( $serviceId );
			}
		}
		
		if ($statusId == 5) {
			try {
				$this->makePayment ( $serviceId, $line, $line ['precio'] );
				$this->revisaPromocionesDeNumeroDeLavadas($line ['idCliente']);
			} catch ( Exception $e ) {
				$this->dataBase->blockUser ( $line ['idCliente'] );
			}
		}
		
		$this->dataBase->updateService ( $serviceId, $statusId );
		$service = $this->dataBase->readService ( $serviceId );
		$line = $service->fetch_assoc ();
		$message = $this->selectMessage ( $statusId, $line );
		$row = $this->dataBase->readPushNotificationToken ( $serviceId );
		$token = $row->fetch_assoc ();
		PushNotification::sendNotification($token ['pushNotificationTokenCliente'], $message,$token ['clientDevice'],"client");
		PushNotification::sendNotification($token ['pushNotificationTokenLavador'], $message,$token ['cleanerDevice'],"cleaner");
	}
	
	public function checkForCancel($serviceId) {
		$service = $this->dataBase->readService ( $serviceId );
		$info = $service->fetch_assoc ();
		if ($info ['idLavador'] != null)
			throw new serviceCantBeCanceled ();
	}
	public function checkForCancelTakingTooLong($serviceId) {
		$service = $this->dataBase->readService ( $serviceId );
		$info = $service->fetch_assoc ();
		if ($info ['status'] == "Started" || $info ['status'] == "Finished")
			throw new serviceCantBeCanceled ();
	}
	public function selectMessage($statusId, $line) {
		switch ($statusId) {
			case 2 :
				return array (
						"state" => "2",
						"message" => "Tu servicio ya fue aceptado",
						"serviceInfo" => $line 
				);
			case 3 :
				return array (
						"state" => "3",
						"message" => "Tu servicio va de camino",
						"serviceInfo" => $line 
				);
			case 4 :
				return array (
						"state" => "4",
						"message" => "Tu servicio ha comenzado",
						"serviceInfo" => $line 
				);
			case 5 :
				return array (
						"state" => "5",
						"message" => "Tu servicio se ha completado",
						"serviceInfo" => $line 
				);
			case 6 :
				return array (
						"state" => "6",
						"message" => "Tu servicio fue cancelado",
						"serviceInfo" => $line 
				);
		}
	}
	function makePayment($serviceId, $line, $price) {
		if ($line ['idTransaccion'] == null) {
		    $precioFaltante = $this->revisaYAplicaDescuento($line['idCliente'], $price, $serviceId);
		    if ($precioFaltante > 0) {
		        $precioFaltante = $this->revisaYAplicaCredito($line['idCliente'], $precioFaltante, $serviceId);
		        if ($precioFaltante > 3 && $line['pago'] == "t") {
		        	$transactionId = Payment::makeTransaction ( $precioFaltante, $line['ConektaId']);
			        $this->saveTransaction ( $serviceId, $transactionId );
		        } else if($precioFaltante > 0 && $line['pago'] == "e") {
					$transactionId = 'efe_'.md5 ( uniqid ( mt_rand (), true ) );
			        $this->saveTransaction ( $serviceId, $transactionId );
		        } else {
		        	$transactionId = 'cred_'.md5 ( uniqid ( mt_rand (), true ) );
			        $this->saveTransaction ( $serviceId, $transactionId );
		        }
		    } else {
		        $transactionId = 'desc_'.md5 ( uniqid ( mt_rand (), true ) );
		        $this->saveTransaction ( $serviceId, $transactionId );
		    }
		} else {

		}
	}
	
	function revisaYAplicaDescuento($idCliente, $precio, $idServicio) 
	{
	    $promocionDB = new PromocionDB();
	    $precioFaltante = $precio;
	    if ($promocionDB->usuarioTieneDescuento($idCliente))
	    {
	        $descuento = $promocionDB->leeUnDescuentoDeUsuario($idCliente);
	        $precioFaltante = $precio - ($precio * $descuento['cantidad'] / 100);
	        $promocionDB->marcarDescuentoComoUsado($descuento['id']);
	        $promocionDB->agregarReferenciaDeDescuentoUsado($idServicio, $descuento['id']);
	    }
	    return $precioFaltante;
	}
	
	function revisaYAplicaCredito($idCliente, $precio, $idServicio)
	{
	    $promocionDB = new PromocionDB();
	    $usuarioDB = new DataBaseUser();
	    $precioFaltante = $precio;
	    $credito = $usuarioDB->readUserInfoById($idCliente)['credito'];
	    if ($credito > 0)
	    {
	        $creditoAUtilizar = $this->calcularCreditoAUtilizar($credito,$precio);
	        $precioFaltante = $precio - $creditoAUtilizar;
	        $promocionDB->descontarCredito($idCliente, $creditoAUtilizar);
	        $promocionDB->agregarReferenciaDeCreditoUsado($idServicio, $idCliente, $creditoAUtilizar);
	    }
	    return $precioFaltante;
	}
	
	function calcularCreditoAUtilizar($credito,$precio)
	{
	    if ($credito > $precio) 
	    {
	        return $precio;
	    } else {
	        return $credito;
	    }
	}

	function revisaPromocionesDeNumeroDeLavadas($idCliente)
	{
		$promocionDB = new PromocionDB();
		$promocion = $promocionDB->leerUnaPromocionPorNumero($idCliente);
		if (!is_null($promocion['cantidad'])) {
			$numeroDeLavadas = $promocion['numeroDeLavadasActuales'] + 1;
	    	if($numeroDeLavadas >= $promocion['cantidad']) {
	    		$nuevoCodigo = $idCliente."-".$promocion['codigoPromocion'];
	    		$promocionDB->creaPromocionUnica($nuevoCodigo);
	    		$promocionDB->agregarAbonoDePromocion($nuevoCodigo, $idCliente, "FALSE");
	    		$promocionDB->marcarDescuentoComoUsado($promocion['id']);
	    	} else {
	    		$promocionDB->actualizarLavadasActuales($promocion['id'], $numeroDeLavadas);
	    	}
		}
	}
	
	
	public function getCleaners($latitud, $longitud, $distance) {
		$cleaners = $this->dataBase->readCleanersLocation ( $latitud, $longitud, $distance );
		$cleanersList = array ();
		while ( $cleaner = $cleaners->fetch_assoc () )
			array_push ( $cleanersList, $cleaner );
		
		return $cleanersList;
	}
	public function getCleanerLocation($cleanerId) {
		return $this->dataBase->readCleanerLocation ( $cleanerId );
	}
	public function getServicesNearby($latitud, $longitud, $distance) {
		$services = $this->dataBase->readServicesLocation ( $latitud, $longitud, $distance );
		$servicesList = array ();
		while ( $service = $services->fetch_assoc () )
			array_push ( $servicesList, $service );
		
		return $servicesList;
	}
	public function requestService($direccion, $latitud, $longitud, $idServicio, $idCliente, $idCoche, $idCocheFavorito, $metodoDePago, $idRegion) {
		if ($this->dataBase->usuarioEstaBloqueado($idCliente)) {
			throw new usuarioBloqueado();
		}
		date_default_timezone_set ( 'America/Mexico_City' );
		$fecha = date ( "Y-m-d H:i:s" );
		$precio = $this->dataBase->calculatePrice($idCoche, $idServicio, $idRegion);
		$precioAPagar = $this->calculaPrecioAPagar($precio,$idCliente);
		$idService = $this->dataBase->insertService ( $fecha, $direccion, $latitud, $longitud, $idServicio, $idCliente, $idCoche , $idCocheFavorito, $metodoDePago, $precio, $precioAPagar, $idRegion);
		return $idService;
	}

	function calculaPrecioAPagar($precio,$idCliente)
	{
		$precioFaltante = $this->revisaDescuento($idCliente, $precio);
		if ($precioFaltante > 0) {
			$precioFaltante = $this->revisaCredito($idCliente, $precioFaltante);
		} 
		return $precioFaltante;
	}

	function revisaDescuento($idCliente, $precio) 
	{
	    $promocionDB = new PromocionDB();
	    $precioFaltante = $precio;
	    if ($promocionDB->usuarioTieneDescuento($idCliente))
	    {
	        $descuento = $promocionDB->leeUnDescuentoDeUsuario($idCliente);
	        $precioFaltante = $precio - ($precio * $descuento['cantidad'] / 100);
	    }
	    return $precioFaltante;
	}
	
	function revisaCredito($idCliente, $precio)
	{
	    $promocionDB = new PromocionDB();
	    $usuarioDB = new DataBaseUser();
	    $precioFaltante = $precio;
	    $credito = $usuarioDB->readUserInfoById($idCliente)['credito'];
	    if ($credito > 0)
	    {
	        $creditoAUtilizar = $this->calcularCreditoAUtilizar($credito,$precio);
	        $precioFaltante = $precio - $creditoAUtilizar;
	    }
	    return $precioFaltante;
	}

	public function acceptService($serviceId, $cleanerId, $token) {
		$this->dataBase->updateServiceAccepted ( $serviceId, $cleanerId );
		$this->changeServiceStatus ( $serviceId, 2 , 0);
		return $this->getActiveService ( $token, 2 );
	}

	function leerPrecios($latitud, $longitud)
	{
		$precios = $this->dataBase->leerPrecios($latitud, $longitud);
		for ($preciosLista = array(); $fila = $precios->fetch_assoc(); $preciosLista[] = $fila);
		return $preciosLista;
	}
}
class serviceCantBeCanceled extends Exception {
}
class usuarioBloqueado extends Exception {
}
?>