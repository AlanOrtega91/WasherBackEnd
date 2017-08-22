<?php
abstract class DataBase {
	
 	const DB_LINK = '127.0.0.1';
 	const DB_LOGIN = 'washerDBus4r';
 	const DB_PASSWORD ='lk_je9023U23daerD';
 	const DB_NAME = 'washer_dev';
 	
 	var $mysqli;
 	
 	function __construct()
 	{
 		$this->mysqli = new mysqli(self::DB_LINK, self::DB_LOGIN, self::DB_PASSWORD, self::DB_NAME);
 		if ($this->mysqli->connect_errno) {
 			throw new errorWithDatabaseException("Error connecting with database");
 		}
 		$this->mysqli->set_charset("utf8");
 	}
 	
 	function ejecutarQuery($query)
 	{
 		if(! ($resultado = $this->mysqli->query($query))) {
 			throw new errorWithDatabaseException($query.' ---- Query failed '.$this->mysqli->error);
 		}
 		return $resultado;
 	}
 	
 	function ejecutarQuerySinContraints($query)
 	{
 		$this->mysqli->query("SET foreign_key_checks = 0;");
 		if(! ($resultado = $this->mysqli->query($query))) {
 			throw new errorWithDatabaseException($query.' ---- Query failed '.$this->mysqli->error);
 		}
 		$this->mysqli->query("SET foreign_key_checks = 1;");
 		return $resultado;
 	}
 	
 	function resultadoTieneValores($resultado) {
 		if ($resultado->num_rows > 0)
 		{
 			return true;
 		} else {
 			return false;
 		}
 	}
}

class carsNotFoundException extends Exception{
}
class errorWithDatabaseException extends Exception{
}
class sessionNotFoundException extends Exception{
}
class userNotFoundException extends Exception{
}
class cleanerNotFoundException extends Exception{
}
class cleanerHasNoProductsException extends Exception{
}
class serviceNotFoundException extends Exception{
}
class serviceTakenException extends Exception {
}
class insufficientProductException extends Exception {
}
class noSessionFoundException extends Exception {
}
?>