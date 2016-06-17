<?php
include dirname(__FILE__)."/../DataBaseClasses/DataBaseCleaner.php";
class Cleaner {
	/*
   * Todo lo comentado esta en espera de que el cliente diga el funcionamiento de nuevos lavadores
   */
	private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseCleaner();
	}
	
  function sendLogIn($mail,$password)
	{
		$this->dataBase->readUser($mail, $password);
		$token = $this->createSession($mail);
		return $this->dataBase->readCleanerInfo($token);
	}
	
	function readCleanerData($token)
	{
		return $this->dataBase->readCleanerInfo($token);
	}
	
	function createSession($mail)
	{
		$token = $this->dataBase->insertSession($mail);
		return $token;
	}
	
	function updateLocation($idCleaner, $latitud, $longitud)
	{
		$this->dataBase->updateLocation($idCleaner, $latitud, $longitud);
	}
/*private function addUser($name, ,$lastName, $mail, $password)
	{
		$dataBase->insertNewUser($name, $lastName, $mail, $password);
		return createSession();
	}*/
	function sendLogOut($mail)
	{
		$this->dataBase->deleteSession($mail);
	}
	
	function changePassword($mail, $newPassword, $oldPassword)
	{
		$this->dataBase->readUser($mail, $oldPassword);
		$this->dataBase->updatePassword($mail,$newPassword);
	}
	
	/*function changeData($newName, $newLastName, $newMail, $oldMail)
	{
		$this->dataBase->updateUser($newName, $newLastName, $newMail, $oldMail)
	}*/
	
	private function userHasToken($token)
	{
		try
		{
			return $this->dataBase->readSession($token);
		} catch (sessionNotFoundException $e)
		{
			return false;
		}
	}
}

class queryNotFoundException extends Exception{
		}
?>