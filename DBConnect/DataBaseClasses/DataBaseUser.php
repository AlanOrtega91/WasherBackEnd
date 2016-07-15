<?php
require_once dirname(__FILE__)."/../DataBaseClasses/DataBase.php";
class DataBaseUser {
  
  const QUERY_READ_USER = "SELECT * FROM Cliente WHERE Mail = '%s' AND Password = MD5('%s');";
	const QUERY_READ_USER_INFO = "SELECT * FROM Cliente
	LEFT JOIN Sesion_Cliente ON
	Cliente.idCliente = Sesion_Cliente.idCliente
	WHERE Token = '%s';";
	const QUERY_GET_USER_ID = "SELECT idCliente FROM Cliente WHERE Mail = '%s';";
	const QUERY_UPDATE_USER = "UPDATE Cliente SET Nombre = '%s', PrimerApellido = '%s', Celular = '%s', Mail = '%s',
	NombreFactura = '%s', RFC = '%s', DireccionFactura = '%s' WHERE idCliente = '%s'";
	const QUERY_UPDATE_PASSWORD = "UPDATE Cliente SET Password = MD5('%s') WHERE Mail = '%s'";
  const QUERY_GET_SESSION = "SELECT Token FROM Sesion_Cliente LEFT JOIN Cliente WHERE Mail='%s'";
  const QUERY_READ_SESSION = "SELECT * FROM Sesion_Cliente WHERE Token='%s';";
  const QUERY_INSERT_SESSION = "INSERT INTO Sesion_Cliente (Token, idCliente) VALUES('%s', '%s');";
  const QUERY_INSERT_USER = "INSERT INTO Cliente (Nombre, PrimerApellido, Mail, Password, Celular) VALUES ('%s', '%s', '%s', MD5('%s'), '%s');";
  const QUERY_DELETE_SESSION = "DELETE FROM Sesion_Cliente WHERE idCliente = '%s';";
	const QUERY_UPDATE_IMAGE = "UPDATE Cliente SET FotoURL = '%s' WHERE idCliente = '%s';";
	const QUERY_UPDATE_PNT_FOR_CLIENT = "UPDATE Cliente SET pushNotificationToken = '%s' WHERE idCliente = '%s';";
	var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
  }
  
  public function insertNewUser($name, $lastName, $mail, $password, $cel)
  {
    $query = sprintf(DataBaseUser::QUERY_INSERT_USER,$name, $lastName, $mail,$password, $cel);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("Could not create new user ".$this->mysqli->error);
  }
  
	public function updatePushNotificationToken($clientId,$token){
		$query = sprintf(DataBaseUser::QUERY_UPDATE_PNT_FOR_CLIENT,$token,$clientId);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("Could not create new user ".$this->mysqli->error);
	}
	
	
  public function readUser($mail,$password)
  {
    $query = sprintf(DataBaseUser::QUERY_READ_USER, $mail, $password);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed = '. mysql_error());
		$rows = $result->fetch_assoc();
    if ($result->num_rows === 0 )
			throw new userNotFoundException("User not found");
    return $result;
  }
	
	public function readUserInfo($token)
  {
    $query = sprintf(DataBaseUser::QUERY_READ_USER_INFO, $token);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed = '. mysql_error());
		$rows = $result->fetch_assoc();
    if ($result->num_rows === 0 )
			throw new userNotFoundException("User not found");
    return $rows;
  }
  
  public function insertSession($mail)
  {
    $token = md5(uniqid(mt_rand(), true));
		$query = sprintf(DataBaseUser::QUERY_INSERT_SESSION,$token, $this->getUserId($mail));
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException('Query failed: '. $this->mysqli->error);
    return $token;
  }
	
	function getUserId($mail)
	{
		$query = sprintf(DataBaseUser::QUERY_GET_USER_ID,$mail);
		if(!$result = $this->mysqli->query($query))
			throw new errorWithDatabaseException('Query failed: '. $this->mysqli->error);
		$line = $result->fetch_assoc();
    return $line['idCliente'];
	}
  
  public function getToken($mail)
  {
    $query = sprintf(DataBaseUser::QUERY_GET_SESSION, $mail);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException('Query failed = '. mysql_error());
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
    if (mysql_num_rows($result) == 0 )
			throw new sessionNotFoundException();
    
    return $result;
  }
	
  public function updateImage($userId, $imageName)
	{
		$query = sprintf(DataBaseUser::QUERY_UPDATE_IMAGE, $imageName, $userId);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
	}
	
  public function deleteSession($mail)
  {
    $query = sprintf(DataBaseUser::QUERY_DELETE_SESSION, $this->getUserId($mail));
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
  }
  
  public function readSession($token)
  {
    $query = sprintf(DataBaseUser::QUERY_READ_SESSION, $token);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new noSessionFoundException("No session found");
		
    return true;
  }
	
	public function updatePassword($mail, $password)
	{
		$query = sprintf(DataBaseUser::QUERY_UPDATE_PASSWORD, $password, $mail);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
	}
	
	public function updateUser($idClient, $newName, $newLastName, $newCel, $newMail, $newBillingName, $newRFC, $newBillingAddress)
	{
		$query = sprintf(DataBaseUser::QUERY_UPDATE_USER, $newName, $newLastName, $newCel, $newMail, $newBillingName, $newRFC, $newBillingAddress, $idClient);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
	}
}
?>