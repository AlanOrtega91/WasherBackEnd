<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
  $token = SafeString::safe($_POST['token']);
  $user  = new User();
  $info = $user->userHasToken($token);
  $cards = Payment::readClient($info['idCliente']);
  echo json_encode(array("Status"=>"OK","cards" => $cards));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(noSessionFoundException $e)
{
  echo json_encode(array("Status"=>"SESSION ERROR"));
}
?>