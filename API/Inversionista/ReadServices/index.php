<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Inversionista.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{
	if (isset($_POST['idLavador'])) {
		$idCleaner = SafeString::safe($_POST['idLavador']);
	} else {
		$idCleaner = "";
	}
  if (isset($_POST['fecha'])) {
  	$date = SafeString::safe($_POST['fecha']);
  } else {
  	$date = "";
  }
  $token = SafeString::safe($_POST['token']);
  $investor  = new Investor();
  $servicesInfo = $investor->readServices($idCleaner, $date, $token);
  echo json_encode(array("Status"=>"OK","Services"=>$servicesInfo));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
}
?>