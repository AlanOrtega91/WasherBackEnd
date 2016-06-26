<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";
if (!isset($_POST['cleanerId']) || !isset($_POST['latitud']) || !isset($_POST['longitud']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

$cleanerId = SafeString::safe($_POST['cleanerId']);
$latitud = SafeString::safe($_POST['latitud']);
$longitud = SafeString::safe($_POST['longitud']);
try{
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->updateLocation($cleanerId, $latitud, $longitud);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>