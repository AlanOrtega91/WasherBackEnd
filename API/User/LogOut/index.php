<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";

if (!isset($_POST['mail']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $mail = SafeString::safe($_POST['mail']);
  $user  = new User();
  $user->sendLogOut($mail);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} 
?>