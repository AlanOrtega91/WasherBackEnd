<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";
if (!isset($_POST['mail']) || !isset($_POST['password']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

$mail = SafeString::safe($_POST['mail']);
$password = SafeString::safe($_POST['password']);
try{
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->sendLogIn($mail, $password);
  echo json_encode(array("Status"=>"OK","Cleaner Info"=>$cleanerInfo));
} catch(cleanerNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user not found"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>