<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseCleaner {
  
  const QUERY_READ_USER = "SELECT * FROM Lavador WHERE Email='%s' AND Password = MD5('%s');";
	const QUERY_READ_USER_INFO = "SELECT * FROM Lavador
	LEFT JOIN Sesion_Lavador ON
	Lavador.idLavador = Sesion_Lavador.idLavador
	WHERE Token = '%s';";
	const QUERY_GET_USER_ID = "SELECT idLavador FROM Lavador WHERE Email = '%s';";
	const QUERY_UPDATE_PASSWORD = "UPDATE Lavador SET Password = MD5('%s') WHERE Email = '%s';";
	const QUERY_UPDATE_LOCATION = "UPDATE Lavador SET Latitud = '%s', Longitud = '%s'
	WHERE idLavador = '%s'
	;";
  const QUERY_GET_SESSION = "SELECT Token FROM Sesion_Lavador LEFT JOIN Lavador WHERE Email='%s';";
  const QUERY_READ_SESSION = "SELECT * FROM Sesion_Lavador 
  		LEFT JOIN Lavador ON
  		Sesion_Lavador.idLavador = Lavador.idLavador
  		WHERE Token='%s';";
  const QUERY_INSERT_SESSION = "INSERT INTO Sesion_Lavador (Token, idLavador) VALUES('%s', '%s');";
  const QUERY_DELETE_SESSION = "DELETE FROM Sesion_Lavador WHERE idLavador = (SELECT idLavador FROM Lavador WHERE Email = '%s');";
	const QUERY_UPDATE_IMAGE = "UPDATE Lavador SET FotoURL = '%s' WHERE idLavador = '%s';";
	const QUERY_UPDATE_PNT_FOR_CLEANER = "UPDATE Lavador SET pushNotificationToken = '%s' WHERE idLavador = '%s';";
	const QUERY_DELETE_PNT_FOR_CLEANER = "UPDATE Lavador SET pushNotificationToken = '' WHERE Email = '%s';";
const QUERY_DELETE_PNT = "UPDATE Lavador SET pushNotificationToken = '' WHERE pushNotificationToken = '%s';";
const QUERY_NULL_LOCATION = "UPDATE Lavador SET Latitud = NULL, Longitud = NULL WHERE Email = '%s'";
	var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
  }
  
  public function deletePushNotification($email)
		{
				$query = sprintf(DataBaseCleaner::QUERY_DELETE_PNT_FOR_CLEANER,$email);
				if(!$this->mysqli->query($query))
						throw new errorWithDatabaseException("Could not create new user ".$this->mysqli->error);
		}
		
		public function updatePushNotificationToken($clientId,$token){
		$query = sprintf(DataBaseCleaner::QUERY_DELETE_PNT,$token);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("Could not create new user ".$this->mysqli->error);
		
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_PNT_FOR_CLEANER,$token,$clientId);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("Could not create new user ".$this->mysqli->error);
		
	}
	
	public function deleteLocation($email){
		$query = sprintf(DataBaseCleaner::QUERY_NULL_LOCATION,$email);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("Could not create new user ".$this->mysqli->error);
	}
	
  public function readUser($email,$password)
  {
    $query = sprintf(DataBaseCleaner::QUERY_READ_USER, $email, $password);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed = '. mysql_error());
		$rows = $result->fetch_assoc();
    if ($result->num_rows === 0 )
			throw new cleanerNotFoundException("Cleaner not found");
    return $result;
  }
  
  public function insertSession($email)
  {
		
    $token = md5(uniqid(mt_rand(), true));
				$query = sprintf(DataBaseCleaner::QUERY_INSERT_SESSION,$token, $this->getCleanerId($email));
				if(!$this->mysqli->query($query))
						throw new errorWithDatabaseException('Query failed: '. $this->mysqli->error);
	
    return $token;
  }
  
  public function getToken($email)
  {
    $query = sprintf(DataBaseCleaner::QUERY_GET_SESSION, $email);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException('Query failed = '. mysql_error());
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
    if (mysql_num_rows($result) == 0 )
			throw new sessionNotFoundException();
    
    return $result;
  }
	
	function getCleanerId($email)
	{
		$query = sprintf(DataBaseCleaner::QUERY_GET_USER_ID,$email);
		if(!$result = $this->mysqli->query($query))
			throw new errorWithDatabaseException('Query failed: '. $this->mysqli->error);
		$line = $result->fetch_assoc();
    return $line['idLavador'];
	}
  
  public function deleteSession($email)
  {
    $query = sprintf(DataBaseCleaner::QUERY_DELETE_SESSION, $email);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
  }
  
  public function readSession($token)
  {
    $query = sprintf(DataBaseCleaner::QUERY_READ_SESSION, $token);
		if(!$result = $this->mysqli->query($query))
			throw new errorWithDatabaseException();
		if ($result->num_rows === 0 )
			throw new noSessionFoundException("Cleaner not Found");
    return $result->fetch_assoc();
  }
	
	public function readCleanerInfo($token)
  {
    $query = sprintf(DataBaseCleaner::QUERY_READ_USER_INFO, $token);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed = '. mysql_error());
		$rows = $result->fetch_assoc();
    if ($result->num_rows === 0 )
			throw new cleanerNotFoundException("User not found");
    return $rows;
  }
	
	public function updateLocation($idCleaner, $latitud, $longitud)
	{
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_LOCATION, $latitud, $longitud, $idCleaner);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
	}
	
	public function updateImage($cleanerId, $imageName)
	{
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_IMAGE, $imageName, $cleanerId);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
	}
	
	public function updatePassword($email, $password)
	{
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_PASSWORD, $password, $email);
		if(!$this->mysqli->query($query))
			throw new errorWithDatabaseException("ERROR");
	}
}
?>