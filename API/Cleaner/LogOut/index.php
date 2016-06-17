<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";

if (!isset($_GET['mail']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$mail = SafeString::safe($_GET['mail']);
try{
  $cleaner  = new Cleaner();
  $cleaner->sendLogOut($mail);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>