<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_GET['mail']) || !isset($_GET['password']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

$mail = SafeString::safe($_GET['mail']);
$password = SafeString::safe($_GET['password']);
try{
  $user  = new User();
  $userInfo = $user->sendLogIn($mail, $password);
  echo json_encode(array("Status"=>"OK","User Info"=>$userInfo));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
}
?>