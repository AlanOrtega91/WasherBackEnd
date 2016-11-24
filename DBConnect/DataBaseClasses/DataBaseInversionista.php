<?php
require_once dirname ( __FILE__ ) . "/DataBase.php";

class DataBaseInvestor {
	const QUERY_INSERT_USER = "INSERT INTO Inversionista (Nombre, PrimerApellido, Email, Password) VALUES ('%s', '%s', '%s', SHA2(MD5(('%s')),512));";
	const QUERY_READ_USER = "SELECT * FROM Inversionista WHERE Email = '%s' AND Password = SHA2(MD5(('%s')),512);";
	const QUERY_DELETE_SESSION = "DELETE FROM Sesion_Inversionista WHERE idInversionista = '%s';";
	const QUERY_INSERT_SESSION = "INSERT INTO Sesion_Inversionista (Token, idInversionista) VALUES('%s', '%s');";
	const QUERY_READ_USER_INFO = "SELECT Inversionista.idInversionista, Email, Nombre, PrimerApellido, Token
	FROM Inversionista
	LEFT JOIN Sesion_Inversionista ON
	Inversionista.idInversionista = Sesion_Inversionista.idInversionista
	WHERE Token = '%s';";
	const QUERY_GET_USER_ID = "SELECT idInversionista FROM Inversionista WHERE Email = '%s';";
	const QUERY_READ_CLEANERS = "SELECT idLavador, Lavador.Nombre, Lavador.PrimerApellido, Lavador.Email, Lavador.Telefono, Lavador.Latitud, Lavador.Longitud
			FROM Sesion_Inversionista LEFT JOIN Inversionista 
			ON Sesion_Inversionista.idInversionista = Inversionista.idInversionista
			LEFT JOIN Lavador 
			ON Inversionista.idInversionista = Lavador.idManager
			WHERE Token = '%s'
		;";
	const QUERY_READ_ALL_SERVICES = "SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		LEFT JOIN Inversionista ON
		Lavador.idManager = Inversionista.idInversionista
		LEFT JOIN Sesion_Inversionista ON
		Inversionista.idInversionista = Sesion_Inversionista.idInversionista 
		WHERE Token = '%s'
		AND Servicio_Pedido.idServicioPedido IS NOT NULL
		ORDER BY fechaEmpezado DESC
		;";
	const QUERY_READ_ALL_SERVICES_FILTER_DATE = "SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		LEFT JOIN Inversionista ON
		Lavador.idManager = Inversionista.idInversionista
		LEFT JOIN Sesion_Inversionista ON
		Inversionista.idInversionista = Sesion_Inversionista.idInversionista 
		WHERE Token = '%s'
		AND Servicio_Pedido.idServicioPedido IS NOT NULL
		AND DAYOFMONTH(Servicio_Pedido.fechaEmpezado) = DAYOFMONTH('%s')
		AND MONTH(Servicio_Pedido.fechaEmpezado) = MONTH('%s')
		AND YEAR(Servicio_Pedido.fechaEmpezado) = YEAR('%s')
		ORDER BY fechaEmpezado DESC
		;";
	const QUERY_READ_ALL_SERVICES_FILTER_ALL = "SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		LEFT JOIN Inversionista ON
		Lavador.idManager = Inversionista.idInversionista
		LEFT JOIN Sesion_Inversionista ON
		Inversionista.idInversionista = Sesion_Inversionista.idInversionista 
		WHERE Token = '%s'
		AND Servicio_Pedido.idServicioPedido IS NOT NULL
		AND DAYOFMONTH(Servicio_Pedido.fechaEmpezado) = DAYOFMONTH('%s')
		AND MONTH(Servicio_Pedido.fechaEmpezado) = MONTH('%s')
		AND YEAR(Servicio_Pedido.fechaEmpezado) = YEAR('%s')
		AND Lavador.idLavador = '%s'
		ORDER BY fechaEmpezado DESC
		;";
	const QUERY_READ_ALL_SERVICES_FILTER_CLEANER = "SELECT Servicio_Pedido.idServicioPedido AS id, Status.Status as status, Lavador.Nombre AS nombreLavador, 
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
		LEFT JOIN Tiempo_Servicio ON
		Vehiculo.idVehiculo = Tiempo_Servicio.idVehiculo AND Tiempo_Servicio.idServicio = Servicio.idServicio
		LEFT JOIN Lavador ON
		Servicio_Pedido.idLavador = Lavador.idLavador
		LEFT JOIN Inversionista ON
		Lavador.idManager = Inversionista.idInversionista
		LEFT JOIN Sesion_Inversionista ON
		Inversionista.idInversionista = Sesion_Inversionista.idInversionista 
		WHERE Token = '%s'
		AND Servicio_Pedido.idServicioPedido IS NOT NULL
		AND Lavador.idLavador = '%s'
		ORDER BY fechaEmpezado DESC
		;";
	
	const QUERY_UPDATE_USER = "UPDATE Cliente SET Nombre = '%s', PrimerApellido = '%s', Telefono = '%s', Email = '%s',
			NombreFactura = '%s', RFC = '%s', DireccionFactura = '%s' 
			WHERE idCliente = '%s'";
	const QUERY_UPDATE_PASSWORD = "UPDATE Cliente SET Password = SHA2(MD5(('%s')),512) WHERE Email = '%s'";
	
	var $mysqli;
	public function __construct() {
		$this->mysqli = new mysqli ( DataBase::DB_LINK, DataBase::DB_LOGIN, DataBase::DB_PASSWORD, DataBase::DB_NAME );
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException ( "Error connecting with database" );
		$this->mysqli->set_charset("utf8");
	}
	public function insertNewUser($name, $lastName, $email, $password ) {
		$query = sprintf ( DataBaseInvestor::QUERY_INSERT_USER, $name, $lastName, $email, $password );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( "Could not create new user " . $this->mysqli->error );
	}
	
	public function readUser($email, $password) {
		$query = sprintf ( DataBaseInvestor::QUERY_READ_USER, $email, $password );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		$rows = $result->fetch_assoc ();
		if ($result->num_rows === 0)
			throw new userNotFoundException ( "User not found" );
		return $result;
	}
	public function readCleaners($token) {
		$query = sprintf ( DataBaseInvestor::QUERY_READ_CLEANERS, $token );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		
		return $result;
	}
	public function readUserInfo($token) {
		$query = sprintf ( DataBaseInvestor::QUERY_READ_USER_INFO, $token );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		$rows = $result->fetch_assoc ();
		if ($result->num_rows === 0)
			throw new userNotFoundException ( "User not found" );
		return $rows;
	}
	public function insertSession($email) {
		$token = md5 ( uniqid ( mt_rand (), true ) );
		$query = sprintf ( DataBaseInvestor::QUERY_INSERT_SESSION, $token, $this->getUserId ( $email ) );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( 'Query failed: ' . $this->mysqli->error );
		return $token;
	}
	function getUserId($email) {
		$query = sprintf ( DataBaseInvestor::QUERY_GET_USER_ID, $email );
		if (! $result = $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( 'Query failed: ' . $this->mysqli->error );
		$line = $result->fetch_assoc ();
		return $line ['idInversionista'];
	}
	public function getToken($email) {
		$query = sprintf ( DataBaseInvestor::QUERY_GET_SESSION, $email );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed' );
		if (mysql_num_rows ( $result ) == 0)
			throw new sessionNotFoundException ();
		
		return $result;
	}
	public function deleteSession($email) {
		$query = sprintf ( DataBaseInvestor::QUERY_DELETE_SESSION, $this->getUserId ( $email ) );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( "ERROR" );
	}
	function readAllServices($token){
		$query = sprintf ( DataBaseInvestor::QUERY_READ_ALL_SERVICES, $token );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		return $result;
	}
	function readAllServicesFilterDate($token, $date){
		$query = sprintf ( DataBaseInvestor::QUERY_READ_ALL_SERVICES_FILTER_DATE, $token, $date, $date, $date );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		return $result;
	}
	function readAllServicesFilterCleaner($token, $idCleaner){
		$query = sprintf ( DataBaseInvestor::QUERY_READ_ALL_SERVICES_FILTER_CLEANER, $token, $idCleaner );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		return $result;
	}
	function readAllServicesFilterAll($token,$date,$idCleaner){
		$query = sprintf ( DataBaseInvestor::QUERY_READ_ALL_SERVICES_FILTER_ALL, $token, $date, $date, $date, $idCleaner );
		if (! ($result = $this->mysqli->query ( $query )))
			throw new errorWithDatabaseException ( 'Query failed = ' . mysql_error () );
		return $result;
	}
	public function updatePassword($email, $password) {
		$query = sprintf ( DataBaseInvestor::QUERY_UPDATE_PASSWORD, $password, $email );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( "ERROR" );
	}
	public function updateUser($idClient, $newName, $newLastName, $newPhone, $newEmail, $newBillingName, $newRFC, $newBillingAddress) {
		$query = sprintf ( DataBaseInvestor::QUERY_UPDATE_USER, $newName, $newLastName, $newPhone, $newEmail, $newBillingName, $newRFC, $newBillingAddress, $idClient );
		if (! $this->mysqli->query ( $query ))
			throw new errorWithDatabaseException ( "ERROR" );
	}
}
?>