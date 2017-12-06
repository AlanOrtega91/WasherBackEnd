<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseCleaner extends DataBase{
  
  const QUERY_READ_USER = "SELECT * FROM Lavador WHERE Email='%s' AND Password = SHA2(MD5(('%s')),512);";
	const QUERY_READ_USER_INFO = "SELECT * FROM Lavador
	LEFT JOIN Sesion_Lavador ON
	Lavador.idLavador = Sesion_Lavador.idLavador
	WHERE Token = '%s';";
	const QUERY_GET_USER_ID = "SELECT idLavador FROM Lavador WHERE Email = '%s';";
	const QUERY_UPDATE_PASSWORD = "UPDATE Lavador SET Password = SHA2(MD5(('%s')),512) WHERE Email = '%s';";
	const QUERY_UPDATE_LOCATION = "UPDATE Lavador SET Latitud = '%s', Longitud = '%s', UbicacionActualizada = NOW()
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
const QUERY_UPDATE_DEVICE = "UPDATE Lavador SET Dispositivo = '%s' WHERE idLavador = '%s'";

  
  public function deletePushNotification($email)
		{
				$query = sprintf(DataBaseCleaner::QUERY_DELETE_PNT_FOR_CLEANER,$email);
				$this->ejecutarQuery($query);
		}
		
		public function updatePushNotificationToken($clientId,$token){
		$query = sprintf(DataBaseCleaner::QUERY_DELETE_PNT,$token);
		$this->ejecutarQuery($query);
		
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_PNT_FOR_CLEANER,$token,$clientId);
		$this->ejecutarQuery($query);
		
	}
	
	public function deleteLocation($email){
		$query = sprintf(DataBaseCleaner::QUERY_NULL_LOCATION,$email);
		$this->ejecutarQuery($query);
	}
	
  public function readUser($email,$password)
  {
    $query = sprintf(DataBaseCleaner::QUERY_READ_USER, $email, $password);
    $result = $this->ejecutarQuery($query);
	$rows = $result->fetch_assoc();
    if ($result->num_rows === 0 )
			throw new cleanerNotFoundException("Cleaner not found");
    return $result;
  }
  
  public function insertSession($email)
  {
		
    $token = md5(uniqid(mt_rand(), true));
				$query = sprintf(DataBaseCleaner::QUERY_INSERT_SESSION,$token, $this->getCleanerId($email));
				$this->ejecutarQuery($query);
	
    return $token;
  }
  
  public function getToken($email)
  {
    $query = sprintf(DataBaseCleaner::QUERY_GET_SESSION, $email);
    $result = $this->ejecutarQuery($query);
    if (!$this->resultadoTieneValores($result)) {
		throw new sessionNotFoundException();
    }
    
    return $result;
  }
	
	function getCleanerId($email)
	{
		$query = sprintf(DataBaseCleaner::QUERY_GET_USER_ID,$email);
		$result = $this->ejecutarQuery($query);
		$line = $result->fetch_assoc();
    return $line['idLavador'];
	}
  
  public function deleteSession($email)
  {
    $query = sprintf(DataBaseCleaner::QUERY_DELETE_SESSION, $email);
    $this->ejecutarQuery($query);
  }
  
  public function readSession($token)
  {
    $query = sprintf(DataBaseCleaner::QUERY_READ_SESSION, $token);
    $result = $this->ejecutarQuery($query);
    if (!$this->resultadoTieneValores($result)) {
		throw new noSessionFoundException("Cleaner not Found");
    }
    return $result->fetch_assoc();
  }
	
	public function readCleanerInfo($token)
  {
    $query = sprintf(DataBaseCleaner::QUERY_READ_USER_INFO, $token);
    $result = $this->ejecutarQuery($query);
		$rows = $result->fetch_assoc();
    if ($result->num_rows === 0 )
			throw new cleanerNotFoundException("User not found");
    return $rows;
  }
	
	public function updateLocation($idCleaner, $latitud, $longitud)
	{
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_LOCATION, $latitud, $longitud, $idCleaner);
		$this->ejecutarQuery($query);
	}
	
	public function updateImage($cleanerId, $imageName)
	{
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_IMAGE, $imageName, $cleanerId);
		$this->ejecutarQuery($query);
	}
	
	public function updatePassword($email, $password)
	{
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_PASSWORD, $password, $email);
		$this->ejecutarQuery($query);
	}
	
	public function updateDevice($userId,$device) {
		$query = sprintf(DataBaseCleaner::QUERY_UPDATE_DEVICE, $device, $userId);
		$this->ejecutarQuery($query);
	}
}
?>