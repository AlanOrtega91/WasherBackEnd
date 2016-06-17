<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_GET['mail']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  

$mail = SafeString::safe($_GET['mail']);
try{
  $user  = new User();
  $user->sendLogOut($mail);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>