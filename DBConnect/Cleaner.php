<?php
require_once dirname(__FILE__)."/DataBaseClasses/DataBaseCleaner.php";
class Cleaner {
	/*
   * Todo lo comentado esta en espera de que el cliente diga el funcionamiento de nuevos lavadores
   */
	private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseCleaner();
	}
	
  function sendLogIn($email,$password) {
		$this->dataBase->readUser($email, $password);
		$this->sendLogOut($email);
		$token = $this->createSession($email);
		return $this->dataBase->readCleanerInfo($token);
	}
	
	function readCleanerData($token)
	{
		return $this->dataBase->readCleanerInfo($token);
	}
	
	function createSession($email)
	{
		$token = $this->dataBase->insertSession($email);
		return $token;
	}
	
	function updateLocation($idCleaner, $latitud, $longitud)
	{
		$this->dataBase->updateLocation($idCleaner, $latitud, $longitud);
	}

	function savePushNotificationToken($cleanerId,$token){
		$this->dataBase->updatePushNotificationToken($cleanerId,$token);
	}

	function sendLogOut($email)
	{
		$this->dataBase->deletePushNotification($email);
		$this->dataBase->deleteLocation($email);
		$this->dataBase->deleteSession($email);
	}
	
	public function saveImage($cleanerId, $imageName){
		$this->dataBase->updateImage($cleanerId, $imageName);
	}
	
	function changePassword($email, $newPassword, $oldPassword)
	{
		$this->dataBase->readUser($email, $oldPassword);
		$this->dataBase->updatePassword($email,$newPassword);
	}
	
	function userHasToken($token)
	{
		return $this->dataBase->readSession($token);
	}
	function saveDevice($cleanerId,$device) {
		$this->dataBase->updateDevice($cleanerId,$device);
	}
}
?>