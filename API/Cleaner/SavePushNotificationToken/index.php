<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";

if (!isset($_POST['token']) || !isset($_POST['pushNotificationToken']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $token = SafeString::safe($_POST['token']);
  $pushNotificationToken = SafeString::safe($_POST['pushNotificationToken']);
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->userHasToken($token);
  $cleaner->savePushNotificationToken($cleanerInfo['idLavador'],$pushNotificationToken);
  echo json_encode(array("Status"=>"OK"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>