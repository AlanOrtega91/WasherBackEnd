<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['email']) || !isset($_POST['newPassword']) || !isset($_POST['oldPassword']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  

try{
  $email = SafeString::safe($_POST['email']);
  $newPassword = SafeString::safe($_POST['newPassword']);
  $oldPassword = SafeString::safe($_POST['oldPassword']);
  $user  = new User();
  $user->changePassword($email, $newPassword, $oldPassword);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} 
?>