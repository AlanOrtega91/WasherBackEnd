<?php
require_once dirname ( __FILE__ ) . "/../DataBaseClasses/DataBaseUser.php";
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
	function sendLogIn($mail, $password) {
		$this->sendLogOut ( $mail );
		$this->dataBase->readUser ( $mail, $password );
		$token = $this->createSession ( $mail );
		
		return $this->dataBase->readUserInfo ( $token );
	}
	function createSession($mail) {
		$token = $this->dataBase->insertSession ( $mail );
		return $token;
	}
	function addUser($name, $lastName, $mail, $password, $cel) {
		$this->dataBase->insertNewUser ( $name, $lastName, $mail, $password, $cel );
		return $this->sendLogIn ( $mail, $password );
	}
	function sendLogOut($mail) {
		$this->dataBase->deletePushNotification ( $mail );
		$this->dataBase->deleteSession ( $mail );
	}
	public function saveImage($userId, $imageName) {
		$this->dataBase->updateImage ( $userId, $imageName );
	}
	function changePassword($mail, $newPassword, $oldPassword) {
		$this->dataBase->readUser ( $mail, $oldPassword );
		$this->dataBase->updatePassword ( $mail, $newPassword );
	}
	function changeData($idClient, $newName, $newLastName, $newCel, $newMail, $newBillingName, $newRFC, $newBillingAddress) {
		$this->dataBase->updateUser ( $idClient, $newName, $newLastName, $newCel, $newMail, $newBillingName, $newRFC, $newBillingAddress );
	}
	function userHasToken($token) {
		return $this->dataBase->readSession ( $token );
	}
}
?>