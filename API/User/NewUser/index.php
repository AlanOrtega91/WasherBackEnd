<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_GET['name']) || !isset($_GET['lastName']) || !isset($_GET['mail']) || !isset($_GET['password']) || !isset($_GET['cel']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  

$name = SafeString::safe($_GET['name']);
$lastName = SafeString::safe($_GET['lastName']);
$mail = SafeString::safe($_GET['mail']);
$password = SafeString::safe($_GET['password']);
$cel = SafeString::safe($_GET['cel']);
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