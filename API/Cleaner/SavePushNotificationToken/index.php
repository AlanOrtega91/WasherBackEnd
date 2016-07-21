<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";

if (!isset($_POST['cleanerId']) || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $cleanerId = SafeString::safe($_POST['cleanerId']);
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $cleaner->savePushNotificationToken($cleanerId,$token);
  echo json_encode(array("Status"=>"OK"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
}
?>