<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";
if (!isset($_POST['newName']) || !isset($_POST['newLastName']) || !isset($_POST['newMail']) || !isset($_POST['oldMail']) ||
    !isset($_POST['newCel']) || !isset($_POST['newBillingName']) || !isset($_POST['newRFC']) || !isset($_POST['newBillingAddress']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$newName = SafeString::safe($_POST['newName']);
$newLastName = SafeString::safe($_POST['newLastName']);
$newMail = SafeString::safe($_POST['newMail']);
$oldMail = SafeString::safe($_POST['oldMail']);
$newCel =  SafeString::safe($_POST['newCel']);
$newBillingName = SafeString::safe($_POST['newBillingName']);
$newRFC = SafeString::safe($_POST['newRFC']);
$newBillingAddress = SafeString::safe($_POST['newBillingAddress']);
try{
  $user  = new User();
  $user->changeData($newName, $newLastName,$newCel, $newMail, $oldMail, $newBillingName, $newRFC, $newBillingAddress);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>