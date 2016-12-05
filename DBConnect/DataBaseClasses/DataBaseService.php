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
	idCliente, idTipoServicio, idVehiculo, idVehiculoFavorito)
	VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
	;";
	const QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_OPEN = "SELECT * FROM Servicio_Pedido
	WHERE idServicioPedido = '%s' AND idLavador IS NULL AND idStatus != 6 
	;";
	const QUERY_UPDATE_SERVICE_ACCEPTED_CHECK_AVAILABLE_PRODUCTS = "SELECT * FROM Servicio_Pedido
	LEFT JOIN Servicio ON
	Servicio.idServicio = Servicio_Pedido.idServicio
	LEFT JOIN Vehiculo ON
	Vehiculo.idVehiculo = Servicio_Pedido.idVehiculo
	LEFT JOIN Vehiculo_Usa_Producto ON
	Vehiculo_Usa_Producto.idVehiculo = Vehiculo.idVehiculo 
	LEFT JOIN Producto ON
	Producto.idProducto = Vehiculo_Usa_Producto.idProducto
	LEFT JOIN Lavador_Tiene_Producto ON
	Lavador_Tiene_Producto.idProducto = Producto.idProducto
	LEFT JOIN Lavador ON
	Lavador.idLavador = Lavador_Tiene_Producto.idLavador
	WHERE (Lavador_Tiene_Producto.Cantidad - Vehiculo_Usa_Producto.CantidadConsumida) < 0
 	AND Vehiculo_Usa_Producto.CantidadConsumida > 0 
	AND Lavador.idLavador = '%s'
	AND Servicio_Pedido.idServicioPedido = '%s'
	;";
	const QUERY_UPDATE_SERVICE_ACCEPTED = "UPDATE Servicio_Pedido SET idLavador = '%s'
	WHERE idServicioPedido = '%s';
	";
	const QUERY_UPDATE_START_TIME = "UPDATE Servicio_Pedido SET FechaEmpezado = '%s' WHERE idServicioPedido = '%s';";
	const QUERY_UPDATE_ACCEPT_TIME = "UPDATE Servicio_Pedido SET FechaAceptado = '%s' WHERE idServicioPedido = '%s';";
	const QUERY_UPDATE_SERVICE = "UPDATE Servicio_Pedido SET idStatus = '%s' WHERE idServicioPedido = '%s';";
  const QUERY_READ_SERVICE =
		"SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio,Lavador.idLavador AS idLavador, 
  		Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador, 
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud, 
  		Tiempo_Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio.Descripcion AS descripcion, Servicio_Pedido.FechaEmpezado AS fechaEmpezado, 
  		Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Servicio_Pedido.Calificacion AS Calificacion, Cliente.Nombre AS nombreCliente, Cliente.Telefono AS telCliente, 
  		Servicio_Pedido.idTransaccion AS idTransaccion, Cliente.idCliente AS idCliente, Servicio_Pedido.fechaAceptado AS fechaAceptado,
  		Vehiculos_Favoritos.Color AS Color, Vehiculos_Favoritos.Placas AS Placas, Vehiculos_Favoritos.Marca AS Marca,
  		Cliente.ConektaId
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
  		LEFT JOIN Vehiculos_Favoritos ON
  		Vehiculos_Favoritos.idVehiculoFavorito = Servicio_Pedido.idVehiculoFavorito
		WHERE Servicio_Pedido.idServicioPedido = '%s'
		;";
		
		const QUERY_READ_ACTIVE_SERVICE_FOR_CLEANER =
		"SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, 
				Lavador.Longitud AS longitudLavador,
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio, Servicio_Pedido.idServicioPedido AS id,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Tiempo_Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Cliente.Nombre AS nombreCliente, Cliente.Telefono AS telCliente, Tipo_Servicio.TipoServicio AS Tipo,    
		Vehiculos_Favoritos.Color AS Color, Vehiculos_Favoritos.Placas AS Placas, Vehiculos_Favoritos.Marca AS Marca
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Vehiculos_Favoritos ON
		Vehiculos_Favoritos.idVehiculoFavorito = Servicio_Pedido.idVehiculoFavorito
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		WHERE Sesion_Lavador.Token = '%s'
		AND Servicio_Pedido.idStatus != 5 
		AND Servicio_Pedido.idStatus != 6
		;";
		const QUERY_READ_CLEANERS_LOCATION = "SELECT idLavador, Nombre, PrimerApellido, Latitud, Longitud,
		( 6371 * acos( cos( radians('%s') ) * cos( radians( Latitud ) ) * cos( radians( Longitud ) - radians('%s') ) +
		sin( radians('%s') ) * sin( radians( Latitud ) ) ) ) AS distance
		FROM Lavador 
		WHERE idLavador NOT IN 
				(
				SELECT idLavador FROM Servicio_Pedido WHERE idStatus != 5 AND idStatus != 6 AND idLavador IS NOT NULL
				)
		HAVING distance < '%s'
		ORDER BY distance
		;";
		const QUERY_READ_SERVICES_LOCATION = "SELECT *,
		( 6371 * acos( cos( radians('%s') ) * cos( radians( Latitud ) ) * cos( radians( Longitud ) - radians('%s') ) +
		sin( radians('%s') ) * sin( radians( Latitud ) ) ) ) AS distance
		FROM Servicio_Pedido HAVING distance < '%s' 
		AND idLavador IS NULL 
		AND Servicio_Pedido.idStatus != 6 
		ORDER BY distance
		;";
		const QUERY_UPDATE_CLEANER_PRODUCTS = "UPDATE Servicio_Pedido
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Vehiculo_Usa_Producto ON
		Vehiculo_Usa_Producto.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Producto ON
		Producto.idProducto = Vehiculo_Usa_Producto.idProducto
		LEFT JOIN Lavador_Tiene_Producto ON
		Lavador_Tiene_Producto.idProducto = Producto.idProducto AND Lavador_Tiene_Producto.idLavador = Servicio_Pedido.idLavador
		SET Lavador_Tiene_Producto.Cantidad = Lavador_Tiene_Producto.Cantidad - Vehiculo_Usa_Producto.CantidadConsumida
		WHERE Servicio_Pedido.idServicioPedido = '%s'
		;";
		
		const QUERY_READ_ACTIVE_SERVICE_FOR_USER =
		"SELECT Status.Status as status, Lavador.Nombre AS nombreLavador, Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador,
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio, Servicio_Pedido.idServicioPedido AS id,
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud,
		Servicio.Descripcion AS descripcion, Tiempo_Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Cliente.Nombre AS nombreCliente, Cliente.Telefono AS telCliente, Servicio_Pedido.idTransaccion AS idTransaccion, Tipo_Servicio.TipoServicio AS Tipo,
		Vehiculos_Favoritos.Color AS Color, Vehiculos_Favoritos.Placas AS Placas, Vehiculos_Favoritos.Marca AS Marca
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Vehiculos_Favoritos ON
		Vehiculos_Favoritos.idVehiculoFavorito = Servicio_Pedido.idVehiculoFavorito
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		WHERE Sesion_Cliente.Token = '%s'
		AND Servicio_Pedido.idStatus != 5 
		AND Servicio_Pedido.idStatus != 6
		;";
		
		const QUERY_SELECT_SERVICES_FOR_USER = "SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio,Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador, 
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud, Tiempo_Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio.Descripcion AS descripcion, Servicio_Pedido.FechaEmpezado AS fechaEmpezado, Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Servicio_Pedido.Calificacion AS Calificacion, Cliente.Nombre AS nombreCliente, Cliente.Telefono AS telCliente, 
		Servicio_Pedido.FechaAceptado AS fechaAceptado, Tipo_Servicio.TipoServicio AS Tipo,
		Vehiculos_Favoritos.Color AS Color, Vehiculos_Favoritos.Placas AS Placas, Vehiculos_Favoritos.Marca AS Marca
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Vehiculos_Favoritos ON
		Vehiculos_Favoritos.idVehiculoFavorito = Servicio_Pedido.idVehiculoFavorito
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador 
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		WHERE Cliente.idCliente = '%s'
		AND Servicio_Pedido.idStatus != '6'
		ORDER BY fechaEmpezado DESC
		;";
		
		const QUERY_SELECT_SERVICES_FOR_CLEANER = "SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
		Vehiculo.Nombre AS coche, Servicio.Servicio AS servicio,Lavador.idLavador AS idLavador, Lavador.Latitud AS latitudLavador, Lavador.Longitud AS longitudLavador, 
		Servicio_Pedido.precio AS precio, Servicio_Pedido.Latitud AS latitud, Servicio_Pedido.Longitud AS longitud, Tiempo_Servicio.TiempoEstimado AS tiempoEstimado,
		Servicio.Descripcion AS descripcion, Servicio_Pedido.FechaEmpezado AS fechaEmpezado, Servicio_Pedido.FechaEmpezado + INTERVAL tiempoEstimado MINUTE AS horaFinalEstimada,
		Servicio_Pedido.Calificacion AS Calificacion, Cliente.Nombre AS nombreCliente, Cliente.Telefono AS telCliente, Tipo_Servicio.TipoServicio AS Tipo,
		Vehiculos_Favoritos.Color AS Color, Vehiculos_Favoritos.Placas AS Placas, Vehiculos_Favoritos.Marca AS Marca
		FROM Servicio_Pedido
		LEFT JOIN Status ON
		Servicio_Pedido.idStatus = Status.idStatus
		LEFT JOIN Vehiculo ON
		Servicio_Pedido.idVehiculo = Vehiculo.idVehiculo
		LEFT JOIN Vehiculos_Favoritos ON
		Vehiculos_Favoritos.idVehiculoFavorito = Servicio_Pedido.idVehiculoFavorito
		LEFT JOIN Servicio ON
		Servicio_Pedido.idServicio = Servicio.idServicio
		LEFT JOIN Tipo_Servicio ON
		Servicio_Pedido.idTipoServicio = Tipo_Servicio.idTipoServicio
		LEFT JOIN Cliente ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador 
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		WHERE Lavador.idLavador = '%s'
		AND Servicio_Pedido.idStatus != '6'
		ORDER BY fechaEmpezado DESC
		;";
		
		const QUERY_UPDATE_SERVICE_REVIEW = "UPDATE Servicio_Pedido SET Calificacion = '%d' WHERE idServicioPedido = '%d';";
		
		const QUERY_READ_CLEANER_REVIEWS = "SELECT ROUND(AVG(Calificacion),1) AS Calificacion FROM Servicio_Pedido WHERE idLavador = '%d';";
		const QUERY_UPDATE_TRANSACTION_ID = "UPDATE Servicio_Pedido SET idTransaccion = '%s'
		WHERE idServicioPedido = '%s';";
		const QUERY_READ_PUSH_NOTIFICATION_TOKEN = "
		SELECT Cliente.pushNotificationToken AS pushNotificationTokenCliente, Cliente.dispositivo AS clientDevice, 
				Lavador.pushNotificationToken AS pushNotificationTokenLavador, Lavador.dispositivo AS cleanerDevice
		From Cliente
		LEFT JOIN Servicio_Pedido ON
		Servicio_Pedido.idCliente = Cliente.idCliente
		LEFT JOIN Lavador ON
		Lavador.idLavador = Servicio_Pedido.idLavador 
		WHERE Servicio_Pedido.idServicioPedido = '%s'
		;";
		
		const QUERY_GET_USER_ID = "SELECT idCliente FROM Servicio_Pedido WHERE idServicioPedido = '%s';";
		const QUERY_GET_CLEANER_LOCATION = "SELECT idLavador, Latitud, Longitud FROM Lavador WHERE idLavador = '%s'";
		const QUERY_BLOCK_USER = "UPDATE Cliente SET block = 1 WHERE idCliente = '%s'";
	var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
		$this->mysqli->set_charset("utf8");
  }
		
		public function readCleanerLocation($cleanerId){
				$query = sprintf(DataBaseService::QUERY_GET_CLEANER_LOCATION,$cleanerId);
				if(!$result = $this->mysqli->query($query))
						throw new errorWithDatabaseException('Query failed: '. $this->mysqli->error);
				$line = $result->fetch_assoc();
				return $line;
		}
		
		function getUserId($idService)
	{
		$query = sprintf(DataBaseService::QUERY_GET_USER_ID,$idService);
		if(!$result = $this->mysqli->query($query))
			throw new errorWithDatabaseException('Query failed: '. $this->mysqli->error);
		$line = $result->fetch_assoc();
    return $line['idCliente'];
	}
		
		public function readPushNotificationToken($serviceId)
		{
				$query = sprintf(DataBaseService::QUERY_READ_PUSH_NOTIFICATION_TOKEN,$serviceId);
				if(!($result = $this->mysqli->query($query)))
					throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
				
				return $result;
		}
	
	public function updateTransactionId($serviceId,$transactionId){
		$query = sprintf(DataBaseService::QUERY_UPDATE_TRANSACTION_ID,$transactionId,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
	
	public function blockUser($idClient)
	{
		$query = sprintf(DataBaseService::QUERY_BLOCK_USER,$idClient);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		
			return $result;
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
  
	public function insertService($fecha,$direccion, $latitud,$longitud,$idServicio,$idCliente,$idTipoServicio,$idCoche, $idCocheFavorito)
	{
		$query = sprintf(DataBaseService::QUERY_INSERT_SERVICE,$fecha,$direccion,$latitud,$longitud,$this->calculatePrice($idCoche, $idTipoServicio, $idServicio),$idServicio,$idCliente,$idTipoServicio,$idCoche, $idCocheFavorito);
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
	public function updateAcceptTimeService ( $serviceId, $fecha ){
		$query = sprintf(DataBaseService::QUERY_UPDATE_ACCEPT_TIME,$fecha,$serviceId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
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
		
		if($result->num_rows != 0)
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
?>