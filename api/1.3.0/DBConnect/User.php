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
	function saveConektaId($userId, $conektaId) {
		$this->dataBase->updateConektaId($userId, $conektaId);
	}
	function sendLogIn($email, $password) {
		$this->dataBase->readUser ( $email, $password );
		$this->sendLogOut ( $email );
		$token = $this->createSession ( $email );
		
		return $this->dataBase->readUserInfo ( $token );
	}
	function createSession($email) {
		$token = $this->dataBase->insertSession ( $email );
		return $token;
	}
	function addUser($name, $lastName, $email, $password, $phone) {
		$this->dataBase->insertNewUser ( $name, $lastName, $email, $password, $phone );
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
	function changeData($idClient, $newName, $newLastName, $newPhone, $newEmail, $newBillingName, $newRFC, $newBillingAddress) {
		$this->dataBase->updateUser ( $idClient, $newName, $newLastName, $newPhone, $newEmail, $newBillingName, $newRFC, $newBillingAddress );
	}
	function userHasToken($token) {
		return $this->dataBase->readSession ( $token );
	}
	function saveDevice($userId,$device) {
		$this->dataBase->updateDevice($userId,$device);
	}
}
?>