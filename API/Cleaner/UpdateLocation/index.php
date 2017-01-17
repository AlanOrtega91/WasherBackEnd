<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['latitud']) || !isset($_POST['longitud']))
  die(json_encode(array("Status"=>"ERROR missing values")));


try{
  $token = SafeString::safe($_POST['token']);
  $latitud = SafeString::safe($_POST['latitud']);
  $longitud = SafeString::safe($_POST['longitud']);
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->userHasToken($token);
  $cleanerInfo = $cleaner->updateLocation($cleanerInfo['idLavador'], $latitud, $longitud);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>