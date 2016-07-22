<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";

if (!isset($_POST['email']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $email = SafeString::safe($_POST['email']);
  $cleaner  = new Cleaner();
  $cleaner->sendLogOut($email);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>