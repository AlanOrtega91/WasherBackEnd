<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Status"=>"ERROR missing values")));


try{
  $token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $cards = Payment::readClient($info['idClienteConekta']);
  echo json_encode(array("Status"=>"OK","cards" => $cards));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(noSessionFoundException $e)
{
  echo json_encode(array("Status"=>"SESSION ERROR"));
}
?>