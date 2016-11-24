<?php
require_once dirname ( __FILE__ ) . "/DataBaseClasses/DataBaseInversionista.php";

class Investor {
	private $dataBase;
	
	public function __construct() {
		$this->dataBase = new DataBaseInvestor ();
	}
	function addUser($name, $lastName, $email, $password) {
		$this->dataBase->insertNewUser ( $name, $lastName, $email, $password );
		return $this->sendLogIn ( $email, $password );
	}
	function readUserData($token) {
		return $this->dataBase->readUserInfo ( $token );
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
	function sendLogOut($email) {
		$this->dataBase->deleteSession ( $email );
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
	
	function readCleanersForInvestor($token) {
		$cleaners = $this->dataBase->readCleaners ( $token );
		$cleanersList = array ();
		while ( $cleaner = $cleaners->fetch_assoc () ) {
			array_push ( $cleanersList, $cleaner );
		}
		return $cleanersList;
	}
	
	function readServices($idCleaner, $date, $token){
		$services = array();
		if ($idCleaner == "" && $date == ""){
			$services = $this->dataBase->readAllServices($token);
		} else if ($idCleaner == "" && $date != "") {
			$services = $this->dataBase->readAllServicesFilterDate($token, $date);
		} else if  ($idCleaner != "" && $date == "") {
			$services = $this->dataBase->readAllServicesFilterCleaner($token, $idCleaner);
		}
		else {
			$services = $this->dataBase->readAllServicesFilterAll($token,$date,$idCleaner);
		}
		$servicesList = array ();
		while ( $service = $services->fetch_assoc () ) {
			array_push ( $servicesList, $service );
		}
		return $servicesList;
		return $services;
	}
}
?>
