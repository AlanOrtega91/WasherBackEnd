<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{
  $token = SafeString::safe($_POST['token']);
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