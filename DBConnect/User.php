<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseUser.php";
require_once dirname ( __FILE__ ) . "/Payment.php";

class User {
	private $dataBase;
	
	public function __construct() {
		$this->dataBase = new DataBaseUser ();
	}
	function readUserData($token) {
		return $this->dataBase->readUserInfo ( $token );
	}
	function savePushNotificationToken($clientId, $token) {
		$this->dataBase->updatePushNotificationToken ( $clientId, $token );
	}
	function sendLogIn($email, $password) {
		$this->sendLogOut ( $email );
		$this->dataBase->readUser ( $email, $password );
		$token = $this->createSession ( $email );
		
		return $this->dataBase->readUserInfo ( $token );
	}
	function createSession($email) {
		$token = $this->dataBase->insertSession ( $email );
		return $token;
	}
	function addUser($name, $lastName, $email, $password, $cel) {
		$this->dataBase->insertNewUser ( $name, $lastName, $email, $password, $cel );
		return $this->sendLogIn ( $email, $password );
	}
	function sendLogOut($email) {
		$this->dataBase->deletePushNotification ( $email );
		$this->dataBase->deleteSession ( $email );
	}
	public function saveImage($userId, $imageName) {
		$this->dataBase->updateImage ( $userId, $imageName );
	}
	function changePassword($email, $newPassword, $oldPassword) {
		$this->dataBase->readUser ( $email, $oldPassword );
		$this->dataBase->updatePassword ( $email, $newPassword );
	}
	function changeData($idClient, $newName, $newLastName, $newCel, $newEmail, $newBillingName, $newRFC, $newBillingAddress) {
		$this->dataBase->updateUser ( $idClient, $newName, $newLastName, $newCel, $newEmail, $newBillingName, $newRFC, $newBillingAddress );
	}
	function userHasToken($token) {
		return $this->dataBase->readSession ( $token );
	}
}
?>