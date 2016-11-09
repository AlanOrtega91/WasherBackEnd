<?php
require_once dirname ( __FILE__ ) . "/DataBase.php";

class DataBaseInvestor {
	const QUERY_INSERT_USER = "INSERT INTO Inversionista (Nombre, PrimerApellido, Email, Password) VALUES ('%s', '%s', '%s', SHA2(MD5(('%s')),512));";
	const QUERY_READ_USER = "SELECT * FROM Inversionista WHERE Email = '%s' AND Password = SHA2(MD5(('%s')),512);";
	const QUERY_DELETE_SESSION = "DELETE FROM Sesion_Inversionista WHERE idInversionista = '%s';";
	const QUERY_INSERT_SESSION = "INSERT INTO Sesion_Inversionista (Token, idInversionista) VALUES('%s', '%s');";
	const QUERY_READ_USER_INFO = "SELECT * FROM Inversionista
	LEFT JOIN Sesion_Inversionista ON
	Inversionista.idInversionista = Sesion_Inversionista.idInversionista
	WHERE Token = '%s';";
	const QUERY_GET_USER_ID = "SELECT idInversionista FROM Inversionista WHERE Email = '%s';";
	const QUERY_READ_CLEANERS = "SELECT * 
			FROM Sesion_Inversionista LEFT JOIN Inversionista 
			ON Sesion_Inversionista.idInversionista = Inversionista.idInversionista
			LEFT JOIN Lavador 
			ON Inversionista.idInversionista = Lavador.idManager
			LEFT JOIN Servicio_Pedido
			ON Lavador.idLavador = Servicio_Pedido.idLavador
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
			WHERE Token = '%s'
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
	public function readAllServices($token) {
		$query = sprintf ( DataBaseInvestor::QUERY_READ_CLEANERS, $token );
		echo $query;
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