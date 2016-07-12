<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_POST['clientId']) || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$clientId = SafeString::safe($_POST['clientId']);
$token = SafeString::safe($_POST['token']);
try{
  $user  = new User();
  $user->savePushNotificationToken($clientId,$token);
  echo json_encode(array("Status"=>"OK"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
}
?>