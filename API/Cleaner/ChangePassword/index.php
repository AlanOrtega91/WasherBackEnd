<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";

if (!isset($_GET['mail']) || !isset($_GET['newPassword']) || !isset($_GET['oldPassword']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$mail = SafeString::safe($_GET['mail']);
$newPassword = SafeString::safe($_GET['newPassword']);
$oldPassword = SafeString::safe($_GET['oldPassword']);
try{
  $cleaner  = new Cleaner();
  $cleaner->changePassword($mail, $newPassword, $oldPassword);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
}
?>