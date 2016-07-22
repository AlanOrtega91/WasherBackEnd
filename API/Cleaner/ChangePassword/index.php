<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";

if (!isset($_POST['email']) || !isset($_POST['newPassword']) || !isset($_POST['oldPassword']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $email = SafeString::safe($_POST['email']);
  $newPassword = SafeString::safe($_POST['newPassword']);
  $oldPassword = SafeString::safe($_POST['oldPassword']);
  $cleaner  = new Cleaner();
  $cleaner->changePassword($email, $newPassword, $oldPassword);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
}
?>