<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_POST['name']) || !isset($_POST['lastName']) || !isset($_POST['mail']) || !isset($_POST['password']) || !isset($_POST['cel']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  

$name = SafeString::safe($_POST['name']);
$lastName = SafeString::safe($_POST['lastName']);
$mail = SafeString::safe($_POST['mail']);
$password = SafeString::safe($_POST['password']);
$cel = SafeString::safe($_POST['cel']);
try{
  $user  = new User();
  $user->addUser($name, $lastName, $mail, $password,$cel);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>