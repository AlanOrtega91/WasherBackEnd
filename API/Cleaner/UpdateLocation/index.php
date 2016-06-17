<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";
if (!isset($_GET['cleanerId']) || !isset($_GET['latitud']) || !isset($_GET['longitud']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

$cleanerId = SafeString::safe($_GET['cleanerId']);
$latitud = SafeString::safe($_GET['latitud']);
$longitud = SafeString::safe($_GET['longitud']);
try{
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->updateLocation($cleanerId, $latitud, $longitud);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>