<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_POST['mail']) || !isset($_POST['newPassword']) || !isset($_POST['oldPassword']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$mail = SafeString::safe($_POST['mail']);
$newPassword = SafeString::safe($_POST['newPassword']);
$oldPassword = SafeString::safe($_POST['oldPassword']);
try{
  $user  = new User();
  $user->changePassword($mail, $newPassword, $oldPassword);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
}
?>