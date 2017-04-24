<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Inversionista.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['email']) || !isset($_POST['password']))
  die(json_encode(array("Status"=>"ERROR missing values")));


try{
  $email = SafeString::safe($_POST['email']);
  $password = SafeString::safe($_POST['password']);
  $investor  = new Investor();
  $investorInfo = $investor->sendLogIn($email, $password);
  $cleaners = $investor->readCleanersForInvestor($investorInfo['Token']);
  echo json_encode(array("Status"=>"OK","User Info"=>$investorInfo,"Lavadores" => $cleaners));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
}
?>