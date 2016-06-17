<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";
if (!isset($_GET['newName']) || !isset($_GET['newLastName']) || !isset($_GET['newMail']) || !isset($_GET['oldMail']) || !isset($_GET['newCel']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$newName = SafeString::safe($_GET['newName']);
$newLastName = SafeString::safe($_GET['newLastName']);
$newMail = SafeString::safe($_GET['newMail']);
$oldMail = SafeString::safe($_GET['oldMail']);
$newCel =  SafeString::safe($_GET['newCel']);
try{
  $user  = new User();
  $user->changeData($newName, $newLastName,$newCel, $newMail, $oldMail);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>