<?php
require_once dirname(__FILE__)."/DataBaseClasses/DataBase.php";

class SafeString
{
	public static function safe($string){
		$needles = array("INSERT","UPDATE","SELECT","DROP","SHOW","ALTER");
		foreach($needles as $needle)
			if(stristr($string,$needle))
				throw invalidStringFoundException("Intento de inyeccion sql detectada");
			
		return mysqli_real_escape_string(SafeString::getDataBaseLink(),$string);;
	}
  
  public static function getDataBaseLink()
  {
		$mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
		
		return $mysqli;
  }
}

class invalidStringFoundException extends Exception{
		}
?>