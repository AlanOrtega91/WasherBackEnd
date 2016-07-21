<?php
require_once dirname(__FILE__)."/../DataBaseClasses/DataBaseCleaner.php";
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
		$this->sendLogOut($mail);
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

function savePushNotificationToken($cleanerId,$token){
		$this->dataBase->updatePushNotificationToken($cleanerId,$token);
	}

	function sendLogOut($mail)
	{
		$this->dataBase->deletePushNotification($mail);
		$this->dataBase->deleteLocation($mail);
		$this->dataBase->deleteSession($mail);
	}
	
	public function saveImage($cleanerId, $imageName){
		$this->dataBase->updateImage($cleanerId, $imageName);
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
	
	function userHasToken($token)
	{
		return $this->dataBase->readSession($token);
	}
}
?>