<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseService {
  
  const QUERY_READ_ALL_SERVICES = "SELECT * FROM Servicio;";
	const QUERY_READ_ALL_SERVICES_TYPE = "SELECT * FROM Tipo_Servicio;";
	const QUERY_READ_MULTIPLIERS_FOR_CAR = "SELECT Multiplicador  AS precio
	FROM Vehiculo
	WHERE idVehiculo = '%s'
	;";
	const QUERY_READ_PRICE_FOR_SERVICE = "SELECT Servicio.PrecioBase AS precio
	FROM Servicio
	WHERE Servicio.idServicio = '%s'
	;";
	const QUERY_READ_PRICE_FOR_SERVICE_TYPE = "SELECT Tipo_Servicio.Multiplicador AS precio
	FROM Tipo_Servicio 
	WHERE Tipo_Servicio.idTipoServicio = '%s'
	;";
	const QUERY_INSERT_SERVICE = "INSERT INTO Servicio_Pedido (FechaPedido, Direccion, Latitud, Longitud, Precio, idServicio,
	idCliente, idTipoServicio, idVehiculo)
	VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
	;";
	const QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_OPEN = "SELECT * FROM Servicio_Pedido
	WHERE idServicioPedido = '%s' AND idLavador IS NULL
	;";
	const QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_AVAILABLE_PRODUCTS = "SELECT * FROM Servicio_Pedido
	LEFT JOIN Servicio ON
	Servicio.idServicio = Servicio_Pedido.idServicio
	LEFT JOIN Servicio_Usa_Producto ON
	Servicio_Usa_Producto.idServicio = Servicio.idServicio
	LEFT JOIN Producto ON
	Producto.idProducto = Servicio_Usa_Producto.idProducto
	LEFT JOIN Lavador_Tiene_Producto ON
	Lavador_Tiene_Producto.idProducto = Producto.idProducto
	LEFT JOIN Lavador ON
	Lavador.idLavador = Lavador_Tiene_Producto.idLavador
	WHERE Lavador_Tiene_Producto.Cantidad >= Servicio_Usa_Producto.CantidadConsumida
	AND Lavador.idLavador = '%s'
	AND Servicio_Pedido.idServicioPedido = '%s'
	;";
	const QUERY_UPDATE_SERVICE_ACCEPTED = "UPDATE Servicio_Pedido SET idStatus = 2, idLavador = '%s'
	WHERE idServicioPedido = '%s';
	";
	const QUERY_UPDATE_START_TIME = "UPDATE Servicio_Pedido SET FechaEmpezado = '%s' WHERE idServicioPedido = '%s';";
	const QUERY_UPDATE_SERVICE = "UPDATE Servicio_Pedido SET idStatus = '%s' WHERE idServicioPedido = '%s';";
  const QUERY_READ_SERVICE =
		"SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador,
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Cliente.Nombre AS nombreCliente, Cliente.Celular AS celCliente  
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		WHERE Servicio_Pedido.idServicioPedido = '%s'
		;";
		
		const QUERY_READ_ACTIVE_SERVICE_FOR_USER =
		"SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador,
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio, Servicio_Pedido.idServicioPedido AS id,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Cliente.Nombre AS nombreCliente, Cliente.Celular AS celCliente  
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Sesion_Cliente ON
		Sesion_Cliente.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		LEFT JOIN Sesion_Lavador ON
		Sesion_Lavador.idLavador = Lavador.idLavador
		WHERE Sesion_Cliente.Token = '%s'
		AND Servicio_Pedido.idStatus != 5 
		AND Servicio_Pedido.idStatus != 6
		;";
		const QUERY_READ_ACTIVE_SERVICE_FOR_CLEANER =
		"SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador,
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio, Servicio_Pedido.idServicioPedido AS id,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Cliente.Nombre AS nombreCliente, Cliente.Celular AS celCliente  
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Sesion_Cliente ON
		Sesion_Cliente.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		LEFT JOIN Sesion_Lavador ON
		Sesion_Lavador.idLavador = Lavador.idLavador
		WHERE Sesion_Lavador.Token = '%s'
		AND Servicio_Pedido.idStatus != 5 
		AND Servicio_Pedido.idStatus != 6
		;";
		const QUERY_READ_CLEANERS_LOCATION = "SELECT Nombre,PrimerApellido,Latitud,Longitud,
		( 6371 * acos( cos( radians('%s') ) * cos( radians( Latitud ) ) * cos( radians( Longitud ) - radians('%s') ) +
		sin( radians('%s') ) * sin( radians( Latitud ) ) ) ) AS distance
		FROM Lavador HAVING distance < '%s'
		ORDER BY distance
		;";
		const QUERY_READ_SERVICES_LOCATION = "SELECT *,
		( 6371 * acos( cos( radians('%s') ) * cos( radians( Latitud ) ) * cos( radians( Longitud ) - radians('%s') ) +
		sin( radians('%s') ) * sin( radians( Latitud ) ) ) ) AS distance
		FROM Servicio_Pedido HAVING distance < '%s' 
		AND idLavador IS NULL
		ORDER BY distance
		;";
		const QUERY_UPDATE_CLEANER_PRODUCTS = "UPDATE Servicio_Pedido
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Servicio_Usa_Producto ON
		Servicio_Usa_Producto.idServicio = Servicio.idServicio
		LEFT JOIN Producto ON
		Producto.idProducto = Servicio_Usa_Producto.idProducto
		LEFT JOIN Lavador_Tiene_Producto ON
		Lavador_Tiene_Producto.idProducto = Producto.idProducto AND Lavador_Tiene_Producto.idLavador = Servicio_Pedido.idLavador
		SET Lavador_Tiene_Producto.Cantidad = Lavador_Tiene_Producto.Cantidad - Servicio_Usa_Producto.CantidadConsumida
		WHERE Servicio_Pedido.idServicioPedido = '%s'
		;";
		
		const QUERY_SELECT_SERVICES_FOR_USER = "SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, 
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Servicio_Pedido.FechaEmpezado AS fechaEmpezado
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		WHERE Cliente.idCliente = '%s'
		AND fechaEmpezado IS NOT NULL
		ORDER BY fechaEmpezado DESC
		;";
		
		const QUERY_SELECT_SERVICES_FOR_CLEANER = "SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, 
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Servicio_Pedido.FechaEmpezado AS fechaEmpezado
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		WHERE Lavador.idLavador = '%s'
		AND fechaEmpezado IS NOT NULL
		ORDER BY fechaEmpezado DESC
		;";
		
		const QUERY_UPDATE_SERVICE_REVIEW = "UPDATE Servicio_Pedido SET Calificacion = '%d' WHERE idServicioPedido = '%d';";
		
		const QUERY_READ_CLEANER_REVIEWS = "SELECT ROUND(AVG(Calificacion),1) AS Calificacion FROM Servicio_Pedido WHERE idLavador = '%d';";
		
	var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
  }
	
	public function readCleanersLocation($pointLatitud, $pointLongitud, $distance)
	{
		$query = sprintf(DataBaseService::QUERY_READ_CLEANERS_LOCATION,$pointLatitud, $pointLongitud, $pointLatitud, $distance);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		
    return $result;
	}
	
	public function readReviewForCleaner($cleanerId)
	{
		$query = sprintf(DataBaseService::QUERY_READ_CLEANER_REVIEWS,$cleanerId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		
    return $result;
	}
	
	public function readServicesLocation($pointLatitud, $pointLongitud, $distance)
	{
		$query = sprintf(DataBaseService::QUERY_READ_SERVICES_LOCATION,$pointLatitud, $pointLongitud, $pointLatitud, $distance);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		
    return $result;
	}
  
	public function insertService($fecha,$direccion, $latitud,$longitud,$idServicio,$idCliente,$idTipoServicio,$idCoche)
	{
		$query = sprintf(DataBaseService::QUERY_INSERT_SERVICE,$fecha,$direccion,$latitud,$longitud,$this->calculatePrice($idCoche, $idTipoServicio, $idServicio),$idServicio,$idCliente,$idTipoServicio,$idCoche);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		return $this->mysqli->insert_id;
	}
	
	
	public function calculatePrice($idCoche, $idTipoServicio, $idServicio)
	{
		$query = sprintf(DataBaseService::QUERY_READ_MULTIPLIERS_FOR_CAR,$idCoche);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new errorWithDatabaseException("Price could not be calculated");
		
		$priceForCar = $result->fetch_assoc();
		$query = sprintf(DataBaseService::QUERY_READ_PRICE_FOR_SERVICE,$idServicio);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new errorWithDatabaseException("Price could not be calculated");
		
		$priceForService = $result->fetch_assoc();
		
		$query = sprintf(DataBaseService::QUERY_READ_PRICE_FOR_SERVICE_TYPE,$idTipoServicio);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new errorWithDatabaseException("Price could not be calculated");
		
		$priceForServiceType = $result->fetch_assoc();
		
    return $priceForCar['precio'] * $priceForService['precio'] * $priceForServiceType['precio'];
	}
	
  public function readAllServices()
  {
    $query = sprintf(DataBaseService::QUERY_READ_ALL_SERVICES);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
    return $result;
  }
	
	public function readAllServicesType()
	{
		$query = sprintf(DataBaseService::QUERY_READ_ALL_SERVICES_TYPE);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
    return $result;
	}
  
  public function readService($serviceId)
  {
    $query = sprintf(DataBaseService::QUERY_READ_SERVICE,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new serviceNotFoundException("Service not found");
		
    return $result;
  }
	
	public function readActiveServiceForUser($token)
  {
    $query = sprintf(DataBaseService::QUERY_READ_ACTIVE_SERVICE_FOR_USER,$token);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
    return $result;
  }
	
	public function readActiveServiceForCleaner($token)
  {
    $query = sprintf(DataBaseService::QUERY_READ_ACTIVE_SERVICE_FOR_CLEANER,$token);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
    return $result;
  }
	
	public function readServicesHistoryForUser($clientId)
  {
    $query = sprintf(DataBaseService::QUERY_SELECT_SERVICES_FOR_USER,$clientId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
    return $result;
  }
	
	public function readServicesHistoryForCleaner($clientId)
  {
    $query = sprintf(DataBaseService::QUERY_SELECT_SERVICES_FOR_CLEANER,$clientId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
    return $result;
  }
	
	public function updateStartTimeService($serviceId, $fecha)
	{
		$query = sprintf(DataBaseService::QUERY_UPDATE_START_TIME,$fecha,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	
	public function updateReview($serviceId,$rating){
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE_REVIEW,$rating,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	
	public function updateService($serviceId, $statusId)
	{
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE,$statusId,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	
	public function updateServiceAccepted($serviceId,$cleanerId)
	{
		$this->checkIfServiceCanBeAccepted($serviceId,$cleanerId);
		
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE_ACCEPTED,$cleanerId,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	public function checkIfServiceCanBeAccepted($serviceId,$cleanerId)
	{
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_OPEN,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new serviceTakenException("Service taken");
		
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_AVAILABLE_PRODUCTS, $cleanerId, $serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new insufficientProductException("Not Enough Products");
	}
	
	public function removeCleanerProducts($serviceId)
	{
		$query = sprintf(DataBaseService::QUERY_UPDATE_CLEANER_PRODUCTS,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	
	public function readProductsForService($serviceId,$cleanerId)
	{
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_OPEN,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new serviceTakenException("Service taken");
		
		$query = sprintf(DataBaseService::QUERY_UPDATE_SERVICE_ACCEPTED,$cleanerId,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
  
}
class errorWithDatabaseException extends Exception{
	}
class sessionNotFoundException extends Exception{
	}
class serviceNotFoundException extends Exception{
	}
class serviceTakenException extends Exception {
	}
class insufficientProductException extends Exception {
}

?>