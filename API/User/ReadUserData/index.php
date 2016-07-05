<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$token = SafeString::safe($_POST['token']);

try{
  $user  = new User();
  $userInfo = $user->readUserData($token);
  echo json_encode(array("Status"=>"OK","User Info"=>$userInfo));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
}
?>