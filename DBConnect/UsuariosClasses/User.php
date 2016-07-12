<?php
require_once dirname(__FILE__)."/../DataBaseClasses/DataBaseUser.php";
class User {

private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseUser();
	}
	
	function readUserData($token)
	{
		return $this->dataBase->readUserInfo($token);
	}
	
	function savePushNotificationToken($clientId,$token){
		$this->dataBase->updatePushNotificationToken($clientId,$token);
	}
	
  function sendLogIn($mail,$password)
	{
		$this->dataBase->readUser($mail, $password);	
    $token = $this->createSession($mail);
		
		return $this->dataBase->readUserInfo($token);
	}
	
	function createSession($mail)
	{
		$token = $this->dataBase->insertSession($mail);
		return $token;
	}
	
	function addUser($name, $lastName, $mail, $password, $cel)
	{
		$this->dataBase->insertNewUser($name, $lastName, $mail, $password, $cel);
	}

	function sendLogOut($mail)
	{
		$this->dataBase->deleteSession($mail);
	}
	
	public function saveImage($userId, $imageName){
		$this->dataBase->updateImage($userId, $imageName);
	}
	
	function changePassword($mail, $newPassword, $oldPassword)
	{
		$this->dataBase->readUser($mail, $oldPassword);
		$this->dataBase->updatePassword($mail,$newPassword);
	}
	
	function changeData($newName, $newLastName, $newCel, $newMail, $oldMail)
	{
		$this->dataBase->updateUser($newName, $newLastName, $newCel, $newMail, $oldMail);
	}
	
	private function userHasToken($token)
	{
		try{
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