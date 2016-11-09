<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Inversionista.php";

if (!isset($_POST['name']) || !isset($_POST['lastName']) || !isset($_POST['email']) ||
    !isset($_POST['password']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $name = SafeString::safe($_POST['name']);
  $lastName = SafeString::safe($_POST['lastName']);
  $email = SafeString::safe($_POST['email']);
  $password = SafeString::safe($_POST['password']);
  $investor  = new Investor();
  $investorInfo = $investor->addUser($name, $lastName, $email, $password);
  
  echo json_encode(array("Status"=>"OK","User Info"=>$investorInfo));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (userNotFoundException $e) {
	echo json_encode(array("Status"=>"ERROR user not found"));
}
?>